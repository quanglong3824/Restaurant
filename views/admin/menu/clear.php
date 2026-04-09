<?php
/**
 * View: admin/menu/clear.php
 * Clear menu data (IT only)
 */
?>

<style>
    .clear-page-container {
        padding: 1.5rem;
        max-width: 1200px;
        margin: 0 auto;
    }
    
    .clear-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
        gap: 1rem;
    }
    
    .clear-header h2 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-primary, #1e293b);
    }
    
    .clear-warning {
        background: linear-gradient(135deg, #fef3c7 0%, #fde68a 100%);
        border: 2px solid #f59e0b;
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 1.5rem;
        display: flex;
        gap: 1rem;
        align-items: flex-start;
    }
    
    .clear-warning-icon {
        font-size: 2rem;
        color: #d97706;
        flex-shrink: 0;
    }
    
    .clear-warning-content h5 {
        margin: 0 0 0.5rem 0;
        color: #92400e;
        font-weight: 700;
    }
    
    .clear-warning-content p {
        margin: 0.25rem 0;
        color: #78350f;
        font-size: 0.9rem;
    }
    
    .stats-row {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    
    .stat-card {
        background: #fff;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 12px;
        padding: 1.25rem;
        text-align: center;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 16px rgba(0,0,0,0.1);
    }
    
    .stat-icon {
        font-size: 2rem;
        margin-bottom: 0.75rem;
        display: block;
    }
    
    .stat-value {
        font-size: 2rem;
        font-weight: 800;
        color: var(--text-primary, #1e293b);
        margin: 0;
        line-height: 1;
    }
    
    .stat-label {
        font-size: 0.8rem;
        color: var(--text-secondary, #64748b);
        margin-top: 0.5rem;
        display: block;
        font-weight: 600;
    }
    
    .stat-card.primary .stat-icon { color: #0ea5e9; }
    .stat-card.info .stat-icon { color: #06b6d4; }
    .stat-card.warning .stat-icon { color: #f59e0b; }
    .stat-card.success .stat-icon { color: #16a34a; }
    
    .clear-card {
        background: #fff;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 12px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
        margin-bottom: 1rem;
    }
    
    .clear-card-header {
        padding: 1rem 1.25rem;
        border-bottom: 1px solid var(--border-color, #e5e7eb);
        background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        border-radius: 12px 12px 0 0;
    }
    
    .clear-card-header h5 {
        margin: 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-primary, #1e293b);
        font-weight: 600;
    }
    
    .clear-card-body {
        padding: 1.25rem;
    }
    
    .clear-form-label {
        font-weight: 700;
        color: var(--text-primary, #1e293b);
        margin-bottom: 1rem;
        display: block;
        font-size: 1rem;
    }
    
    .clear-options-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
        gap: 0.75rem;
        margin-bottom: 1.25rem;
    }
    
    .clear-option {
        display: flex;
        align-items: flex-start;
        gap: 0.5rem;
        padding: 0.75rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.15s;
    }
    
    .clear-option:hover {
        background: #f1f5f9;
    }
    
    .clear-option input[type="radio"] {
        margin-top: 0.25rem;
        accent-color: var(--gold, #d4af37);
    }
    
    .clear-option label {
        cursor: pointer;
        flex: 1;
        font-size: 0.9rem;
        color: var(--text-primary, #1e293b);
    }
    
    .clear-option strong {
        color: var(--gold-dark, #a16207);
        font-weight: 700;
    }
    
    .clear-confirm {
        background: linear-gradient(135deg, #fee2e2 0%, #fecaca 100%);
        border: 2px solid #ef4444;
        border-radius: 10px;
        padding: 1rem;
        margin-bottom: 1.25rem;
    }
    
    .clear-confirm .form-check {
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }
    
    .clear-confirm input[type="checkbox"] {
        width: 1.25rem;
        height: 1.25rem;
        margin-top: 0.1rem;
        accent-color: #dc2626;
    }
    
    .clear-confirm label {
        font-weight: 700;
        color: #991b1b;
        cursor: pointer;
        font-size: 0.95rem;
    }
    
    .clear-actions {
        display: flex;
        gap: 0.75rem;
        flex-wrap: wrap;
    }
    
    .btn-clear-submit {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        color: #fff;
        border: none;
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 700;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        box-shadow: 0 4px 12px rgba(220, 38, 38, 0.3);
    }
    
    .btn-clear-submit:hover:not(:disabled) {
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(220, 38, 38, 0.4);
    }
    
    .btn-clear-submit:disabled {
        opacity: 0.5;
        cursor: not-allowed;
        transform: none;
    }
    
    .btn-clear-cancel {
        background: #fff;
        color: var(--text-primary, #1e293b);
        border: 2px solid var(--border-color, #e5e7eb);
        padding: 0.875rem 1.75rem;
        border-radius: 10px;
        font-weight: 600;
        font-size: 1rem;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        text-decoration: none;
    }
    
    .btn-clear-cancel:hover {
        border-color: var(--gold, #d4af37);
        color: var(--gold, #d4af37);
    }
    
    .clear-info-card {
        background: #fff;
        border: 1px solid var(--border-color, #e5e7eb);
        border-radius: 12px;
        padding: 1.25rem;
        margin-top: 1rem;
    }
    
    .clear-info-card h5 {
        margin: 0 0 1rem 0;
        display: flex;
        align-items: center;
        gap: 0.5rem;
        color: var(--text-primary, #1e293b);
        font-weight: 600;
    }
    
    .clear-info-card ul {
        margin: 0;
        padding-left: 1.25rem;
    }
    
    .clear-info-card li {
        margin-bottom: 0.5rem;
        color: var(--text-secondary, #64748b);
        font-size: 0.9rem;
        line-height: 1.6;
    }
    
    .clear-info-card li:last-child {
        margin-bottom: 0;
    }
    
    .clear-info-card code {
        background: #f1f5f9;
        padding: 0.15rem 0.4rem;
        border-radius: 4px;
        font-family: 'Consolas', 'Monaco', monospace;
        font-size: 0.85em;
        color: #dc2626;
    }
</style>

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