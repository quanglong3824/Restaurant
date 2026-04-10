<?php
/**
 * View: Cài đặt hệ thống
 * Dành cho IT/Admin quản lý các tùy chọn hệ thống
 */
$settingsByKey = [];
foreach ($settings as $s) {
    $settingsByKey[$s['setting_key']] = $s;
}
?>

<div class="pos-container">
    <div class="pos-header">
        <div>
            <h2><i class="fas fa-cog me-2"></i>Cài Đặt Hệ Thống</h2>
            <p class="text-muted">Quản lý các tùy chọn hệ thống quan trọng</p>
        </div>
        <button class="btn-primary" onclick="showAddSettingModal()">
            <i class="fas fa-plus me-2"></i>Thêm Cài Đặt
        </button>
    </div>

    <?php if (isset($_SESSION['flash'])): ?>
        <div class="alert alert-<?= $_SESSION['flash']['type'] === 'success' ? 'success' : 'danger' ?> alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['flash']['message']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
        <?php unset($_SESSION['flash']); ?>
    <?php endif; ?>

    <div class="pos-grid">
        <!-- DEV MODE Setting -->
        <div class="pos-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5><i class="fas fa-code me-2"></i>Chế Độ Phát Triển (DEV MODE)</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Tắt kiểm tra vị trí để dev có thể test từ xa</p>
                    </div>
                    <span class="badge <?= ($settingsByKey['dev_mode']['setting_value'] ?? '0') === '1' ? 'badge-success' : 'badge-secondary' ?>">
                        <?= ($settingsByKey['dev_mode']['setting_value'] ?? '0') === '1' ? 'ĐANG BẬT' : 'ĐANG TẮT' ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/it/settings/update" class="setting-form">
                    <input type="hidden" name="setting_key" value="dev_mode">
                    <input type="hidden" name="description" value="Chế độ phát triển - Tắt kiểm tra vị trí để dev có thể test từ xa">
                    
                    <div class="toggle-switch-wrapper">
                        <label class="toggle-switch">
                            <input type="checkbox" name="setting_value" value="1" 
                                   <?= ($settingsByKey['dev_mode']['setting_value'] ?? '0') === '1' ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label"><?= ($settingsByKey['dev_mode']['setting_value'] ?? '0') === '1' ? 'BẬT' : 'TẮT' ?></span>
                    </div>
                    
                    <div class="form-note" style="margin-top: 1rem; padding: 0.75rem; background: #fff3cd; border-radius: 8px; font-size: 0.85rem; color: #856404;">
                        <i class="fas fa-info-circle me-1"></i>
                        <strong>Lưu ý:</strong> Khi BẬT, nhân viên có thể test đặt món từ bất kỳ đâu mà không cần đến quán. 
                        Chỉ sử dụng trong quá trình phát triển và kiểm thử.
                    </div>
                    
                    <button type="submit" class="btn-primary mt-3">
                        <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                    </button>
                </form>
            </div>
        </div>

        <!-- Maintenance Mode Setting -->
        <div class="pos-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5><i class="fas fa-tools me-2"></i>Chế Độ Bảo Trì</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Ẩn website khỏi khách hàng khi bảo trì</p>
                    </div>
                    <span class="badge <?= ($settingsByKey['maintenance_mode']['setting_value'] ?? '0') === '1' ? 'badge-success' : 'badge-secondary' ?>">
                        <?= ($settingsByKey['maintenance_mode']['setting_value'] ?? '0') === '1' ? 'ĐANG BẬT' : 'ĐANG TẮT' ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/it/settings/update" class="setting-form">
                    <input type="hidden" name="setting_key" value="maintenance_mode">
                    <input type="hidden" name="description" value="Chế độ bảo trì - Ẩn website khỏi khách hàng">
                    
                    <div class="toggle-switch-wrapper">
                        <label class="toggle-switch">
                            <input type="checkbox" name="setting_value" value="1" 
                                   <?= ($settingsByKey['maintenance_mode']['setting_value'] ?? '0') === '1' ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label"><?= ($settingsByKey['maintenance_mode']['setting_value'] ?? '0') === '1' ? 'BẬT' : 'TẮT' ?></span>
                    </div>
                    
                    <div class="form-note" style="margin-top: 1rem; padding: 0.75rem; background: #f8d7da; border-radius: 8px; font-size: 0.85rem; color: #721c24;">
                        <i class="fas fa-exclamation-triangle me-1"></i>
                        <strong>Cảnh báo:</strong> Khi BẬT, tất cả khách hàng sẽ không thể truy cập menu và đặt món.
                    </div>
                    
                    <button type="submit" class="btn-primary mt-3">
                        <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                    </button>
                </form>
            </div>
        </div>

        <!-- Auto Print Orders Setting -->
        <div class="pos-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5><i class="fas fa-print me-2"></i>Tự Động In Đơn</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Tự động in đơn hàng khi có order mới</p>
                    </div>
                    <span class="badge <?= ($settingsByKey['auto_print_orders']['setting_value'] ?? '1') === '1' ? 'badge-success' : 'badge-secondary' ?>">
                        <?= ($settingsByKey['auto_print_orders']['setting_value'] ?? '1') === '1' ? 'ĐANG BẬT' : 'ĐANG TẮT' ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/it/settings/update" class="setting-form">
                    <input type="hidden" name="setting_key" value="auto_print_orders">
                    <input type="hidden" name="description" value="Tự động in đơn hàng khi có order mới">
                    
                    <div class="toggle-switch-wrapper">
                        <label class="toggle-switch">
                            <input type="checkbox" name="setting_value" value="1" 
                                   <?= ($settingsByKey['auto_print_orders']['setting_value'] ?? '1') === '1' ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label"><?= ($settingsByKey['auto_print_orders']['setting_value'] ?? '1') === '1' ? 'BẬT' : 'TẮT' ?></span>
                    </div>
                    
                    <button type="submit" class="btn-primary mt-3">
                        <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                    </button>
                </form>
            </div>
        </div>

        <!-- Allow Online Payment Setting -->
        <div class="pos-card">
            <div class="card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <h5><i class="fas fa-credit-card me-2"></i>Thanh Toán Online</h5>
                        <p class="text-muted mb-0" style="font-size: 0.85rem;">Cho phép khách thanh toán qua QR/chuyển khoản</p>
                    </div>
                    <span class="badge <?= ($settingsByKey['allow_online_payment']['setting_value'] ?? '1') === '1' ? 'badge-success' : 'badge-secondary' ?>">
                        <?= ($settingsByKey['allow_online_payment']['setting_value'] ?? '1') === '1' ? 'ĐANG BẬT' : 'ĐANG TẮT' ?>
                    </span>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="<?= BASE_URL ?>/it/settings/update" class="setting-form">
                    <input type="hidden" name="setting_key" value="allow_online_payment">
                    <input type="hidden" name="description" value="Cho phép thanh toán online">
                    
                    <div class="toggle-switch-wrapper">
                        <label class="toggle-switch">
                            <input type="checkbox" name="setting_value" value="1" 
                                   <?= ($settingsByKey['allow_online_payment']['setting_value'] ?? '1') === '1' ? 'checked' : '' ?>>
                            <span class="toggle-slider"></span>
                        </label>
                        <span class="toggle-label"><?= ($settingsByKey['allow_online_payment']['setting_value'] ?? '1') === '1' ? 'BẬT' : 'TẮT' ?></span>
                    </div>
                    
                    <button type="submit" class="btn-primary mt-3">
                        <i class="fas fa-save me-2"></i>Lưu Thay Đổi
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- All Settings Table -->
    <div class="pos-card mt-4">
        <div class="card-header">
            <h5><i class="fas fa-list me-2"></i>Tất Cả Cài Đặt</h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="pos-table">
                    <thead>
                        <tr>
                            <th>Khóa</th>
                            <th>Giá Trị</th>
                            <th>Mô Tả</th>
                            <th>Cập Nhật</th>
                            <th>Hành Động</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($settings as $setting): ?>
                        <tr>
                            <td><code><?= htmlspecialchars($setting['setting_key']) ?></code></td>
                            <td><strong><?= htmlspecialchars($setting['setting_value']) ?></strong></td>
                            <td><?= htmlspecialchars($setting['description'] ?? 'Không có mô tả') ?></td>
                            <td><?= date('d/m/Y H:i', strtotime($setting['updated_at'])) ?></td>
                            <td>
                                <form method="POST" action="<?= BASE_URL ?>/it/settings/reset" style="display: inline;" onsubmit="return confirm('Bạn có chắc muốn đặt lại cài đặt này về mặc định?')">
                                    <input type="hidden" name="setting_key" value="<?= htmlspecialchars($setting['setting_key']) ?>">
                                    <button type="submit" class="btn-sm btn-danger">
                                        <i class="fas fa-undo"></i> Reset
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
.pos-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
    gap: 1.5rem;
}

.pos-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    overflow: hidden;
}

.card-header {
    padding: 1.25rem;
    background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
    border-bottom: 1px solid #e2e8f0;
}

.card-header h5 {
    margin: 0;
    font-size: 1rem;
    font-weight: 700;
    color: #1e293b;
}

.card-body {
    padding: 1.25rem;
}

/* Toggle Switch */
.toggle-switch-wrapper {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.toggle-switch {
    position: relative;
    display: inline-block;
    width: 60px;
    height: 34px;
}

.toggle-switch input {
    opacity: 0;
    width: 0;
    height: 0;
}

.toggle-slider {
    position: absolute;
    cursor: pointer;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: #cbd5e1;
    transition: 0.4s;
    border-radius: 34px;
}

.toggle-slider:before {
    position: absolute;
    content: "";
    height: 26px;
    width: 26px;
    left: 4px;
    bottom: 4px;
    background-color: white;
    transition: 0.4s;
    border-radius: 50%;
}

.toggle-switch input:checked + .toggle-slider {
    background-color: #10b981;
}

.toggle-switch input:checked + .toggle-slider:before {
    transform: translateX(26px);
}

.toggle-label {
    font-weight: 700;
    font-size: 0.9rem;
    color: #1e293b;
}

.badge-success {
    background: #10b981;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

.badge-secondary {
    background: #64748b;
    color: white;
    padding: 0.25rem 0.75rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
}

.pos-table th {
    background: #f8fafc;
    color: #64748b;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    padding: 0.75rem 1rem;
}

.pos-table td {
    padding: 0.75rem 1rem;
    vertical-align: middle;
    border-bottom: 1px solid #e2e8f0;
}

.pos-table code {
    background: #f1f5f9;
    padding: 0.2rem 0.5rem;
    border-radius: 4px;
    font-family: 'Consolas', monospace;
    font-size: 0.85rem;
    color: #475569;
}

.btn-sm {
    padding: 0.35rem 0.75rem;
    font-size: 0.8rem;
    border-radius: 6px;
}

.btn-danger {
    background: #ef4444;
    color: white;
    border: none;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-danger:hover {
    background: #dc2626;
}

.btn-primary {
    background: linear-gradient(135deg, #d4af37, #b8860b);
    color: white;
    border: none;
    padding: 0.6rem 1.25rem;
    border-radius: 8px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    display: inline-flex;
    align-items: center;
}

.btn-primary:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
}

.d-flex { display: flex; }
.align-items-center { align-items: center; }
.justify-content-between { justify-content: space-between; }
.mb-0 { margin-bottom: 0; }
.mt-3 { margin-top: 1rem; }
.me-1 { margin-right: 0.25rem; }
.me-2 { margin-right: 0.5rem; }
.text-muted { color: #64748b; }
</style>

<script>
function showAddSettingModal() {
    const key = prompt('Nhập khóa cài đặt (ví dụ: my_new_setting):');
    if (!key) return;
    
    const value = prompt('Nhập giá trị mặc định:');
    if (value === null) return;
    
    const description = prompt('Nhập mô tả (tùy chọn):') || '';
    
    // Create a hidden form and submit
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '<?= BASE_URL ?>/it/settings/update';
    
    const keyInput = document.createElement('input');
    keyInput.type = 'hidden';
    keyInput.name = 'setting_key';
    keyInput.value = key;
    
    const valueInput = document.createElement('input');
    valueInput.type = 'hidden';
    valueInput.name = 'setting_value';
    valueInput.value = value;
    
    const descInput = document.createElement('input');
    descInput.type = 'hidden';
    descInput.name = 'description';
    descInput.value = description;
    
    form.appendChild(keyInput);
    form.appendChild(valueInput);
    form.appendChild(descInput);
    document.body.appendChild(form);
    form.submit();
}

// Auto-update toggle label
document.querySelectorAll('.toggle-switch input').forEach(input => {
    input.addEventListener('change', function() {
        const label = this.closest('.toggle-switch-wrapper').querySelector('.toggle-label');
        label.textContent = this.checked ? 'BẬT' : 'TẮT';
    });
});
</script>