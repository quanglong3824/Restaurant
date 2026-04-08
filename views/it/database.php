<?php // views/it/database.php ?>

<?php
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);

// Thống kê số bản ghi các bảng có thể dọn
$db = getDB();
function tableCount(PDO $db, string $table, string $cond = ''): int {
    try {
        $sql = "SELECT COUNT(*) FROM `{$table}`" . ($cond ? " $cond" : '');
        return (int) $db->query($sql)->fetchColumn();
    } catch (\Throwable $e) { return 0; }
}
$stats = [
    'orders_closed'          => tableCount($db, 'orders', "WHERE status = 'closed'"),
    'orders_open'            => tableCount($db, 'orders', "WHERE status = 'open'"),
    'order_items'            => tableCount($db, 'order_items'),
    'order_notifications'    => tableCount($db, 'order_notifications'),
    'realtime_notifications' => tableCount($db, 'realtime_notifications'),
    'support_requests'       => tableCount($db, 'support_requests', "WHERE status = 'completed'"),
    'customer_sessions'      => tableCount($db, 'customer_sessions'),
    'table_status_history'   => tableCount($db, 'table_status_history'),
    'user_shifts_old'        => tableCount($db, 'user_shifts', "WHERE work_date < CURDATE()"),
];
$totalCleanable = $stats['orders_closed'] + $stats['order_items'] + $stats['order_notifications']
    + $stats['realtime_notifications'] + $stats['support_requests'] + $stats['customer_sessions']
    + $stats['table_status_history'] + $stats['user_shifts_old'];
?>

<?php if ($flash): ?>
<div class="db-flash <?= $flash['type']==='success'?'db-flash-success':'db-flash-error' ?>">
    <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?> db-flash-icon"></i>
    <span><?= e($flash['message']) ?></span>
</div>
<?php endif; ?>

<!-- ╔══════════════════════════════════════════════════════╗ -->
<!-- ║  BACKUP                                              ║ -->
<!-- ╚══════════════════════════════════════════════════════╝ -->
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-database"></i> Quản lý Cơ sở dữ liệu</h2>
        <a href="<?= BASE_URL ?>/it/database/backup" class="btn btn-gold">
            <i class="fas fa-plus"></i> Tạo bản sao lưu
        </a>
    </div>
    <div class="db-card-body">
        <div class="db-info-row">
            <div class="db-info-col">
                <h3 class="db-info-title"><i class="fas fa-database"></i> Sao lưu dữ liệu</h3>
                <p class="db-info-text">
                    Nhấn nút để tạo file <code>.sql</code> được lưu vào <code>/backups</code>.
                    Bạn có thể tải về hoặc xóa các bản cũ bên dưới.
                </p>
            </div>
            <div class="db-info-col-bordered">
                <h3 class="db-info-title-danger"><i class="fas fa-exclamation-triangle"></i> Phục hồi dữ liệu</h3>
                <p class="db-info-text">
                    <strong>Cảnh báo:</strong> Phục hồi sẽ ghi đè toàn bộ dữ liệu hiện tại.
                    Thực hiện thủ công qua phpMyAdmin với file đã tải về.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách backup -->
<div class="card db-card-spaced">
    <div class="card-header">
        <h2><i class="fas fa-history"></i> Danh sách bản sao lưu</h2>
        <span class="badge badge-gold"><?= count($backups) ?> bản lưu</span>
    </div>
    <div class="db-table-wrap">
        <table class="db-table">
            <thead>
                <tr>
                    <th>Tên file</th>
                    <th>Ngày tạo</th>
                    <th>Kích thước</th>
                    <th class="width-limit">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($backups)): ?>
                    <tr><td colspan="4" class="empty-cell">Chưa có bản sao lưu nào.</td></tr>
                <?php else: ?>
                    <?php foreach ($backups as $b): ?>
                        <tr>
                            <td><code class="chip"><?= e($b['name']) ?></code></td>
                            <td><?= date('d/m/Y H:i:s', strtotime($b['date'])) ?></td>
                            <td><?= round($b['size'] / 1024, 2) ?> KB</td>
                            <td>
                                <div style="display:flex;gap:.5rem;">
                                    <a href="<?= BASE_URL ?>/it/database/download?file=<?= urlencode($b['name']) ?>"
                                       class="btn btn-outline btn-sm" title="Tải về"><i class="fas fa-download"></i></a>
                                    <form method="POST" action="<?= BASE_URL ?>/it/database/delete"
                                          onsubmit="return confirm('Xóa bản sao lưu này?')">
                                        <input type="hidden" name="file" value="<?= e($b['name']) ?>">
                                        <button type="submit" class="btn btn-danger-outline btn-sm"><i class="fas fa-trash"></i></button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ╔══════════════════════════════════════════════════════╗ -->
<!-- ║  DỌN DẸP CSDL                                        ║ -->
<!-- ╚══════════════════════════════════════════════════════╝ -->
<div class="card db-cleanup-card">
    <div class="db-cleanup-header">
        <div>
            <h2 class="db-cleanup-title"><i class="fas fa-broom"></i> Dọn dẹp Cơ sở dữ liệu</h2>
            <p class="db-cleanup-subtitle">
                Có thể dọn <strong class="db-cleanup-count"><?= number_format($totalCleanable) ?> bản ghi</strong> — 
                Đang mở: <?= $stats['orders_open'] ?> đơn hàng (sẽ được giữ lại)
            </p>
        </div>
        <span class="db-it-badge">
            <i class="fas fa-shield-alt"></i> Chỉ IT
        </span>
    </div>

    <div class="db-cleanup-content">

        <!-- Cảnh báo chung -->
        <div class="db-warning-box">
            <i class="fas fa-exclamation-triangle db-warning-icon"></i>
            <span><strong>Lưu ý:</strong> Luôn tạo bản sao lưu trước khi dọn dẹp. Thao tác này <strong>không thể hoàn tác</strong>.</span>
        </div>

        <div class="db-cleanup-grid">

            <!-- ── CHẾ ĐỘ 1: TOÀN BỘ ──────────────────────────── -->
            <div class="db-mode-card">
                <div class="db-mode-header db-mode-header-red">
                    <div class="db-mode-title db-mode-title-red">
                        <i class="fas fa-fire db-mode-icon"></i>Chế độ 1 — Dọn toàn bộ
                    </div>
                    <div class="db-mode-desc">Xóa mọi giao dịch. Giữ lại: users, menu, bàn, ca, QR</div>
                </div>
                <div class="db-mode-body">
                    <div class="db-stats-row">
                        <?php
                        $mode1Items = [
                            ['order_notifications', $stats['order_notifications'], 'Thông báo đơn'],
                            ['realtime_notifications', $stats['realtime_notifications'], 'Thông báo RT'],
                            ['order_items', $stats['order_items'], 'Chi tiết món'],
                            ['orders', $stats['orders_closed'] + $stats['orders_open'], 'Đơn hàng'],
                            ['support_requests', tableCount($db,'support_requests'), 'Hỗ trợ'],
                            ['customer_sessions', $stats['customer_sessions'], 'Phiên KH'],
                            ['table_status_history', $stats['table_status_history'], 'Lịch sử bàn'],
                            ['user_shifts', tableCount($db,'user_shifts'), 'Phân công ca'],
                        ];
                        ?>
                        <?php foreach ($mode1Items as [$tbl, $cnt, $lbl]): ?>
                        <span class="db-stat-chip">
                            <?= $lbl ?>: <?= number_format($cnt) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <form method="POST" action="<?= BASE_URL ?>/it/database/cleanup/all"
                          onsubmit="return validateCleanup(this, 'XAC-NHAN-XOA-TOAN-BO')">
                        <input type="hidden" name="confirm_text" id="confirm-all" value="">
                        <button type="submit" class="db-cleanup-btn" id="btn-cleanup-all">
                            <i class="fas fa-fire"></i> Dọn toàn bộ giao dịch
                        </button>
                    </form>
                </div>
            </div>

            <!-- ── CHẾ ĐỘ 2: LỊCH SỬ ĐẶT BÀN ────────────────── -->
            <div class="db-mode-card db-mode-card-orange">
                <div class="db-mode-header db-mode-header-orange">
                    <div class="db-mode-title db-mode-title-orange">
                        <i class="fas fa-history db-mode-icon"></i>Chế độ 2 — Dọn lịch sử
                    </div>
                    <div class="db-mode-desc">Xóa đơn đã đóng + thông báo cũ. Giữ lại đơn đang mở</div>
                </div>
                <div class="db-mode-body">
                    <div class="db-stats-row">
                        <?php
                        $mode2Items = [
                            ['Đơn đã đóng', $stats['orders_closed'], '#ea580c'],
                            ['Đơn đang mở (giữ)', $stats['orders_open'], '#16a34a'],
                            ['Thông báo cũ', $stats['order_notifications'] + $stats['realtime_notifications'], '#ea580c'],
                            ['Phiên KH', $stats['customer_sessions'], '#ea580c'],
                            ['Lịch sử bàn >30d', $stats['table_status_history'], '#ea580c'],
                            ['Hỗ trợ đã xử lý', $stats['support_requests'], '#ea580c'],
                        ];
                        ?>
                        <?php foreach ($mode2Items as [$lbl, $cnt, $clr]): ?>
                        <span class="<?= $clr==='#16a34a'?'db-stat-chip-green':'db-stat-chip-orange' ?>" style="color: <?= $clr ?>">
                            <?= $lbl ?>: <?= number_format($cnt) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <form method="POST" action="<?= BASE_URL ?>/it/database/cleanup/orders"
                          onsubmit="return validateCleanup(this, 'XOA-LICH-SU')">
                        <input type="hidden" name="confirm_text" id="confirm-orders" value="">
                        <button type="submit" class="db-cleanup-btn db-cleanup-btn-orange">
                            <i class="fas fa-history"></i> Dọn lịch sử đặt bàn
                        </button>
                    </form>
                </div>
            </div>

        </div><!-- end grid 2 cols -->

        <!-- ── CHẾ ĐỘ 3: TỪNG BẢNG ────────────────────────────── -->
        <div class="db-mode-card db-mode-card-indigo">
            <div class="db-mode-header db-mode-header-indigo">
                <div class="db-mode-title db-mode-title-indigo">
                    <i class="fas fa-table db-mode-icon"></i>Chế độ 3 — Dọn từng bảng
                </div>
                <div class="db-mode-desc">Xóa dữ liệu theo bảng cụ thể, có ràng buộc an toàn</div>
            </div>
            <div class="db-mode-body">
                <div class="db-cleanup-table-wrap">
                    <table class="db-cleanup-table">
                        <thead>
                            <tr>
                                <th>Bảng</th>
                                <th>Điều kiện xóa</th>
                                <th class="text-right">Bản ghi</th>
                                <th class="text-center width-limit">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $tableDefs = [
                                ['orders',                "WHERE status = 'closed'",                        $stats['orders_closed'],          'Đơn hàng đã đóng',        'XOA-ORDERS'],
                                ['order_items',           "Theo đơn đã đóng",                               tableCount($db,'order_items','WHERE order_id IN (SELECT id FROM orders WHERE status="closed")'), 'Chi tiết món (đơn đóng)', 'XOA-ORDER-ITEMS'],
                                ['order_notifications',   "Toàn bộ",                                        $stats['order_notifications'],     'Thông báo đơn hàng',      'XOA-ORDER-NOTIFICATIONS'],
                                ['realtime_notifications',"Toàn bộ",                                        $stats['realtime_notifications'],   'Thông báo realtime',      'XOA-REALTIME-NOTIFICATIONS'],
                                ['support_requests',      "WHERE status = 'completed'",                     $stats['support_requests'],        'Yêu cầu hỗ trợ',          'XOA-SUPPORT-REQUESTS'],
                                ['customer_sessions',     "Toàn bộ",                                        $stats['customer_sessions'],        'Phiên khách (QR)',         'XOA-CUSTOMER-SESSIONS'],
                                ['table_status_history',  "Toàn bộ",                                        $stats['table_status_history'],    'Lịch sử trạng thái bàn',  'XOA-TABLE-STATUS-HISTORY'],
                                ['user_shifts',           "WHERE work_date < CURDATE()",                    $stats['user_shifts_old'],          'Phân công ca cũ',          'XOA-USER-SHIFTS'],
                            ];
                            ?>
                            <?php foreach ($tableDefs as [$tbl, $cond, $cnt, $label, $code]): ?>
                            <tr>
                                <td>
                                    <code><?= $tbl ?></code>
                                    <div class="table-label"><?= $label ?></div>
                                </td>
                                <td>
                                    <code class="cond-code"><?= e($cond) ?></code>
                                </td>
                                <td class="count-cell <?= $cnt > 0 ? 'has-data' : '' ?>">
                                    <span><?= number_format($cnt) ?></span>
                                </td>
                                <td class="text-center">
                                    <?php if ($cnt > 0): ?>
                                    <button type="button" class="btn btn-danger-outline btn-sm"
                                            onclick="openCleanupTableModal('<?= $tbl ?>', '<?= addslashes($label) ?>', <?= $cnt ?>, '<?= $code ?>')"
                                            title="Dọn bảng này">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <?php else: ?>
                                    <span class="empty-text">Trống</span>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ╔══════════════════════════════════════════════════════╗ -->
<!-- ║  THÔNG TIN HỆ THỐNG                                  ║ -->
<!-- ╚══════════════════════════════════════════════════════╝ -->
<div class="card db-card-spaced">
    <div class="card-header">
        <h2><i class="fas fa-info-circle"></i> Thông tin hệ thống</h2>
    </div>
    <div class="db-table-wrap">
        <table class="db-table db-info-table">
            <tbody>
                <tr>
                    <td>Database Name</td>
                    <td><code><?= e(DB_NAME) ?></code></td>
                </tr>
                <tr>
                    <td>Host</td>
                    <td><code><?= e(DB_HOST) ?></code></td>
                </tr>
                <tr>
                    <td>Đường dẫn sao lưu</td>
                    <td><code>/backups/</code></td>
                </tr>
                <tr>
                    <td>Kích thước Database</td>
                    <td>
                        <?php
                        $q = $db->query("SELECT SUM(data_length + index_length) / 1024 / 1024 AS size
                                         FROM information_schema.TABLES WHERE table_schema = '" . DB_NAME . "'");
                        $sizeMB = round($q->fetch()['size'], 2);
                        $color = $sizeMB > 100 ? '#dc2626' : ($sizeMB > 50 ? '#d97706' : '#16a34a');
                        ?>
                        <span style="color:<?= $color ?>;font-weight:700;"><?= $sizeMB ?> MB</span>
                    </td>
                </tr>
                <tr>
                    <td>PHP Version</td>
                    <td><code><?= phpversion() ?></code></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════ Modal xác nhận dọn từng bảng ══════ -->
<div class="modal-backdrop db-modal" id="modalCleanupTable">
    <div class="modal db-modal-content">
        <div class="db-modal-header">
            <h3 class="db-modal-title"><i class="fas fa-trash-alt"></i> Xác nhận dọn bảng</h3>
            <button class="modal-close" type="button" onclick="closeCleanupModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="db-modal-body">
            <div id="cleanup-table-info" class="db-modal-info"></div>
            <form method="POST" id="form-cleanup-table" action="">
                <input type="hidden" name="table_name" id="input-table-name" value="">
                <div class="form-group">
                    <label class="form-label" id="label-confirm-code">
                        Gõ mã xác nhận để tiếp tục:
                    </label>
                    <input type="text" name="confirm_text" id="input-confirm-table"
                           class="form-control db-modal-input" placeholder="" autocomplete="off">
                </div>
                <button type="submit" class="db-modal-submit">
                    <i class="fas fa-trash"></i> Xác nhận dọn
                </button>
            </form>
        </div>
    </div>
</div>

<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/it/database.css">

<script>
// ── Modal dọn từng bảng ─────────────────────────────────────────────
function openCleanupTableModal(tableName, label, count, code) {
    document.getElementById('input-table-name').value = tableName;
    document.getElementById('form-cleanup-table').action = '<?= BASE_URL ?>/it/database/cleanup/table';
    document.getElementById('input-confirm-table').value = '';
    document.getElementById('input-confirm-table').placeholder = code;
    document.getElementById('label-confirm-code').innerHTML =
        `Gõ <code style="color:#dc2626;font-weight:700;">${code}</code> để xác nhận:`;
    document.getElementById('cleanup-table-info').innerHTML =
        `<strong>Bảng:</strong> <code>${tableName}</code> — <strong>${label}</strong><br>
         <strong>Sẽ xóa:</strong> <span style="color:#dc2626;font-weight:700;">${count.toLocaleString()}</span> bản ghi.
         <br><span style="color:#92400e;font-size:.82rem;">⚠️ Không thể hoàn tác sau khi xác nhận.</span>`;
    const modal = document.getElementById('modalCleanupTable');
    modal.style.display = 'flex';
    setTimeout(() => document.getElementById('input-confirm-table').focus(), 100);
}
function closeCleanupModal() {
    document.getElementById('modalCleanupTable').style.display = 'none';
}
document.getElementById('modalCleanupTable').addEventListener('click', function(e) {
    if (e.target === this) closeCleanupModal();
});

// ── Validate + prompt cho chế độ 1 & 2 ────────────────────────────
function validateCleanup(form, requiredCode) {
    const msg = `⚠️ THAO TÁC NGUY HIỂM!\n\nĐể xác nhận, gõ chính xác:\n${requiredCode}`;
    const userInput = prompt(msg);
    if (userInput === null) return false; // cancel
    if (userInput.trim() !== requiredCode) {
        alert('❌ Mã xác nhận không đúng. Hủy thao tác.');
        return false;
    }
    // Set hidden field
    const hiddenField = form.querySelector('input[name="confirm_text"]');
    hiddenField.value = userInput.trim();
    return true;
}
</script>