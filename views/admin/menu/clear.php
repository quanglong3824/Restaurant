<?php
/**
 * View: admin/menu/clear.php
 * Clear menu data (IT only)
 */
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <!-- Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="mb-0">
                    <i class="fas fa-trash-alt text-danger me-2"></i>
                    <?= e($pageTitle) ?>
                </h2>
                <a href="/admin/menu" class="btn btn-outline-secondary">
                    <i class="fas fa-arrow-left me-1"></i> Quay lại
                </a>
            </div>

            <!-- Warning Alert -->
            <div class="alert alert-warning border-0">
                <div class="d-flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-triangle fa-2x"></i>
                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h5 class="alert-heading">Cảnh báo quan trọng!</h5>
                        <p class="mb-0">
                            Hành động này sẽ <strong>xóa vĩnh viễn</strong> dữ liệu thực đơn. 
                            Không thể hoàn tác sau khi xóa.
                        </p>
                        <p class="mb-0 mt-2">
                            <small>
                                Dữ liệu lịch sử đơn hàng (order_items) sẽ vẫn được giữ nguyên để đảm bảo báo cáo chính xác.
                            </small>
                        </p>
                    </div>
                </div>
            </div>

            <!-- Current Data Stats -->
            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-utensils fa-2x text-primary mb-2"></i>
                            <h3 class="mb-0"><?= number_format($counts['items']) ?></h3>
                            <small class="text-muted">Món ăn</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-list fa-2x text-info mb-2"></i>
                            <h3 class="mb-0"><?= number_format($counts['categories']) ?></h3>
                            <small class="text-muted">Danh mục</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-box-open fa-2x text-warning mb-2"></i>
                            <h3 class="mb-0"><?= number_format($counts['sets']) ?></h3>
                            <small class="text-muted">Set Combo</small>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="card border-0 shadow-sm">
                        <div class="card-body text-center">
                            <i class="fas fa-clipboard-list fa-2x text-success mb-2"></i>
                            <h3 class="mb-0"><?= number_format($counts['setItems']) ?></h3>
                            <small class="text-muted">Món trong Set</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Clear Form -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-cog me-2"></i>
                        Tùy chọn xóa
                    </h5>
                </div>
                <div class="card-body">
                    <form action="/admin/menu/clear" method="POST" id="clearForm">
                        <input type="hidden" name="confirm" value="YES_CLEAR_ALL">
                        
                        <!-- Clear Type Selection -->
                        <div class="mb-4">
                            <label class="form-label fw-bold">Chọn dữ liệu cần xóa:</label>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="type" id="typeAll" value="all" checked>
                                        <label class="form-check-label" for="typeAll">
                                            <strong>Tất cả</strong> - Xóa mọi dữ liệu thực đơn
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="type" id="typeItems" value="items">
                                        <label class="form-check-label" for="typeItems">
                                            <strong>Chỉ món ăn</strong> - Xóa <?= number_format($counts['items']) ?> món và <?= number_format($counts['setItems']) ?> món trong set
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="type" id="typeCategories" value="categories">
                                        <label class="form-check-label" for="typeCategories">
                                            <strong>Chỉ danh mục</strong> - Xóa <?= number_format($counts['categories']) ?> danh mục
                                        </label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="radio" name="type" id="typeSets" value="sets">
                                        <label class="form-check-label" for="typeSets">
                                            <strong>Chỉ Set Combo</strong> - Xóa <?= number_format($counts['sets']) ?> set
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Confirmation Checkbox -->
                        <div class="alert alert-danger">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="confirmCheckbox" required>
                                <label class="form-check-label fw-bold" for="confirmCheckbox">
                                    Tôi hiểu rằng hành động này không thể hoàn tác và xác nhận muốn xóa dữ liệu
                                </label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-danger btn-lg" id="submitBtn" disabled>
                                <i class="fas fa-trash-alt me-2"></i>
                                XÓA DỮ LIỆU NGAY
                            </button>
                            <a href="/admin/menu" class="btn btn-outline-secondary btn-lg">
                                Hủy
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Additional Info -->
            <div class="card border-0 shadow-sm mt-4">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-info-circle me-2"></i>
                        Thông tin thêm
                    </h5>
                </div>
                <div class="card-body">
                    <ul class="mb-0">
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
        </div>
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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i> Đang xử lý...';
        submitBtn.disabled = true;
        
        // Submit form
        this.submit();
    });
</script>