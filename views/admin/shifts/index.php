<?php // views/admin/shifts/index.php ?>

<?php
// Flash message
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);
?>

<?php if ($flash): ?>
<div class="shift-flash shift-flash-<?= $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'warning' ? 'warning' : 'danger') ?>">
    <i class="fas fa-<?= $flash['type']==='success'?'check-circle':($flash['type']==='warning'?'exclamation-triangle':'times-circle') ?>"></i>
    <?= e($flash['message']) ?>
</div>
<?php endif; ?>

<div class="content-with-aside content-with-aside--sm">

    <!-- ═══════════════════════════════ MAIN COLUMN ═══════ -->
    <div class="flex-col-gap">

        <!-- ── Phân công hôm nay ─────────────────────────── -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2><i class="fas fa-calendar-check"></i> Phân công hôm nay</h2>
                    <p class="card-header-date">
                        <i class="fas fa-calendar-alt"></i>
                        <?= date('l, d/m/Y', strtotime($today)) ?>
                    </p>
                </div>
                <button class="btn btn-gold" onclick="Aurora.openModal('modalAssign')" id="btn-open-assign">
                    <i class="fas fa-user-plus"></i> Gán nhân viên
                </button>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th><i class="fas fa-clock"></i>Ca trực</th>
                            <th><i class="fas fa-user"></i>Nhân viên</th>
                            <th class="th-action-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assignments)): ?>
                        <tr>
                            <td colspan="3" class="empty-state">
                                <i class="fas fa-user-slash empty-state-icon"></i>
                                Chưa có nhân viên nào được phân công hôm nay.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php
                            // Group theo ca
                            $grouped = [];
                            foreach ($assignments as $a) {
                                $grouped[$a['shift_name']][] = $a;
                            }
                            ?>
                            <?php foreach ($grouped as $shiftName => $members): ?>
                            <?php $first = $members[0]; ?>
                            <tr class="shift-row">
                                <td rowspan="<?= count($members) ?>">
                                    <div class="shift-name"><?= e($shiftName) ?></div>
                                    <div class="shift-time">
                                        <i class="fas fa-clock"></i>
                                        <?= date('H:i', strtotime($first['start_time'])) ?> – <?= date('H:i', strtotime($first['end_time'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $first['user_role'] === 'admin' ? 'gold' : 'blue' ?>">
                                        <?= e(roleLabel($first['user_role'])) ?>
                                    </span>
                                    <?= e($first['user_name']) ?>
                                </td>
                                <td class="td-center">
                                    <form method="POST" action="<?= BASE_URL ?>/admin/shifts/remove_assign" onsubmit="return confirm('Hủy phân công <?= e($first['user_name']) ?> khỏi ca này?');">
                                        <input type="hidden" name="id" value="<?= $first['id'] ?>">
                                        <button type="submit" class="btn btn-danger-outline btn-sm" title="Hủy phân công"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php for ($i = 1; $i < count($members); $i++): $m = $members[$i]; ?>
                            <tr>
                                <td>
                                    <span class="badge badge-<?= $m['user_role'] === 'admin' ? 'gold' : 'blue' ?>">
                                        <?= e(roleLabel($m['user_role'])) ?>
                                    </span>
                                    <?= e($m['user_name']) ?>
                                </td>
                                <td class="td-center">
                                    <form method="POST" action="<?= BASE_URL ?>/admin/shifts/remove_assign" onsubmit="return confirm('Hủy phân công <?= e($m['user_name']) ?> khỏi ca này?');">
                                        <input type="hidden" name="id" value="<?= $m['id'] ?>">
                                        <button type="submit" class="btn btn-danger-outline btn-sm" title="Hủy phân công"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endfor; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Tổng kết -->
            <?php if (!empty($assignments)): ?>
            <div class="summary-bar">
                <span><i class="fas fa-users"></i><?= count($assignments) ?> nhân viên</span>
                <span><i class="fas fa-layer-group"></i><?= count($grouped) ?> ca</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- ── Cấu hình ca trực ──────────────────────────── -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> Cấu hình Ca trực</h2>
                <span class="card-subtitle"><?= count($shifts) ?> ca đang hoạt động</span>
            </div>
            
            <!-- Pagination for shifts -->
            <?php if (isset($pagination) && $pagination['totalPages'] > 1): ?>
            <div class="pagination-container">
                <?= renderPagination($pagination['page'], $pagination['totalPages'], BASE_URL . '/admin/shifts', []) ?>
            </div>
            <?php endif; ?>
            
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tên ca</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Thời lượng</th>
                            <th class="th-action-center">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($shifts)): ?>
                        <tr>
                            <td colspan="5" class="empty-state">
                                <i class="fas fa-clock empty-state-icon"></i>
                                Chưa cấu hình ca trực nào.
                            </td>
                        </tr>
                        <?php else: ?>
                            <?php foreach ($shifts as $s): ?>
                            <?php
                                $start = strtotime($s['start_time']);
                                $end   = strtotime($s['end_time']);
                                $diff  = $end - $start;
                                if ($diff < 0) $diff += 86400; // qua đêm
                                $hours = floor($diff / 3600);
                                $mins  = floor(($diff % 3600) / 60);
                                $duration = $hours . 'h' . ($mins > 0 ? $mins . 'm' : '');
                            ?>
                            <tr>
                                <td>
                                    <strong class="text-primary"><?= e($s['name']) ?></strong>
                                </td>
                                <td>
                                    <span class="tabular-nums"><?= date('H:i', $start) ?></span>
                                </td>
                                <td>
                                    <span class="tabular-nums"><?= date('H:i', $end) ?></span>
                                </td>
                                <td>
                                    <span class="duration-badge">
                                        <?= $duration ?>
                                    </span>
                                </td>
                                <td class="td-center">
                                    <form method="POST" action="<?= BASE_URL ?>/admin/shifts/delete" onsubmit="return confirm('Xóa ca «<?= e($s['name']) ?>»?\nTất cả phân công liên quan cũng sẽ bị xóa.');">
                                        <input type="hidden" name="id" value="<?= $s['id'] ?>">
                                        <button type="submit" class="btn btn-danger-outline btn-sm" title="Xóa ca này"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Assignment Pagination Bottom -->
        <?php if (isset($assignmentPagination) && $assignmentPagination['totalPages'] > 1): ?>
        <div class="pagination-margin">
            <?= renderPagination($assignmentPagination['page'], $assignmentPagination['totalPages'], BASE_URL . '/admin/shifts', ['assign_page' => '']) ?>
        </div>
        <?php endif; ?>

        <!-- ── Lịch sử phân công 7 ngày ─────────────────── -->
        <?php if (!empty($recentHistory)): ?>
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-history"></i> Lịch sử phân công (7 ngày gần nhất)</h2>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Số lượng</th>
                            <th>Nhân viên</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentHistory as $h): ?>
                        <tr>
                            <td>
                                <strong><?= date('d/m/Y', strtotime($h['work_date'])) ?></strong>
                                <?php if ($h['work_date'] === $today): ?>
                                <span class="today-badge">Hôm nay</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $h['total_assignments'] ?> người</td>
                            <td class="text-muted small"><?= e($h['staff_names']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php endif; ?>

    </div><!-- end main column -->

    <!-- ═══════════════════════════════ SIDEBAR ══════════ -->
    <aside class="sticky-aside">
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-plus-circle"></i> Thêm Ca Mới</h2>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/shifts/store" id="form-add-shift">
                <div class="card-body-flex">
                    <div class="form-group">
                        <label class="form-label">Tên Ca <span class="text-danger">*</span></label>
                        <input type="text" name="name" id="shift-name" class="form-control"
                               placeholder="VD: Ca Sáng, Ca Tối..." required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giờ bắt đầu <span class="text-danger">*</span></label>
                        <input type="time" name="start_time" id="shift-start" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giờ kết thúc <span class="text-danger">*</span></label>
                        <input type="time" name="end_time" id="shift-end" class="form-control" required>
                    </div>
                    <!-- Preview thời lượng -->
                    <div id="shift-preview" class="shift-preview">
                        <i class="fas fa-info-circle"></i>
                        Thời lượng: <strong id="shift-duration" class="text-primary"></strong>
                    </div>
                    <button type="submit" class="btn btn-gold btn-block" id="btn-save-shift">
                        <i class="fas fa-save"></i> Lưu ca trực
                    </button>
                </div>
            </form>
        </div>

        <!-- Gợi ý ca phổ biến -->
        <div class="card card-mt">
            <div class="card-header">
                <h2 class="card-title-sm"><i class="fas fa-lightbulb"></i> Ca phổ biến</h2>
            </div>
            <div class="card-body-padding">
                <?php
                $presets = [
                    ['Ca Sáng',  '06:00', '14:00'],
                    ['Ca Chiều', '14:00', '22:00'],
                    ['Ca Tối',   '18:00', '24:00'],
                    ['Ca Đêm',   '22:00', '06:00'],
                ];
                ?>
                <?php foreach ($presets as $p): ?>
                <button type="button" class="btn-preset-shift" onclick="applyPreset('<?= $p[0] ?>', '<?= $p[1] ?>', '<?= $p[2] ?>')">
                    <span class="preset-name"><?= $p[0] ?></span>
                    <span class="preset-time"><?= $p[1] ?> – <?= $p[2] ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>

</div><!-- end content-with-aside -->

<!-- ══════════════════════ Modal Gán Nhân Viên ═══════════════════════ -->
<div class="modal-backdrop" id="modalAssign">
    <div class="modal">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus"></i>Gán nhân viên vào ca</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/admin/shifts/assign" class="modal-body">
            <div class="form-group">
                <label class="form-label">Ngày làm việc</label>
                <input type="date" name="work_date" class="form-control"
                       value="<?= $today ?>"
                       min="<?= date('Y-m-d', strtotime('-30 days')) ?>"
                       max="<?= date('Y-m-d', strtotime('+30 days')) ?>"
                       required id="assign-date">
            </div>
            <div class="form-group">
                <label class="form-label">Chọn ca trực</label>
                <select name="shift_id" class="form-control" required id="assign-shift">
                    <?php if (empty($shifts)): ?>
                        <option value="" disabled selected>— Chưa có ca nào —</option>
                    <?php else: ?>
                        <?php foreach ($shifts as $s): ?>
                            <option value="<?= $s['id'] ?>">
                                <?= e($s['name']) ?> (<?= date('H:i', strtotime($s['start_time'])) ?> – <?= date('H:i', strtotime($s['end_time'])) ?>)
                            </option>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">Chọn nhân viên</label>
                <select name="user_id" class="form-control" required id="assign-user">
                    <?php foreach ($users as $u): ?>
                        <?php if ($u['is_active']): ?>
                        <option value="<?= $u['id'] ?>">
                            <?= e($u['name']) ?> — <?= e(roleLabel($u['role'])) ?>
                        </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold btn-block btn-lg"
                    <?= empty($shifts) ? 'disabled' : '' ?>>
                <i class="fas fa-check"></i> Xác nhận phân công
            </button>
        </form>
    </div>
</div>

<script>
// ── Preview thời lượng ca ───────────────────────────────────────────
function updateShiftPreview() {
    const s = document.getElementById('shift-start').value;
    const e = document.getElementById('shift-end').value;
    const preview = document.getElementById('shift-preview');
    const dur = document.getElementById('shift-duration');
    if (!s || !e) { preview.style.display = 'none'; return; }

    let [sh, sm] = s.split(':').map(Number);
    let [eh, em] = e.split(':').map(Number);
    let diff = (eh * 60 + em) - (sh * 60 + sm);
    if (diff <= 0) diff += 24 * 60; // qua đêm

    const h = Math.floor(diff / 60);
    const m = diff % 60;
    dur.textContent = (h > 0 ? h + ' giờ ' : '') + (m > 0 ? m + ' phút' : '');
    preview.style.display = 'block';
}
document.getElementById('shift-start').addEventListener('change', updateShiftPreview);
document.getElementById('shift-end').addEventListener('change', updateShiftPreview);

// ── Áp dụng preset ca ──────────────────────────────────────────────
function applyPreset(name, start, end) {
    document.getElementById('shift-name').value = name;
    document.getElementById('shift-start').value = start;
    document.getElementById('shift-end').value = end;
    updateShiftPreview();
    // Highlight form
    document.getElementById('form-add-shift').style.transition = 'box-shadow .3s';
    document.getElementById('form-add-shift').style.boxShadow = '0 0 0 2px rgba(212,175,55,.4)';
    setTimeout(() => {
        document.getElementById('form-add-shift').style.boxShadow = '';
    }, 800);
}

// ── Hover effect preset buttons ────────────────────────────────────
document.querySelectorAll('.btn-preset-shift').forEach(btn => {
    btn.addEventListener('mouseenter', () => {
        btn.style.background = 'rgba(212,175,55,.08)';
        btn.style.borderColor = 'rgba(212,175,55,.35)';
    });
    btn.addEventListener('mouseleave', () => {
        btn.style.background = 'transparent';
        btn.style.borderColor = 'var(--border-color)';
    });
});
</script>