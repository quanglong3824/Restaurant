<?php
// views/tables/index.php — Sơ đồ bàn (Waiter)
$availableCount = $counts['available'] ?? 0;
$occupiedCount = $counts['occupied'] ?? 0;

// Gom nhóm bàn theo khu vực để hiển thị tất cả
$allAreas = array_keys($grouped);
sort($allAreas);
?>
<div class="page-content">

    <!-- Summary bar -->
    <div class="table-summary">
        <div class="table-summary__item table-summary__item--occupied">
            <i class="fas fa-chair" style="color: var(--warning);"></i>
            <span><?= $occupiedCount ?> Đang bận</span>
        </div>
        <div class="table-summary__item table-summary__item--available">
            <i class="fas fa-chair" style="color: var(--success);"></i>
            <span><?= $availableCount ?> Trống</span>
        </div>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="empty-state">
            <i class="fas fa-table-cells-large"></i>
            <h3>Chưa có dữ liệu bàn</h3>
            <p>Vui lòng thêm bàn trong phần quản lý hệ thống.</p>
        </div>
    <?php else: ?>
        <?php foreach ($allAreas as $area): ?>
            <div class="table-group-container" style="margin-bottom: 2.5rem;">
                <h2 style="font-size: 1.1rem; color: var(--gold-dark); margin-bottom: 1.25rem; border-bottom: 2px solid var(--gold-light); padding-bottom: 0.5rem; display: flex; align-items: center; gap: 0.5rem;">
                    <i class="fas fa-map-marker-alt"></i> Khu vực: <?= e($area) ?>
                </h2>

                <div class="table-grid">
                    <?php foreach ($grouped[$area] as $t): 
                        $isOccupied = ($t['status'] === 'occupied');
                        $isChild = !empty($t['parent_id']);
                        $cardClass = $isOccupied ? 'table-card--occupied' : 'table-card--available';
                        if ($isChild) $cardClass .= ' table-card--merged';
                    ?>
                        <div class="table-card <?= $cardClass ?>" 
                             onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)" 
                             role="button" tabindex="0">
                            
                            <i class="fas <?= $isOccupied ? 'fa-utensils' : 'fa-chair' ?> table-icon"></i>
                            
                            <span class="table-name"><?= e($t['name']) ?></span>
                            
                            <span class="table-area">
                                <?php if ($isChild): ?>
                                    <i class="fas fa-link"></i> Ghép → <?= e($t['parent_name'] ?? 'Bàn chính') ?>
                                <?php else: ?>
                                    <?php 
                                        $mergedChildren = $tableModel->getMergedTables($t['id']);
                                        if (!empty($mergedChildren)):
                                    ?>
                                        <span class="badge-gold" style="font-size: 0.65rem; padding: 2px 5px; border-radius:4px;">BÀN CHÍNH</span>
                                    <?php else: ?>
                                        <?= $t['capacity'] ?> ghế
                                    <?php endif; ?>
                                <?php endif; ?>
                            </span>
                            
                            <div class="table-status-dot"></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal: Mở bàn -->
<div class="modal-backdrop" id="modalOpenTable">
    <div class="modal" style="max-width: 360px;">
        <div class="modal-header">
            <h3 id="modalTableName">Mở bàn</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/tables/open" class="modal-body">
            <input type="hidden" name="table_id" id="openTableId">
            <div class="form-group">
                <label class="form-label">Số lượng khách</label>
                <select name="guest_count" class="form-control">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <option value="<?= $i ?>"><?= $i ?> khách</option>
                    <?php endfor; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold btn-block btn-lg">
                <i class="fas fa-door-open"></i> BẮT ĐẦU PHỤC VỤ
            </button>
        </form>
    </div>
</div>

<!-- Modal: Thao tác bàn đang có khách -->
<div class="modal-backdrop" id="modalOccupied">
    <div class="modal" style="max-width: 380px;">
        <div class="modal-header">
            <h3 id="occupiedTableName">Bàn đang bận</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body">
            <div id="occupiedActions" style="display:flex; flex-direction:column; gap:0.75rem;">
                <a id="viewOrderBtn" href="#" class="btn btn-gold btn-lg" style="justify-content:center;">
                    <i class="fas fa-file-invoice-dollar"></i> XEM ĐƠN / THÊM MÓN
                </a>
                <hr style="border:none; border-top:1px solid var(--border); margin:0.5rem 0;">
                <button type="button" id="btnTransfer" class="btn btn-ghost btn-lg" style="justify-content:center;">
                    <i class="fas fa-exchange-alt"></i> CHUYỂN BÀN
                </button>
                <button type="button" id="btnMerge" class="btn btn-ghost btn-lg" style="justify-content:center;">
                    <i class="fas fa-link"></i> GHÉP THÊM BÀN
                </button>
                <button type="button" class="btn btn-ghost" data-modal-close>ĐÓNG</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Chọn bàn để Ghép/Chuyển -->
<div class="modal-backdrop" id="modalSelectTarget">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h3 id="targetModalTitle">Chọn bàn</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form id="targetForm" method="POST" action="" class="modal-body">
            <input type="hidden" name="source_id" id="sourceTableId">
            <p id="targetModalDesc" style="margin-bottom:1rem; font-size:0.9rem; color:var(--text-muted);"></p>
            <div class="form-group">
                <label class="form-label" id="targetLabel">Chọn bàn trống</label>
                <select name="target_id" id="targetSelect" class="form-control" required>
                    <option value="">-- Chọn một bàn trống --</option>
                    <?php 
                    foreach ($grouped as $area => $tables):
                        foreach ($tables as $t):
                            if ($t['status'] === 'available' && empty($t['parent_id'])):
                    ?>
                        <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= e($area) ?>)</option>
                    <?php 
                            endif;
                        endforeach;
                    endforeach; 
                    ?>
                </select>
            </div>
            <button type="submit" class="btn btn-gold btn-block btn-lg" id="targetSubmitBtn">XÁC NHẬN</button>
        </form>
    </div>
</div>

<style>
    .table-card--merged { border-style: dashed !important; border-color: var(--gold) !important; opacity: 0.85; }
    .table-card--merged .table-icon { color: var(--gold); }
    .table-status-dot { width: 10px; height: 10px; border-radius: 50%; margin-top: 5px; }
    .table-card--available .table-status-dot { background: var(--success); }
    .table-card--occupied .table-status-dot { background: var(--danger); }
</style>

<script>
    function handleTableClick(table) {
        if (table.status === 'occupied') {
            // Nếu là bàn phụ đã ghép -> Yêu cầu thao tác trên bàn chính
            if (table.parent_id) {
                alert('Bàn này đã được ghép vào ' + table.parent_name + '. Vui lòng thao tác trên bàn chính.');
                return;
            }

            document.getElementById('occupiedTableName').textContent = table.name;
            document.getElementById('viewOrderBtn').href = '<?= BASE_URL ?>/orders?table_id=' + table.id;
            
            // Cấu hình nút Chuyển bàn
            document.getElementById('btnTransfer').onclick = () => {
                Aurora.closeModal('modalOccupied');
                openTargetModal('transfer', table);
            };

            // Cấu hình nút Ghép bàn
            document.getElementById('btnMerge').onclick = () => {
                Aurora.closeModal('modalOccupied');
                openTargetModal('merge', table);
            };

            Aurora.openModal('modalOccupied');
        } else {
            // Bàn trống -> Mở bàn
            document.getElementById('modalTableName').textContent = 'Mở ' + table.name;
            document.getElementById('openTableId').value = table.id;
            Aurora.openModal('modalOpenTable');
        }
    }

    function openTargetModal(type, sourceTable) {
        const form = document.getElementById('targetForm');
        const title = document.getElementById('targetModalTitle');
        const desc = document.getElementById('targetModalDesc');
        const sourceInput = document.getElementById('sourceTableId');
        const targetSelect = document.getElementById('targetSelect');
        const submitBtn = document.getElementById('targetSubmitBtn');

        sourceInput.value = sourceTable.id;
        // Đổi tên name của select tùy theo type để controller nhận đúng
        targetSelect.name = type === 'transfer' ? 'to_table_id' : 'child_id';
        if (type === 'transfer') {
            sourceInput.name = 'from_table_id';
            form.action = '<?= BASE_URL ?>/tables/transfer';
            title.innerHTML = '<i class="fas fa-exchange-alt"></i> Chuyển bàn';
            desc.textContent = 'Chuyển toàn bộ đơn hàng từ ' + sourceTable.name + ' sang:';
            submitBtn.textContent = 'XÁC NHẬN CHUYỂN';
        } else {
            sourceInput.name = 'parent_id';
            form.action = '<?= BASE_URL ?>/tables/merge';
            title.innerHTML = '<i class="fas fa-link"></i> Ghép thêm bàn';
            desc.textContent = 'Chọn bàn trống để ghép chung với ' + sourceTable.name + ':';
            submitBtn.textContent = 'XÁC NHẬN GHÉP';
        }

        Aurora.openModal('modalSelectTarget');
    }
</script>