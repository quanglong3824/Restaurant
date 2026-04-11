<?php
// ============================================================
// AdminCategoryController — Full CRUD
// ============================================================

require_once BASE_PATH . '/models/MenuCategory.php';
require_once BASE_PATH . '/models/MenuType.php';
require_once BASE_PATH . '/models/ActivityLog.php';

class AdminCategoryController extends Controller
{
    private MenuCategory $model;
    private MenuType $typeModel;
    private ActivityLog $activityLog;

    public function __construct()
    {
        $this->model = new MenuCategory();
        $this->typeModel = new MenuType();
        $this->activityLog = new ActivityLog();
    }

    /** GET /admin/categories */
    public function index(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);
        
        $page = max(1, (int) $this->input('page', 1));
        $limit = 10;
        
        $allCategories = $this->model->getAll();
        $total = count($allCategories);
        $offset = ($page - 1) * $limit;
        
        $categories = array_slice($allCategories, $offset, $limit);
        $totalPages = ceil($total / $limit);
        
        // Lấy danh sách loại menu từ bảng menu_types
        $menuTypes = $this->typeModel->getActive();
        
        $this->view('layouts/admin', [
            'view' => 'admin/categories/index',
            'pageTitle' => 'Danh Mục Món',
            'pageSubtitle' => $total . ' danh mục',
            'categories' => $categories,
            'menuTypes' => $menuTypes,
            'editItem' => null,
            'pagination' => [
                'page' => $page,
                'limit' => $limit,
                'total' => $total,
                'totalPages' => $totalPages,
            ],
        ]);
    }

    /** POST /admin/categories/store */
    public function store(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $name = trim((string) $this->input('name', ''));
        if (!$name) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tên danh mục không được để trống.'];
            $this->redirect('/admin/categories');
        }

        // Auto calculate sort_order: max + 1
        $allCategories = $this->model->getAll();
        $maxSort = 0;
        foreach ($allCategories as $cat) {
            if ((int)($cat['sort_order'] ?? 0) > $maxSort) {
                $maxSort = (int)($cat['sort_order'] ?? 0);
            }
        }

        $data = [
            'name' => $name,
            'name_en' => trim((string) $this->input('name_en', '')) ?: null,
            'menu_type' => $this->input('menu_type', 'asia'),
            'icon' => $this->input('icon', 'fa-utensils'),
            'sort_order' => $maxSort + 1,
        ];
        
        $id = $this->model->create($data);
        
        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_CREATE,
            'menu_category',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã thêm danh mục!'];
        $this->redirect('/admin/categories');
    }

    /** GET /admin/categories/edit?id= */
    public function edit(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $item = $this->model->findById($id);
        if (!$item) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Không tìm thấy danh mục.'];
            $this->redirect('/admin/categories');
        }

        $categories = $this->model->getAll();
        $menuTypes = $this->typeModel->getActive();
        
        $this->view('layouts/admin', [
            'view' => 'admin/categories/index',
            'pageTitle' => 'Danh Mục Món',
            'pageSubtitle' => count($categories) . ' danh mục',
            'categories' => $categories,
            'menuTypes' => $menuTypes,
            'editItem' => $item,   // truyền item đang edit
        ]);
    }

    /** POST /admin/categories/update */
    public function update(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $name = trim((string) $this->input('name', ''));
        if (!$name) {
            $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Tên không được để trống.'];
            $this->redirect('/admin/categories');
        }

        $data = [
            'name' => $name,
            'name_en' => trim((string) $this->input('name_en', '')) ?: null,
            'menu_type' => $this->input('menu_type', 'asia'),
            'icon' => $this->input('icon', 'fa-utensils'),
            'sort_order' => (int) $this->input('sort_order', 0),
            'is_active' => (int) $this->input('is_active', 1),
        ];
        
        $this->model->update($id, $data);
        
        // Log activity
        $this->activityLog->log(
            ActivityLog::ACTION_UPDATE,
            'menu_category',
            $id,
            $data,
            ActivityLog::LEVEL_INFO
        );
        
        $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã cập nhật danh mục!'];
        $this->redirect('/admin/categories');
    }

    /** POST /admin/categories/delete */
    public function delete(): void
    {
        Auth::requireRole(ROLE_ADMIN, ROLE_IT);

        $id = (int) $this->input('id');
        $category = $this->model->findById($id);
        $ok = $this->model->delete($id);
        if (!$ok) {
            $_SESSION['flash'] = ['type' => 'warning', 'message' => 'Không thể xóa — danh mục còn chứa món ăn.'];
            
            // Log failed delete
            $this->activityLog->log(
                ActivityLog::ACTION_DELETE,
                'menu_category',
                $id,
                ['reason' => 'has_items', 'name' => $category['name'] ?? 'unknown'],
                ActivityLog::LEVEL_WARNING
            );
        } else {
            $_SESSION['flash'] = ['type' => 'success', 'message' => 'Đã xóa danh mục.'];
            
            // Log successful delete
            $this->activityLog->log(
                ActivityLog::ACTION_DELETE,
                'menu_category',
                $id,
                ['name' => $category['name'] ?? 'unknown'],
                ActivityLog::LEVEL_INFO
            );
        }
        $this->redirect('/admin/categories');
    }
}
