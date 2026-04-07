<?php
// ============================================================
// AdminMenuController — Admin: Manage Menu Items
// ============================================================

require_once BASE_PATH . '/models/MenuItem.php';
require_once BASE_PATH . '/models/MenuCategory.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class AdminMenuController extends Controller
{
    private MenuItem $itemModel;
    private MenuCategory $categoryModel;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->itemModel = new MenuItem();
        $this->categoryModel = new MenuCategory();
        $this->activityLog = new ActivityLog();
    }

    /** GET /admin/menu */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $items = $this->itemModel->getAll();
        $categories = $this->categoryModel->getAll();

        $this->view('layouts/admin', [
            'view' => 'admin/menu/index',
            'pageTitle' => 'Quản lý Món ăn',
            'pageSubtitle' => count($items) . ' món',
            'items' => $items,
            'categories' => $categories,
        ]);
    }

    /** GET /admin/menu/create */
    public function create(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $categories = $this->categoryModel->getActive();
        $this->view('layouts/admin', [
            'view' => 'admin/menu/form',
            'pageTitle' => 'Thêm Món',
            'categories' => $categories,
            'item' => null,
        ]);
    }

    /** POST /admin/menu/store */
    public function store(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $data = $this->collectFormData();

        if (!empty($_FILES['image']['name'])) {
            $uploaded = uploadMenuImage($_FILES['image']);
            if ($uploaded)
                $data['image'] = $uploaded;
        }

        $id = $this->itemModel->create($data);
        $this->handleGalleryUpload($id);

        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_CREATE,
            'menu_item',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm món thành công!'];
        $this->redirect('/admin/menu');
    }

    /** GET /admin/menu/edit?id= */
    public function edit(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $item = $this->itemModel->findById($id);
        if (!$item)
            $this->redirect('/admin/menu');

        $categories = $this->categoryModel->getActive();
        $this->view('layouts/admin', [
            'view' => 'admin/menu/form',
            'pageTitle' => 'Sửa Món',
            'categories' => $categories,
            'item' => $item,
        ]);
    }

    /** POST /admin/menu/update */
    public function update(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $data = $this->collectFormData();

        if (!empty($_FILES['image']['name'])) {
            $uploaded = uploadMenuImage($_FILES['image']);
            if ($uploaded) {
                $data['image'] = $uploaded;
                $this->itemModel->updateImage($id, $uploaded);
            }
        }

        $this->itemModel->update($id, $data);
        $this->handleGalleryUpload($id);

        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_UPDATE,
            'menu_item',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );

        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã cập nhật món!'];
        $this->redirect('/admin/menu');
    }

    /** POST /admin/menu/delete */
    public function delete(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        if ($id <= 0) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'ID món không hợp lệ.'];
            $this->redirect('/admin/menu');
        }

        try {
            $db = getDB();

            // Kiểm tra món có đang được tham chiếu trong lịch sử order không
            $stmt = $db->prepare("SELECT COUNT(*) FROM order_items WHERE menu_item_id = ?");
            $stmt->execute([$id]);
            $inUse = (int) $stmt->fetchColumn();

            if ($inUse > 0) {
                // Có FK → soft delete: ẩn khỏi menu thay vì xóa cứng
                $db->prepare("UPDATE menu_items SET is_active = 0, is_available = 0 WHERE id = ?")
                   ->execute([$id]);
                
                // Log activity
                $this->activityLog->log(
                    ActivityLog::ACTION_DELETE,
                    'menu_item',
                    $id,
                    ['reason' => 'in_use', 'order_count' => $inUse],
                    ActivityLog::LEVEL_WARNING
                );
                
                $_SESSION['flash'] = [
                    'type' => 'warning',
                    'message' => "Món này đang có trong {$inUse} lịch sử đơn hàng, không thể xóa hoàn toàn. Đã ẩn khỏi menu.",
                ];
            } else {
                // Không có FK → xóa cứng bình thường
                $this->itemModel->delete($id);
                
                // Log activity
                $this->activityLog->log(
                    ActivityLog::ACTION_DELETE,
                    'menu_item',
                    $id,
                    ['reason' => 'not_in_use'],
                    ActivityLog::LEVEL_INFO
                );
                
                $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa món thành công.'];
            }
        } catch (\Throwable $e) {
            // Fallback: nếu vẫn lỗi FK → chỉ ẩn món
            try {
                getDB()->prepare("UPDATE menu_items SET is_active = 0, is_available = 0 WHERE id = ?")
                       ->execute([$id]);
                $_SESSION['flash'] = [
                    'type'    => 'warning',
                    'message' => 'Không thể xóa hoàn toàn (đang có dữ liệu liên kết). Đã ẩn khỏi menu.',
                ];
            } catch (\Throwable $e2) {
                $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Lỗi xóa món: ' . $e->getMessage()];
            }
        }

        $this->redirect('/admin/menu');
    }

    /** POST /admin/menu/toggle — Toggle hết hàng/còn hàng */
    public function toggle(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $type = (string) $this->input('type', 'available'); // available | active

        if ($type === 'active') {
            $this->itemModel->toggleActive($id);
        } else {
            $this->itemModel->toggleAvailable($id);
        }

        $item = $this->itemModel->findById($id);
        $this->json(['ok' => true, 'is_available' => $item['is_available'], 'is_active' => $item['is_active']]);
    }

    private function collectFormData(): array
    {
        $tags = $this->input('tags', []);
        if (!is_array($tags)) $tags = [];

        return [
            'category_id'     => (int) $this->input('category_id'),
            'name'            => trim((string) $this->input('name', '')),
            'name_en'         => trim((string) $this->input('name_en', '')) ?: null,
            'description'     => trim((string) $this->input('description', '')) ?: null,
            'price'           => (float) $this->input('price', 0),
            'stock'           => (int) $this->input('stock', -1),
            'tags'            => !empty($tags) ? implode(',', array_unique($tags)) : null,
            'note_options'    => trim((string) $this->input('note_options', '')) ?: null,
            'note_options_en' => trim((string) $this->input('note_options_en', '')) ?: null,
            'sort_order'      => (int) $this->input('sort_order', 0),
            'is_active'       => (int) $this->input('is_active', 1),
            'service_type'    => trim((string) $this->input('service_type', 'both')),
        ];
    }

    private function handleGalleryUpload(int $itemId): void
    {
        if (empty($_FILES['gallery']['name'][0]))
            return;

        foreach ($_FILES['gallery']['name'] as $key => $name) {
            if ($_FILES['gallery']['error'][$key] === UPLOAD_ERR_OK) {
                $file = [
                    'name' => $name,
                    'type' => $_FILES['gallery']['type'][$key],
                    'tmp_name' => $_FILES['gallery']['tmp_name'][$key],
                    'error' => $_FILES['gallery']['error'][$key],
                    'size' => $_FILES['gallery']['size'][$key]
                ];
                $uploaded = uploadMenuImage($file);
                if ($uploaded) {
                    $this->itemModel->addGalleryImage($itemId, $uploaded);
                }
            }
        }
    }
}
