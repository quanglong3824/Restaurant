<?php
// ============================================================
// AdminMenuTypeController — CRUD Menu Types (Á, Âu, Alacarte, Khác)
// ============================================================

require_once BASE_PATH . '/models/MenuType.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class AdminMenuTypeController extends Controller
{
    private MenuType $model;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->model = new MenuType();
        $this->activityLog = new ActivityLog();
    }

    /** GET /admin/menu-types */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        
        $allTypes = $this->model->getAll();
        
        // Đếm số danh mục thuộc mỗi loại
        $db = getDB();
        $categoryCounts = [];
        foreach ($allTypes as $type) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM menu_categories WHERE menu_type = ?");
            $stmt->execute([$type['type_key']]);
            $categoryCounts[$type['id']] = (int) $stmt->fetchColumn();
        }
        
        $this->view('layouts/admin', [
            'view' => 'admin/menu-types/index',
            'pageTitle' => 'Phân Loại Menu',
            'pageSubtitle' => count($allTypes) . ' loại',
            'types' => $allTypes,
            'categoryCounts' => $categoryCounts,
            'editItem' => null,
        ]);
    }

    /** POST /admin/menu-types/store */
    public function store(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $name = trim((string) $this->input('name', ''));
        if (!$name) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tên loại menu không được để trống.'];
            $this->redirect('/admin/menu-types');
        }

        $typeKey = trim((string) $this->input('type_key', ''));
        if (!$typeKey) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Mã loại menu không được để trống.'];
            $this->redirect('/admin/menu-types');
        }

        // Kiểm tra type_key đã tồn tại chưa
        $existing = $this->model->findByKey($typeKey);
        if ($existing) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Mã loại menu đã tồn tại.'];
            $this->redirect('/admin/menu-types');
        }

        $data = [
            'name' => $name,
            'name_en' => trim((string) $this->input('name_en', '')) ?: null,
            'type_key' => $typeKey,
            'description' => trim((string) $this->input('description', '')) ?: null,
            'color' => $this->input('color', '#0ea5e9'),
            'icon' => $this->input('icon', 'fa-utensils'),
            'sort_order' => (int) $this->input('sort_order', 0),
        ];
        
        $id = $this->model->create($data);
        
        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_CREATE,
            'menu_type',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm loại menu!'];
        $this->redirect('/admin/menu-types');
    }

    /** GET /admin/menu-types/edit?id= */
    public function edit(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $item = $this->model->findById($id);
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không tìm thấy loại menu.'];
            $this->redirect('/admin/menu-types');
        }

        $types = $this->model->getAll();
        
        // Đếm số danh mục thuộc mỗi loại
        $db = getDB();
        $categoryCounts = [];
        foreach ($types as $type) {
            $stmt = $db->prepare("SELECT COUNT(*) FROM menu_categories WHERE menu_type = ?");
            $stmt->execute([$type['type_key']]);
            $categoryCounts[$type['id']] = (int) $stmt->fetchColumn();
        }

        $this->view('layouts/admin', [
            'view' => 'admin/menu-types/index',
            'pageTitle' => 'Phân Loại Menu',
            'pageSubtitle' => count($types) . ' loại',
            'types' => $types,
            'categoryCounts' => $categoryCounts,
            'editItem' => $item,
        ]);
    }

    /** POST /admin/menu-types/update */
    public function update(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $name = trim((string) $this->input('name', ''));
        if (!$name) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tên không được để trống.'];
            $this->redirect('/admin/menu-types');
        }

        $typeKey = trim((string) $this->input('type_key', ''));
        if (!$typeKey) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Mã loại menu không được để trống.'];
            $this->redirect('/admin/menu-types');
        }

        // Kiểm tra type_key đã tồn tại (trừ current id)
        $existing = $this->model->findByKey($typeKey);
        if ($existing && (int)$existing['id'] !== $id) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Mã loại menu đã tồn tại.'];
            $this->redirect('/admin/menu-types');
        }

        $data = [
            'name' => $name,
            'name_en' => trim((string) $this->input('name_en', '')) ?: null,
            'type_key' => $typeKey,
            'description' => trim((string) $this->input('description', '')) ?: null,
            'color' => $this->input('color', '#0ea5e9'),
            'icon' => $this->input('icon', 'fa-utensils'),
            'sort_order' => (int) $this->input('sort_order', 0),
            'is_active' => (int) $this->input('is_active', 1),
        ];
        
        $this->model->update($id, $data);
        
        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_UPDATE,
            'menu_type',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã cập nhật loại menu!'];
        $this->redirect('/admin/menu-types');
    }

    /** POST /admin/menu-types/delete */
    public function delete(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $type = $this->model->findById($id);
        $ok = $this->model->delete($id);
        
        if (!$ok) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Không thể xóa — loại menu còn chứa danh mục món.'];
            
            // Log failed delete
            $this->activityLog->log(
                ActivityLog::ACTION_DELETE,
                'menu_type',
                $id,
                ['reason' => 'has_categories', 'name' => $type['name'] ?? 'unknown'],
                ActivityLog::LEVEL_WARNING
            );
        } else {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa loại menu.'];
            
            // Log successful delete
            $this->activityLog->log(
                ActivityLog::ACTION_DELETE,
                'menu_type',
                $id,
                ['name' => $type['name'] ?? 'unknown'],
                ActivityLog::LEVEL_INFO
            );
        }
        $this->redirect('/admin/menu-types');
    }

    /** POST /admin/menu-types/toggle */
    public function toggle(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $this->model->toggleActive($id);

        $type = $this->model->findById($id);
        $this->json(['ok' => true, 'is_active' => $type['is_active']]);
    }
}