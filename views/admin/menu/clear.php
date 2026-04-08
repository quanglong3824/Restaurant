<?php
/**
 * View: admin/menu/clear.php
 * Clear menu data (IT only)
 */
?>
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/admin/menu-clear.css">

<div class="clear-page-container">
    <!-- Header -->
    <div class="clear-header">
        <h2>
            <i class="fas fa-trash-alt" style="color: #dc2626;"></i>
            <?= e($pageTitle) ?>
        </h2>
        <a href="<?= BASE_URL ?>/admin/menu" class="btn-clear-cancel">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <!-- Warning Alert -->
    <div class="clear-warning">
        <div class="clear-warning-icon">
            <i class="fas fa-exclamation-triangle"></i>
        </div>
        <div class="clear-warning-content">
            <h5>Cảnh báo quan trọng!</h5>
            <p>Hành động này sẽ <strong>xóa vĩnh viễn</strong> dữ liệu thực đơn. Không thể hoàn tác sau khi xóa.</p>
            <p style="margin-top: 0.5rem; font-size: 0.85rem;">
                <small>Dữ liệu lịch sử đơn hàng (order_items) sẽ vẫn được giữ nguyên để đảm bảo báo cáo chính xác.</small>
            </p>
        </div>
    </div>

    <!-- Current Data Stats -->
    <div class="stats-row">
        <div class="stat-card primary">
            <i class="fas fa-utensils stat-icon"></i>
            <div class="stat-value"><?= number_format($counts['items']) ?></div>
            <span class="stat-label">Món ăn</span>
        </div>
        <div class="stat-card info">
            <i class="fas fa-list stat-icon"></i>
            <div class="stat-value"><?= number_format($counts['categories']) ?></div>
            <span class="stat-label">Danh mục</span>
        </div>
        <div class="stat-card warning">
            <i class="fas fa-box-open stat-icon"></i>
            <div class="stat-value"><?= number_format($counts['sets']) ?></div>
            <span class="stat-label">Set Combo</span>
        </div>
        <div class="stat-card success">
            <i class="fas fa-clipboard-list stat-icon"></i>
            <div class="stat-value"><?= number_format($counts['setItems']) ?></div>
            <span class="stat-label">Món trong Set</span>
        </div>
    </div>

    <!-- Clear Form -->
    <div class="clear-card">
        <div class="clear-card-header">
            <h5>
                <i class="fas fa-cog"></i>
                Tùy chọn xóa
            </h5>
        </div>
        <div class="clear-card-body">
            <form action="<?= BASE_URL ?>/admin/menu/clear" method="POST" id="clearForm">
                <input type="hidden" name="confirm" value="YES_CLEAR_ALL">
                
                <!-- Clear Type Selection -->
                <label class="clear-form-label">Chọn dữ liệu cần xóa:</label>
                <div class="clear-options-grid">
                    <div class="clear-option">
                        <input type="radio" name="type" id="typeAll" value="all" checked>
                        <label for="typeAll">
                            <strong>Tất cả</strong> — Xóa mọi dữ liệu thực đơn
                        </label>
                    </div>
                    <div class="clear-option">
                        <input type="radio" name="type" id="typeItems" value="items">
                        <label for="typeItems">
                            <strong>Chỉ món ăn</strong> — Xóa <?= number_format($counts['items']) ?> món và <?= number_format($counts['setItems']) ?> món trong set
                        </label>
                    </div>
                    <div class="clear-option">
                        <input type="radio" name="type" id="typeCategories" value="categories">
                        <label for="typeCategories">
                            <strong>Chỉ danh mục</strong> — Xóa <?= number_format($counts['categories']) ?> danh mục
                        </label>
                    </div>
                    <div class="clear-option">
                        <input type="radio" name="type" id="typeSets" value="sets">
                        <label for="typeSets">
                            <strong>Chỉ Set Combo</strong> — Xóa <?= number_format($counts['sets']) ?> set
                        </label>
                    </div>
                </div>

                <!-- Confirmation Checkbox -->
                <div class="clear-confirm">
                    <div class="form-check">
                        <input type="checkbox" id="confirmCheckbox" required>
                        <label for="confirmCheckbox">
                            Tôi hiểu rằng hành động này không thể hoàn tác và xác nhận muốn xóa dữ liệu
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="clear-actions">
                    <button type="submit" class="btn-clear-submit" id="submitBtn" disabled>
                        <i class="fas fa-trash-alt"></i>
                        XÓA DỮ LIỆU NGAY
                    </button>
                    <a href="<?= BASE_URL ?>/admin/menu" class="btn-clear-cancel">
                        Hủy
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Additional Info -->
    <div class="clear-info-card">
        <h5>
            <i class="fas fa-info-circle" style="color: #0ea5e9;"></i>
            Thông tin thêm
        </h5>
        <ul>
            <li>
                <strong>Lịch sử đơn hàng:</strong> Dữ liệu trong bảng <code>order_items</code> sẽ được giữ nguyên để đảm bảo tính chính xác của báo cáo doanh thu.
            </li>
            <li>
                <strong>Ghi log:</strong> Hành động xóa sẽ được ghi lại trong <code>activity_log</code> để kiểm tra sau này.
            </li>
            <li>
                <strong>Chỉ IT:</strong> Chỉ vai trò IT mới có quyền thực hiện thao tác này.
            </li>
        </ul>
    </div>
</div>

<script>
    // Enable submit button when confirmation checkbox is checked
    document.getElementById('confirmCheckbox').addEventListener('change', function() {
        document.getElementById('submitBtn').disabled = !this.checked;
    });

    // Confirm before submit
    document.getElementById('clearForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có CHẮC CHẮN muốn xóa dữ liệu? Hành động này KHÔNG THỂ HOÀN TÁC!')) {
            return;
        }
        
        // Show loading state
        const submitBtn = document.getElementById('submitBtn');
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Đang xử lý...';
        submitBtn.disabled = true;
        
        // Submit form
        this.submit();
    });
</script>