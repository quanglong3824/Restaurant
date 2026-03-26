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
<div style="margin-bottom:1.25rem;padding:.9rem 1.25rem;border-radius:10px;font-size:.92rem;display:flex;align-items:flex-start;gap:.75rem;
    background:<?= $flash['type']==='success'?'rgba(34,197,94,.1)':'rgba(239,68,68,.1)' ?>;
    border:1px solid <?= $flash['type']==='success'?'rgba(34,197,94,.3)':'rgba(239,68,68,.3)' ?>;
    color:<?= $flash['type']==='success'?'#16a34a':'#dc2626' ?>;">
    <i class="fas fa-<?= $flash['type']==='success'?'check-circle':'exclamation-circle' ?>" style="margin-top:.1rem;flex-shrink:0;"></i>
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
    <div class="card-body" style="padding:2rem;">
        <div class="row" style="display:flex;gap:2rem;flex-wrap:wrap;">
            <div style="flex:1;min-width:280px;">
                <h3 style="margin-bottom:1rem;color:var(--gold);">Sao lưu dữ liệu</h3>
                <p style="color:var(--text-muted);line-height:1.65;font-size:.9rem;">
                    Nhấn nút để tạo file <code>.sql</code> được lưu vào <code>/backups</code>.
                    Bạn có thể tải về hoặc xóa các bản cũ bên dưới.
                </p>
            </div>
            <div style="flex:1;min-width:280px;border-left:1px solid var(--border-color);padding-left:2rem;">
                <h3 style="margin-bottom:1rem;color:var(--danger, #ef4444);">Phục hồi dữ liệu</h3>
                <p style="color:var(--text-muted);line-height:1.65;font-size:.9rem;">
                    <strong>Cảnh báo:</strong> Phục hồi sẽ ghi đè toàn bộ dữ liệu hiện tại.
                    Thực hiện thủ công qua phpMyAdmin với file đã tải về.
                </p>
            </div>
        </div>
    </div>
</div>

<!-- Danh sách backup -->
<div class="card" style="margin-top:1.5rem;">
    <div class="card-header">
        <h2><i class="fas fa-history"></i> Danh sách bản sao lưu</h2>
        <span class="badge badge-gold"><?= count($backups) ?> bản lưu</span>
    </div>
    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Tên file</th>
                    <th>Ngày tạo</th>
                    <th>Kích thước</th>
                    <th style="width:120px;">Hành động</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($backups)): ?>
                    <tr><td colspan="4" style="text-align:center;padding:2rem;color:var(--text-muted);">Chưa có bản sao lưu nào.</td></tr>
                <?php else: ?>
                    <?php foreach ($backups as $b): ?>
                        <tr>
                            <td><code class="chip" style="font-size:.8rem;"><?= e($b['name']) ?></code></td>
                            <td><?= date('d/m/Y H:i:s', strtotime($b['date'])) ?></td>
                            <td><?= round($b['size'] / 1024, 2) ?> KB</td>
                            <td>
                                <div style="display:flex;gap:.5rem;">
                                    <a href="<?= BASE_URL ?>/it/database/download?file=<?= urlencode($b['name']) ?>"
                                       class="btn btn-outline btn-sm" title="Tải về"><i class="fas fa-download"></i></a>
                                    <form method="POST" action="<?= BASE_URL ?>/it/database/delete" style="display:inline;"
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
<div class="card" style="margin-top:1.5rem;border:1px solid rgba(239,68,68,.25);">
    <div class="card-header" style="background:rgba(239,68,68,.05);">
        <div>
            <h2 style="color:#dc2626;"><i class="fas fa-broom"></i> Dọn dẹp Cơ sở dữ liệu</h2>
            <p style="font-size:.82rem;color:var(--text-muted);margin-top:.2rem;">
                Có thể dọn <strong style="color:#dc2626;"><?= number_format($totalCleanable) ?> bản ghi</strong> — 
                Đang mở: <?= $stats['orders_open'] ?> đơn hàng (sẽ được giữ lại)
            </p>
        </div>
        <span style="background:rgba(239,68,68,.12);color:#dc2626;padding:.3rem .75rem;border-radius:8px;font-size:.78rem;font-weight:600;">
            <i class="fas fa-shield-alt"></i> Chỉ IT
        </span>
    </div>

    <div style="padding:1.5rem;display:flex;flex-direction:column;gap:1.25rem;">

        <!-- Cảnh báo chung -->
        <div style="background:rgba(250,204,21,.08);border:1px solid rgba(250,204,21,.3);border-radius:10px;padding:1rem 1.25rem;display:flex;gap:.75rem;font-size:.87rem;color:#92400e;">
            <i class="fas fa-exclamation-triangle" style="margin-top:.1rem;flex-shrink:0;color:#d97706;"></i>
            <span><strong>Lưu ý:</strong> Luôn tạo bản sao lưu trước khi dọn dẹp. Thao tác này <strong>không thể hoàn tác</strong>.</span>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(300px,1fr));gap:1.25rem;">

            <!-- ── CHẾ ĐỘ 1: TOÀN BỘ ──────────────────────────── -->
            <div style="border:1px solid rgba(239,68,68,.3);border-radius:12px;overflow:hidden;">
                <div style="background:rgba(239,68,68,.08);padding:1rem 1.25rem;border-bottom:1px solid rgba(239,68,68,.2);">
                    <div style="font-weight:700;color:#dc2626;font-size:.95rem;margin-bottom:.25rem;">
                        <i class="fas fa-fire" style="margin-right:.4rem;"></i>Chế độ 1 — Dọn toàn bộ
                    </div>
                    <div style="font-size:.8rem;color:var(--text-muted);">Xóa mọi giao dịch. Giữ lại: users, menu, bàn, ca, QR</div>
                </div>
                <div style="padding:1rem 1.25rem;">
                    <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1rem;">
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
                        <span style="background:rgba(239,68,68,.08);border:1px solid rgba(239,68,68,.2);border-radius:6px;padding:.15rem .5rem;font-size:.75rem;color:#dc2626;">
                            <?= $lbl ?>: <?= number_format($cnt) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <form method="POST" action="<?= BASE_URL ?>/it/database/cleanup/all"
                          onsubmit="return validateCleanup(this, 'XAC-NHAN-XOA-TOAN-BO')">
                        <input type="hidden" name="confirm_text" id="confirm-all" value="">
                        <button type="submit" class="btn btn-block" id="btn-cleanup-all"
                                style="background:#dc2626;color:#fff;border:none;padding:.65rem;border-radius:9px;cursor:pointer;font-weight:600;font-size:.88rem;">
                            <i class="fas fa-fire"></i> Dọn toàn bộ giao dịch
                        </button>
                    </form>
                </div>
            </div>

            <!-- ── CHẾ ĐỘ 2: LỊCH SỬ ĐẶT BÀN ────────────────── -->
            <div style="border:1px solid rgba(249,115,22,.3);border-radius:12px;overflow:hidden;">
                <div style="background:rgba(249,115,22,.07);padding:1rem 1.25rem;border-bottom:1px solid rgba(249,115,22,.2);">
                    <div style="font-weight:700;color:#ea580c;font-size:.95rem;margin-bottom:.25rem;">
                        <i class="fas fa-history" style="margin-right:.4rem;"></i>Chế độ 2 — Dọn lịch sử
                    </div>
                    <div style="font-size:.8rem;color:var(--text-muted);">Xóa đơn đã đóng + thông báo cũ. Giữ lại đơn đang mở</div>
                </div>
                <div style="padding:1rem 1.25rem;">
                    <div style="display:flex;flex-wrap:wrap;gap:.4rem;margin-bottom:1rem;">
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
                        <span style="background:<?= $clr==='#16a34a'?'rgba(34,197,94,.08)':'rgba(249,115,22,.08)' ?>;border:1px solid <?= $clr==='#16a34a'?'rgba(34,197,94,.25)':'rgba(249,115,22,.25)' ?>;border-radius:6px;padding:.15rem .5rem;font-size:.75rem;color:<?= $clr ?>;">
                            <?= $lbl ?>: <?= number_format($cnt) ?>
                        </span>
                        <?php endforeach; ?>
                    </div>
                    <form method="POST" action="<?= BASE_URL ?>/it/database/cleanup/orders"
                          onsubmit="return validateCleanup(this, 'XOA-LICH-SU')">
                        <input type="hidden" name="confirm_text" id="confirm-orders" value="">
                        <button type="submit" class="btn btn-block"
                                style="background:#ea580c;color:#fff;border:none;padding:.65rem;border-radius:9px;cursor:pointer;font-weight:600;font-size:.88rem;">
                            <i class="fas fa-history"></i> Dọn lịch sử đặt bàn
                        </button>
                    </form>
                </div>
            </div>

        </div><!-- end grid 2 cols -->

        <!-- ── CHẾ ĐỘ 3: TỪNG BẢNG ────────────────────────────── -->
        <div style="border:1px solid var(--border-color);border-radius:12px;overflow:hidden;">
            <div style="background:rgba(99,102,241,.06);padding:1rem 1.25rem;border-bottom:1px solid var(--border-color);">
                <div style="font-weight:700;color:#4f46e5;font-size:.95rem;margin-bottom:.25rem;">
                    <i class="fas fa-table" style="margin-right:.4rem;"></i>Chế độ 3 — Dọn từng bảng
                </div>
                <div style="font-size:.8rem;color:var(--text-muted);">Xóa dữ liệu theo bảng cụ thể, có ràng buộc an toàn</div>
            </div>
            <div style="padding:1.25rem;">
                <div style="overflow-x:auto;">
                    <table style="width:100%;border-collapse:collapse;font-size:.87rem;">
                        <thead>
                            <tr style="background:rgba(0,0,0,.04);">
                                <th style="padding:.6rem .8rem;text-align:left;color:var(--text-muted);font-weight:600;">Bảng</th>
                                <th style="padding:.6rem .8rem;text-align:left;color:var(--text-muted);font-weight:600;">Điều kiện xóa</th>
                                <th style="padding:.6rem .8rem;text-align:right;color:var(--text-muted);font-weight:600;">Bản ghi</th>
                                <th style="padding:.6rem .8rem;text-align:center;color:var(--text-muted);font-weight:600;width:100px;">Thao tác</th>
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
                            <tr style="border-bottom:1px solid var(--border-color);">
                                <td style="padding:.65rem .8rem;">
                                    <code style="font-size:.78rem;background:rgba(99,102,241,.08);color:#4f46e5;padding:.15rem .4rem;border-radius:5px;"><?= $tbl ?></code>
                                    <div style="font-size:.75rem;color:var(--text-muted);margin-top:.2rem;"><?= $label ?></div>
                                </td>
                                <td style="padding:.65rem .8rem;font-size:.78rem;color:var(--text-muted);">
                                    <code style="font-size:.75rem;"><?= e($cond) ?></code>
                                </td>
                                <td style="padding:.65rem .8rem;text-align:right;">
                                    <span style="font-weight:700;color:<?= $cnt > 0 ? '#dc2626' : 'var(--text-muted)' ?>;">
                                        <?= number_format($cnt) ?>
                                    </span>
                                </td>
                                <td style="padding:.65rem .8rem;text-align:center;">
                                    <?php if ($cnt > 0): ?>
                                    <button type="button" class="btn btn-danger-outline btn-sm"
                                            onclick="openCleanupTableModal('<?= $tbl ?>', '<?= addslashes($label) ?>', <?= $cnt ?>, '<?= $code ?>')"
                                            title="Dọn bảng này">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                    <?php else: ?>
                                    <span style="color:var(--text-muted);font-size:.75rem;">Trống</span>
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
<div class="card" style="margin-top:1.5rem;">
    <div class="card-header">
        <h2><i class="fas fa-info-circle"></i> Thông tin hệ thống</h2>
    </div>
    <div class="table-wrap">
        <table>
            <tbody>
                <tr>
                    <td style="font-weight:600;width:220px;">Database Name</td>
                    <td><code><?= e(DB_NAME) ?></code></td>
                </tr>
                <tr>
                    <td style="font-weight:600;">Host</td>
                    <td><code><?= e(DB_HOST) ?></code></td>
                </tr>
                <tr>
                    <td style="font-weight:600;">Đường dẫn sao lưu</td>
                    <td><code>/backups/</code></td>
                </tr>
                <tr>
                    <td style="font-weight:600;">Kích thước Database</td>
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
                    <td style="font-weight:600;">PHP Version</td>
                    <td><code><?= phpversion() ?></code></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<!-- ══════ Modal xác nhận dọn từng bảng ══════ -->
<div class="modal-backdrop" id="modalCleanupTable" style="display:none;">
    <div class="modal" style="max-width:440px;">
        <div class="modal-header" style="background:rgba(239,68,68,.06);">
            <h3 style="color:#dc2626;"><i class="fas fa-trash-alt"></i> Xác nhận dọn bảng</h3>
            <button class="modal-close" type="button" onclick="closeCleanupModal()"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body" style="display:flex;flex-direction:column;gap:1rem;">
            <div id="cleanup-table-info" style="background:rgba(239,68,68,.07);border:1px solid rgba(239,68,68,.2);border-radius:10px;padding:.85rem 1rem;font-size:.88rem;"></div>
            <form method="POST" id="form-cleanup-table" action="">
                <input type="hidden" name="table_name" id="input-table-name" value="">
                <div class="form-group">
                    <label class="form-label" id="label-confirm-code">
                        Gõ mã xác nhận để tiếp tục:
                    </label>
                    <input type="text" name="confirm_text" id="input-confirm-table"
                           class="form-control" placeholder="" autocomplete="off"
                           style="font-family:monospace;letter-spacing:.05em;">
                </div>
                <button type="submit" class="btn btn-block"
                        style="background:#dc2626;color:#fff;border:none;padding:.7rem;border-radius:9px;font-weight:700;cursor:pointer;margin-top:.5rem;">
                    <i class="fas fa-trash"></i> Xác nhận dọn
                </button>
            </form>
        </div>
    </div>
</div>

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
