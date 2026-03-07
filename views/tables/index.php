<?php
// views/tables/index.php — Sơ đồ bàn (Waiter)
$available = $counts['available'] ?? 0;
$occupied = $counts['occupied'] ?? 0;

// Re-group tables into Occupied and Available
$occupiedGroups = [];
$availableGroups = [];
$allAvailableForSelect = [];

foreach ($grouped as $area => $tables) {
    foreach ($tables as $t) {
        if ($t['status'] === 'occupied') {
            $occupiedGroups[$area][] = $t;
        } else {
            // Chỉ bàn trống và chưa bị ghép mới có thể chọn làm bàn chính hoặc bàn phụ
            if ($t['parent_id'] === null) {
                $availableGroups[$area][] = $t;
                $allAvailableForSelect[] = $t;
            }
        }
    }
}

// Display order for areas
$displayOrder = ['A1', 'B1', 'C1', 'VIP', 'VIP 3+4', 'Âu'];
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
            <?php 
            // Group VIP 1+2 and VIP 3+4 together
            $displayOrder = ['A1', 'B1', 'C1', 'VIP', 'VIP 3+4', 'Âu'];
            $processedVipGroups = [];
            
            foreach ($displayOrder as $displayArea): 
                // Check if this is a VIP group
                if ($displayArea === 'VIP') {
                    $subAreas = ['VIP 1', 'VIP 2'];
                } elseif ($displayArea === 'VIP 3+4') {
                    $subAreas = ['VIP 3', 'VIP 4'];
                } else {
                    $subAreas = [$displayArea];
                }
                
                // Collect tables from all sub-areas
                $areaTables = [];
                foreach ($subAreas as $subArea) {
                    if (isset($occupiedGroups[$subArea])) {
                        $areaTables[$subArea] = $occupiedGroups[$subArea];
                    }
                }
                
                if (empty($areaTables)) continue;
            ?>
                <div class="section-title"
                    style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">
                    <?= e(in_array($displayArea, ['VIP', 'VIP 3+4']) ? 'Phòng VIP' : $displayArea) ?>
                </div>
                
                <?php if (count($subAreas) > 1): ?>
                    <!-- VIP areas: display sub-groups side by side -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <?php foreach ($areaTables as $subArea => $tables): ?>
                            <div style="background: var(--surface-2); padding: 0.75rem; border-radius: var(--radius-md);">
                                <div style="font-size: 0.8rem; color: var(--gold); margin-bottom: 0.5rem; font-weight: 600;">
                                    <?= e($subArea) ?>
                                </div>
                                <div class="table-grid">
                                    <?php foreach ($tables as $t): ?>
                                        <div class="table-card table-card--occupied <?= $t['parent_id'] ? 'table-card--merged' : '' ?>"
                                            onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)" role="button" tabindex="0">
                                            <i class="fas fa-utensils table-icon"></i>
                                            <span class="table-name"><?= e($t['name']) ?></span>
                                            <span class="table-area">
                                                <?php if ($t['parent_id']): ?>
                                                    <i class="fas fa-link"></i> Ghép → <?= e($t['parent_name'] ?? 'Bàn chính') ?>
                                                <?php else: ?>
                                                    <?= $t['capacity'] ?> ghế
                                                <?php endif; ?>
                                            </span>
                                            <div class="table-status-dot"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- Regular areas -->
                    <div class="table-grid" style="margin-bottom: 1rem;">
                        <?php foreach ($areaTables[$displayArea] as $t): ?>
                            <div class="table-card table-card--occupied <?= $t['parent_id'] ? 'table-card--merged' : '' ?>"
                                onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)" role="button" tabindex="0">
                                <i class="fas fa-utensils table-icon"></i>
                                <span class="table-name"><?= e($t['name']) ?></span>
                                <span class="table-area">
                                    <?php if ($t['parent_id']): ?>
                                        <i class="fas fa-link"></i> Ghép → <?= e($t['parent_name'] ?? 'Bàn chính') ?>
                                    <?php else: ?>
                                        <?php 
                                            $mergedChildren = $tableModel->getMergedTables($t['id']);
                                            if (!empty($mergedChildren)):
                                        ?>
                                            <span class="badge-gold" style="font-size: 0.65rem; padding: 2px 5px;">BÀN CHÍNH</span>
                                        <?php else: ?>
                                            <?= e($t['area'] ?? '') ?> · <?= $t['capacity'] ?> ghế
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </span>                                <div class="table-status-dot"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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
            <?php 
            // Group VIP 1+2 and VIP 3+4 together
            foreach ($displayOrder as $displayArea): 
                if ($displayArea === 'VIP') {
                    $subAreas = ['VIP 1', 'VIP 2'];
                } elseif ($displayArea === 'VIP 3+4') {
                    $subAreas = ['VIP 3', 'VIP 4'];
                } else {
                    $subAreas = [$displayArea];
                }
                
                $areaTables = [];
                foreach ($subAreas as $subArea) {
                    if (isset($availableGroups[$subArea])) {
                        $areaTables[$subArea] = $availableGroups[$subArea];
                    }
                }
                
                if (empty($areaTables)) continue;
            ?>
                <div class="section-title"
                    style="margin-top: 0.5rem; font-size: 0.85rem; color: var(--text-muted); text-transform: uppercase;">
                    <?= e(in_array($displayArea, ['VIP', 'VIP 3+4']) ? 'Phòng VIP' : $displayArea) ?>
                </div>
                
                <?php if (count($subAreas) > 1): ?>
                    <!-- VIP areas: display sub-groups side by side -->
                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; margin-bottom: 1rem;">
                        <?php foreach ($areaTables as $subArea => $tables): ?>
                            <div style="background: var(--surface-2); padding: 0.75rem; border-radius: var(--radius-md);">
                                <div style="font-size: 0.8rem; color: var(--gold); margin-bottom: 0.5rem; font-weight: 600;">
                                    <?= e($subArea) ?>
                                </div>
                                <div class="table-grid">
                                    <?php foreach ($tables as $t): ?>
                                        <div class="table-card table-card--available"
                                            onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)" role="button" tabindex="0">
                                            <i class="fas fa-chair table-icon"></i>
                                            <span class="table-name"><?= e($t['name']) ?></span>
                                            <span class="table-area"><?= $t['capacity'] ?> ghế</span>
                                            <div class="table-status-dot"></div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <!-- Regular areas -->
                    <div class="table-grid" style="margin-bottom: 1rem;">
                        <?php foreach ($areaTables[$displayArea] as $t): ?>
                            <div class="table-card table-card--available"
                                onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)" role="button" tabindex="0">
                                <i class="fas fa-chair table-icon"></i>
                                <span class="table-name"><?= e($t['name']) ?></span>
                                <span class="table-area"><?= e($t['area'] ?? '') ?> · <?= $t['capacity'] ?> ghế</span>
                                <div class="table-status-dot"></div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
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

<!-- Modal: Ghép bàn -->
<div class="modal-backdrop" id="modalMergeTable">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3 id="mergeTableName">Ghép bàn</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/tables/merge" class="modal-body">
            <input type="hidden" name="parent_id" id="mergeParentId">
            <p style="margin-bottom:1rem; font-size:0.9rem; color:var(--text-muted);">
                Chọn bàn trống để ghép vào bàn <strong id="mergeParentName"></strong>:
            </p>
            <div class="form-group">
                <label class="form-label">Bàn cần ghép (Bàn phụ)</label>
                <select name="child_id" class="form-control" required>
                    <option value="">-- Chọn bàn trống --</option>
                    <?php foreach ($allAvailableForSelect as $avail): ?>
                        <option value="<?= $avail['id'] ?>"><?= e($avail['name']) ?> (<?= e($avail['area']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div style="background:var(--surface-2); padding:1rem; border-radius:var(--radius-md); margin-bottom:1.5rem; font-size:0.85rem;">
                <i class="fas fa-info-circle" style="color:var(--gold)"></i> Ghép bàn sẽ chuyển trạng thái bàn phụ sang "Có khách" và sử dụng chung một hóa đơn với bàn chính.
            </div>
            <button type="submit" class="btn btn-gold btn-block btn-lg">
                <i class="fas fa-link"></i> Xác nhận ghép bàn
            </button>
        </form>
    </div>
</div>

<!-- Modal: Chuyển bàn -->
<div class="modal-backdrop" id="modalTransferTable">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3 id="transferTableName">Chuyển bàn</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/tables/transfer" class="modal-body">
            <input type="hidden" name="from_table_id" id="transferFromId">
            <p style="margin-bottom:1rem; font-size:0.9rem; color:var(--text-muted);">
                Chuyển khách từ <strong id="transferFromName"></strong> sang bàn trống:
            </p>
            <div class="form-group">
                <label class="form-label">Chọn bàn mới</label>
                <select name="to_table_id" class="form-control" required>
                    <option value="">-- Chọn bàn trống --</option>
                    <?php foreach ($allAvailableForSelect as $avail): ?>
                        <option value="<?= $avail['id'] ?>"><?= e($avail['name']) ?> (<?= e($avail['area']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold btn-block btn-lg">
                <i class="fas fa-exchange-alt"></i> Xác nhận chuyển bàn
            </button>
        </form>
    </div>
</div>

<style>
    .table-card--merged { border-style: dashed; border-color: var(--gold); }
    .table-card--merged .table-icon { color: var(--gold); }
</style>

<script>
    function handleTableClick(table) {
        if (table.status === 'occupied') {
            if (table.parent_id) {
                alert('Đây là bàn đã được ghép vào ' + table.parent_name + '. Vui lòng thao tác trên bàn chính.');
                return;
            }

            document.getElementById('occupiedTableName').textContent = table.name;
            document.getElementById('viewOrderBtn').href = '<?= BASE_URL ?>/orders?table_id=' + table.id;

            const actionsDiv = document.querySelector('#modalOccupied .modal-body div');
            actionsDiv.querySelectorAll('.dynamic-btn').forEach(b => b.remove());

            // Nút Chuyển bàn
            const transferBtn = document.createElement('button');
            transferBtn.type = 'button';
            transferBtn.className = 'btn btn-outline btn-lg dynamic-btn';
            transferBtn.innerHTML = '<i class="fas fa-exchange-alt"></i> Chuyển sang bàn khác';
            transferBtn.onclick = () => {
                Aurora.closeModal('modalOccupied');
                document.getElementById('transferFromId').value = table.id;
                document.getElementById('transferFromName').textContent = table.name;
                Aurora.openModal('modalTransferTable');
            };

            // Nút Ghép bàn
            const mergeBtn = document.createElement('button');
            mergeBtn.type = 'button';
            mergeBtn.className = 'btn btn-outline btn-lg dynamic-btn';
            mergeBtn.innerHTML = '<i class="fas fa-link"></i> Ghép thêm bàn khác';
            mergeBtn.onclick = () => {
                Aurora.closeModal('modalOccupied');
                document.getElementById('mergeParentId').value = table.id;
                document.getElementById('mergeParentName').textContent = table.name;
                Aurora.openModal('modalMergeTable');
            };

            actionsDiv.insertBefore(transferBtn, actionsDiv.lastElementChild);
            actionsDiv.insertBefore(mergeBtn, actionsDiv.lastElementChild);

            Aurora.openModal('modalOccupied');
        } else {
            // Nếu là bàn trống nhưng đang bị ghép (vô lý vì status available và parent_id null đã lọc trên, nhưng cứ check)
            if (table.parent_id) {
                if(confirm('Bàn này đang được ghép vào ' + table.parent_name + '. Bạn muốn tách bàn này ra?')) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '<?= BASE_URL ?>/tables/unmerge';
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'table_id';
                    input.value = table.id;
                    form.appendChild(input);
                    document.body.appendChild(form);
                    form.submit();
                }
                return;
            }

            document.getElementById('modalTableName').textContent = 'Mở — ' + table.name;
            document.getElementById('openTableId').value = table.id;
            Aurora.openModal('modalOpenTable');
        }
    }
</script>