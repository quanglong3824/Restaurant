<?php
// views/tables/index.php — Sơ đồ bàn (Waiter)
$available = $counts['available'] ?? 0;
$occupied = $counts['occupied'] ?? 0;

// Re-group tables into Occupied and Available
$occupiedGroups = [];
$availableGroups = [];

foreach ($grouped as $area => $tables) {
    foreach ($tables as $t) {
        if ($t['status'] === 'occupied') {
            $occupiedGroups[$area][] = $t;
        } else {
            $availableGroups[$area][] = $t;
        }
    }
}
?>
<div class="page-content">

    <!-- Summary bar -->
    <div class="table-summary">
        <div class="table-summary__item table-summary__item--occupied" style="opacity: 1;">
            <i class="fas fa-chair" style="color: var(--warning);"></i>
            <span><?= $occupied ?> Đang bận</span>
        </div>
        <div class="table-summary__item table-summary__item--available" style="opacity: 1;">
            <i class="fas fa-chair" style="color: var(--success);"></i>
            <span><?= $available ?> Trống</span>
        </div>
    </div>

    <!-- ===== BÀN ĐANG BẬN ===== -->
    <div class="table-group-container" style="margin-bottom: 2rem;">
        <h2
            style="font-size: 1.1rem; color: var(--warning); margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
            <i class="fas fa-users" style="margin-right: 6px;"></i> Bàn đang phục vụ
        </h2>

        <?php if (empty($occupiedGroups)): ?>
            <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                Không có bàn nào đang bận.
            </div>
        <?php else: ?>
            <?php foreach ($occupiedGroups as $area => $tables): ?>
                <div class="section-title"
                    style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">
                    <?= e($area) ?>
                </div>
                <div class="table-grid" style="margin-bottom: 1rem;">
                    <?php foreach ($tables as $t): ?>
                        <div class="table-card table-card--occupied"
                            onclick="handleTableClick(<?= $t['id'] ?>, '<?= e($t['name']) ?>', true)" role="button" tabindex="0">
                            <i class="fas fa-utensils table-icon"></i>
                            <span class="table-name">
                                <?= e($t['name']) ?>
                            </span>
                            <span class="table-area">
                                <?= e($t['area'] ?? '') ?> ·
                                <?= $t['capacity'] ?> ghế
                            </span>
                            <div class="table-status-dot"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- ===== BÀN TRỐNG ===== -->
    <div class="table-group-container">
        <h2
            style="font-size: 1.1rem; color: var(--success); margin-bottom: 1rem; border-bottom: 1px solid var(--border); padding-bottom: 0.5rem;">
            <i class="fas fa-couch" style="margin-right: 6px;"></i> Bàn trống / Sẵn sàng
        </h2>

        <?php if (empty($availableGroups)): ?>
            <div style="color: var(--text-muted); font-size: 0.9rem; margin-bottom: 1rem;">
                Nhà hàng đang hết bàn trống.
            </div>
        <?php else: ?>
            <?php foreach ($availableGroups as $area => $tables): ?>
                <div class="section-title"
                    style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">
                    <?= e($area) ?>
                </div>
                <div class="table-grid" style="margin-bottom: 1rem;">
                    <?php foreach ($tables as $t): ?>
                        <div class="table-card table-card--available"
                            onclick="handleTableClick(<?= $t['id'] ?>, '<?= e($t['name']) ?>', false)" role="button" tabindex="0">
                            <i class="fas fa-chair table-icon"></i>
                            <span class="table-name">
                                <?= e($t['name']) ?>
                            </span>
                            <span class="table-area">
                                <?= e($t['area'] ?? '') ?> ·
                                <?= $t['capacity'] ?> ghế
                            </span>
                            <div class="table-status-dot"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="empty-state">
            <i class="fas fa-table-cells-large"></i>
            <h3>Chưa có bàn nào</h3>
            <p>Admin hãy thêm bàn trong phần quản lý.</p>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Mở bàn -->
<div class="modal-backdrop" id="modalOpenTable">
    <div class="modal" style="max-width: 360px;">
        <div class="modal-header">
            <h3 id="modalTableName">Mở bàn</h3>
            <button class="modal-close" data-modal-close type="button" aria-label="Đóng">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/tables/open" class="modal-body">
            <input type="hidden" name="table_id" id="openTableId">
            <div class="form-group">
                <label class="form-label">Số khách</label>
                <select name="guest_count" class="form-control" id="guestCount">
                    <?php for ($i = 1; $i <= 6; $i++): ?>
                        <option value="<?= $i ?>">
                            <?= $i ?> khách
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold btn-block btn-lg">
                <i class="fas fa-door-open"></i> Bắt đầu phục vụ (Mở bàn)
            </button>
        </form>
    </div>
</div>

<!-- Modal: Bàn đang có khách -->
<div class="modal-backdrop" id="modalOccupied">
    <div class="modal" style="max-width: 360px;">
        <div class="modal-header">
            <h3 id="occupiedTableName">Bàn đang có khách</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <p style="margin-bottom:1.5rem;color:var(--text-muted);font-size:0.9rem;">
                Bàn này hiện tại đang được sử dụng. Cần xử lý gì:
            </p>
            <div style="display:flex;flex-direction:column;gap:.75rem;">
                <a id="viewOrderBtn" href="#" class="btn btn-gold btn-lg">
                    <i class="fas fa-plus-circle"></i> View / Thêm món cho Bàn
                </a>
                <button type="button" class="btn btn-ghost" data-modal-close>Quay lại</button>
            </div>
        </div>
    </div>
</div>

<script>
    function handleTableClick(tableId, tableName, isOccupied) {
        if (isOccupied) {
            document.getElementById('occupiedTableName').textContent = tableName;
            document.getElementById('viewOrderBtn').href =
                '<?= BASE_URL ?>/orders?table_id=' + tableId;
            Aurora.openModal('modalOccupied');
        } else {
            document.getElementById('modalTableName').textContent = 'Mở — ' + tableName;
            document.getElementById('openTableId').value = tableId;
            Aurora.openModal('modalOpenTable');
        }
    }
</script>