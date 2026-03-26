<?php // views/admin/shifts/index.php ?>

<?php
// Flash message
$flash = $_SESSION['flash'] ?? null;
if ($flash) unset($_SESSION['flash']);
?>

<?php if ($flash): ?>
<div class="alert alert-<?= $flash['type'] === 'success' ? 'success' : ($flash['type'] === 'warning' ? 'warning' : 'danger') ?>" style="margin-bottom:1rem;padding:.85rem 1.2rem;border-radius:10px;font-size:.93rem;display:flex;align-items:center;gap:.7rem;background:<?= $flash['type']==='success'?'rgba(34,197,94,.12)':($flash['type']==='warning'?'rgba(250,204,21,.12)':'rgba(239,68,68,.12)') ?>;border:1px solid <?= $flash['type']==='success'?'rgba(34,197,94,.35)':($flash['type']==='warning'?'rgba(250,204,21,.35)':'rgba(239,68,68,.35)') ?>;color:<?= $flash['type']==='success'?'#16a34a':($flash['type']==='warning'?'#a16207':'#dc2626') ?>;">
    <i class="fas fa-<?= $flash['type']==='success'?'check-circle':($flash['type']==='warning'?'exclamation-triangle':'times-circle') ?>"></i>
    <?= e($flash['message']) ?>
</div>
<?php endif; ?>

<div class="content-with-aside content-with-aside--sm">

    <!-- ═══════════════════════════════ MAIN COLUMN ═══════ -->
    <div style="display:flex;flex-direction:column;gap:1.25rem;">

        <!-- ── Phân công hôm nay ─────────────────────────── -->
        <div class="card">
            <div class="card-header">
                <div>
                    <h2><i class="fas fa-calendar-check"></i> Phân công hôm nay</h2>
                    <p style="font-size:.82rem;color:var(--text-muted);margin-top:.15rem;">
                        <i class="fas fa-calendar-alt" style="margin-right:.35rem;"></i>
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
                            <th><i class="fas fa-clock" style="margin-right:.4rem;opacity:.6;"></i>Ca trực</th>
                            <th><i class="fas fa-user" style="margin-right:.4rem;opacity:.6;"></i>Nhân viên</th>
                            <th style="width:80px;text-align:center;">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($assignments)): ?>
                        <tr>
                            <td colspan="3" style="text-align:center;padding:2.5rem 1rem;color:var(--text-muted);">
                                <i class="fas fa-user-slash" style="font-size:1.8rem;opacity:.3;display:block;margin-bottom:.6rem;"></i>
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
                            <tr style="background:rgba(var(--gold-rgb,212,175,55),.04);">
                                <td rowspan="<?= count($members) ?>">
                                    <div style="font-weight:600;color:var(--text-primary);"><?= e($shiftName) ?></div>
                                    <div style="font-size:.78rem;color:var(--text-muted);margin-top:.2rem;">
                                        <i class="fas fa-clock"></i>
                                        <?= date('H:i', strtotime($first['start_time'])) ?> – <?= date('H:i', strtotime($first['end_time'])) ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-<?= $first['user_role'] === 'admin' ? 'gold' : 'blue' ?>" style="font-size:.75rem;padding:.2rem .55rem;border-radius:6px;">
                                        <?= e(roleLabel($first['user_role'])) ?>
                                    </span>
                                    <?= e($first['user_name']) ?>
                                </td>
                                <td style="text-align:center;">
                                    <form method="POST" action="<?= BASE_URL ?>/admin/shifts/remove_assign" onsubmit="return confirm('Hủy phân công <?= e($first['user_name']) ?> khỏi ca này?');">
                                        <input type="hidden" name="id" value="<?= $first['id'] ?>">
                                        <button type="submit" class="btn btn-danger-outline btn-sm" title="Hủy phân công"><i class="fas fa-trash"></i></button>
                                    </form>
                                </td>
                            </tr>
                            <?php for ($i = 1; $i < count($members); $i++): $m = $members[$i]; ?>
                            <tr>
                                <td>
                                    <span class="badge badge-<?= $m['user_role'] === 'admin' ? 'gold' : 'blue' ?>" style="font-size:.75rem;padding:.2rem .55rem;border-radius:6px;">
                                        <?= e(roleLabel($m['user_role'])) ?>
                                    </span>
                                    <?= e($m['user_name']) ?>
                                </td>
                                <td style="text-align:center;">
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
            <div style="padding:.75rem 1.2rem;border-top:1px solid var(--border-color);font-size:.82rem;color:var(--text-muted);display:flex;gap:1.5rem;">
                <span><i class="fas fa-users" style="margin-right:.35rem;"></i><?= count($assignments) ?> nhân viên</span>
                <span><i class="fas fa-layer-group" style="margin-right:.35rem;"></i><?= count($grouped) ?> ca</span>
            </div>
            <?php endif; ?>
        </div>

        <!-- ── Cấu hình ca trực ──────────────────────────── -->
        <div class="card">
            <div class="card-header">
                <h2><i class="fas fa-cog"></i> Cấu hình Ca trực</h2>
                <span style="font-size:.8rem;color:var(--text-muted);"><?= count($shifts) ?> ca đang hoạt động</span>
            </div>
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>Tên ca</th>
                            <th>Bắt đầu</th>
                            <th>Kết thúc</th>
                            <th>Thời lượng</th>
                            <th style="width:80px;text-align:center;">Xóa</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($shifts)): ?>
                        <tr>
                            <td colspan="5" style="text-align:center;padding:2rem;color:var(--text-muted);">
                                <i class="fas fa-clock" style="font-size:1.5rem;opacity:.3;display:block;margin-bottom:.5rem;"></i>
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
                                    <strong style="color:var(--text-primary);"><?= e($s['name']) ?></strong>
                                </td>
                                <td>
                                    <span style="font-variant-numeric:tabular-nums;"><?= date('H:i', $start) ?></span>
                                </td>
                                <td>
                                    <span style="font-variant-numeric:tabular-nums;"><?= date('H:i', $end) ?></span>
                                </td>
                                <td>
                                    <span style="background:rgba(var(--gold-rgb,212,175,55),.12);color:var(--gold,#d4af37);padding:.15rem .5rem;border-radius:6px;font-size:.8rem;font-weight:600;">
                                        <?= $duration ?>
                                    </span>
                                </td>
                                <td style="text-align:center;">
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
                                <span style="background:rgba(34,197,94,.15);color:#16a34a;font-size:.72rem;padding:.1rem .45rem;border-radius:5px;margin-left:.4rem;">Hôm nay</span>
                                <?php endif; ?>
                            </td>
                            <td><?= $h['total_assignments'] ?> người</td>
                            <td style="color:var(--text-muted);font-size:.87rem;"><?= e($h['staff_names']) ?></td>
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
                <div style="padding:1.2rem;display:flex;flex-direction:column;gap:1rem;">
                    <div class="form-group">
                        <label class="form-label">Tên Ca <span style="color:#ef4444;">*</span></label>
                        <input type="text" name="name" id="shift-name" class="form-control"
                               placeholder="VD: Ca Sáng, Ca Tối..." required maxlength="50">
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giờ bắt đầu <span style="color:#ef4444;">*</span></label>
                        <input type="time" name="start_time" id="shift-start" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Giờ kết thúc <span style="color:#ef4444;">*</span></label>
                        <input type="time" name="end_time" id="shift-end" class="form-control" required>
                    </div>
                    <!-- Preview thời lượng -->
                    <div id="shift-preview" style="display:none;background:rgba(var(--gold-rgb,212,175,55),.08);border:1px solid rgba(var(--gold-rgb,212,175,55),.25);border-radius:10px;padding:.75rem 1rem;font-size:.85rem;color:var(--text-muted);">
                        <i class="fas fa-info-circle" style="margin-right:.4rem;"></i>
                        Thời lượng: <strong id="shift-duration" style="color:var(--text-primary);"></strong>
                    </div>
                    <button type="submit" class="btn btn-gold btn-block" id="btn-save-shift">
                        <i class="fas fa-save"></i> Lưu ca trực
                    </button>
                </div>
            </form>
        </div>

        <!-- Gợi ý ca phổ biến -->
        <div class="card" style="margin-top:1rem;">
            <div class="card-header" style="padding:.9rem 1.2rem;">
                <h2 style="font-size:.88rem;"><i class="fas fa-lightbulb"></i> Ca phổ biến</h2>
            </div>
            <div style="padding:.5rem .8rem 1rem;">
                <?php
                $presets = [
                    ['Ca Sáng',  '06:00', '14:00'],
                    ['Ca Chiều', '14:00', '22:00'],
                    ['Ca Tối',   '18:00', '24:00'],
                    ['Ca Đêm',   '22:00', '06:00'],
                ];
                ?>
                <?php foreach ($presets as $p): ?>
                <button type="button" class="btn-preset-shift" onclick="applyPreset('<?= $p[0] ?>', '<?= $p[1] ?>', '<?= $p[2] ?>')"
                        style="display:flex;justify-content:space-between;align-items:center;width:100%;padding:.55rem .85rem;margin:.3rem 0;border:1px solid var(--border-color);border-radius:8px;background:transparent;cursor:pointer;font-size:.85rem;color:var(--text-primary);text-align:left;transition:all .2s;">
                    <span style="font-weight:500;"><?= $p[0] ?></span>
                    <span style="color:var(--text-muted);font-size:.78rem;"><?= $p[1] ?> – <?= $p[2] ?></span>
                </button>
                <?php endforeach; ?>
            </div>
        </div>
    </aside>

</div><!-- end content-with-aside -->

<!-- ══════════════════════ Modal Gán Nhân Viên ═══════════════════════ -->
<div class="modal-backdrop" id="modalAssign">
    <div class="modal" style="max-width:420px;">
        <div class="modal-header">
            <h3><i class="fas fa-user-plus" style="margin-right:.5rem;"></i>Gán nhân viên vào ca</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/admin/shifts/assign" class="modal-body" style="display:flex;flex-direction:column;gap:1rem;">
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
            <button type="submit" class="btn btn-gold btn-block btn-lg" style="margin-top:.5rem;"
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
