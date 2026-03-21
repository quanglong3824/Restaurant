<?php
// views/tables/index.php — Professional Floor Plan
$availableCount = $counts['available'] ?? 0;
$occupiedCount = $counts['occupied'] ?? 0;

$allAreas = array_keys($grouped);
sort($allAreas);

// Process areas
$vip1_tables = $grouped['VIP 1'] ?? [];
$vip2_tables = $grouped['VIP 2'] ?? [];
$vip3_tables = $grouped['VIP 3'] ?? [];
$vip4_tables = $grouped['VIP 4'] ?? [];

$other_areas = [];
foreach ($grouped as $area => $tables) {
    if (in_array($area, ['VIP 1', 'VIP 2', 'VIP 3', 'VIP 4', 'VIP 1 2', 'VIP 3 4'])) continue;
    $other_areas[$area] = $tables;
}

// Phân loại khu vực cho modal
$uniqueAreas = [];
foreach ($allAreas as $a) {
    if (in_array($a, ['VIP 1 2', 'VIP 3 4'])) continue;
    $uniqueAreas[] = $a;
}

/**
 * Helper function to render a table/room card
 */
if (!function_exists('renderTableCard')) {
    function renderTableCard($t, $tableModel, $type) {
        $isOccupied = ($t['status'] === 'occupied');
        $isChild = !empty($t['parent_id']);
        $isPaymentRequested = isset($t['order_note']) && strpos($t['order_note'], 'KHÁCH YÊU CẦU THANH TOÁN') !== false;
        
        $cardClass = $isOccupied ? 'is-occupied' : 'is-available';
        if ($isChild) $cardClass .= ' is-merged-child';
        if ($isPaymentRequested) $cardClass .= ' is-payment-requested';
        
        $icon = ($type === 'room') ? ($isOccupied ? 'fa-door-closed' : 'fa-door-open') : ($isOccupied ? 'fa-utensils' : 'fa-chair');
        ?>
        <div class="premium-table-card <?= $cardClass ?>" onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)">
            <?php if ($isPaymentRequested): ?>
                <div class="payment-pulse-badge"><i class="fas fa-hand-holding-usd"></i></div>
            <?php endif; ?>
            <div class="card-status-bar"></div>
            <div class="card-content">
                <div class="table-id"><?= e($t['name']) ?></div>
                <div class="table-info">
                    <?php if ($isChild): ?>
                        <span class="merge-info"><i class="fas fa-link"></i> <?= e($t['parent_name'] ?? 'P') ?></span>
                    <?php else: ?>
                        <?php
                        $mergedChildren = $tableModel->getMergedTables($t['id']);
                        if (!empty($mergedChildren)): ?>
                            <span class="master-label">MASTER</span>
                        <?php else: ?>
                            <span class="capacity-label"><?= $t['capacity'] ?> <?= ($type === 'room') ? 'NGƯỜI' : 'GHẾ' ?></span>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-icon"><i class="fas <?= $icon ?>"></i></div>
        </div>
        <?php
    }
}
?>

<style>
    .premium-table-card {
        background: white; border-radius: 18px; padding: 1.25rem; position: relative; overflow: hidden;
        display: flex; flex-direction: column; justify-content: space-between; min-height: 110px;
        border: 1px solid #edf2f7; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1); cursor: pointer;
    }
    .premium-table-card:active { transform: scale(0.95); }
    .card-status-bar { position: absolute; top: 0; left: 0; right: 0; height: 6px; background: #e2e8f0; }
    .is-available .card-status-bar { background: linear-gradient(90deg, #10b981, #34d399); }
    .is-occupied .card-status-bar { background: linear-gradient(90deg, #ef4444, #f87171); }
    .is-merged-child .card-status-bar { background: repeating-linear-gradient(45deg, #b89B5e, #b89B5e 10px, #d4af37 10px, #d4af37 20px); }
    .table-id { font-size: 1.4rem; font-weight: 800; color: #1a202c; margin-bottom: 0.25rem; }
    .table-info { font-size: 0.7rem; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: 0.5px; }
    .card-icon { position: absolute; bottom: -10px; right: -5px; font-size: 3.5rem; opacity: 0.04; transform: rotate(-15deg); transition: all 0.3s ease; }
    .is-occupied .card-icon { opacity: 0.08; color: #ef4444; }
    .master-label { background: var(--gold); color: white; padding: 2px 6px; border-radius: 6px; font-size: 0.6rem; }
    .premium-tabs { display: flex; background: #f1f5f9; padding: 6px; border-radius: 16px; margin-bottom: 2rem; gap: 6px; }
    .premium-tab-item { flex: 1; display: flex; align-items: center; justify-content: center; gap: 8px; padding: 12px; border-radius: 12px; text-decoration: none; color: #64748b; font-weight: 700; font-size: 0.9rem; transition: all 0.3s ease; }
    .premium-tab-item.active { background: white; color: var(--gold); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
    .area-section { margin-bottom: 3rem; }
    .area-header { display: flex; align-items: center; gap: 12px; margin-bottom: 1.5rem; padding-bottom: 0.75rem; border-bottom: 2px solid #f1f5f9; }
    .area-header h2 { font-size: 1.25rem; font-weight: 800; color: #1e293b; margin: 0; }
    .area-icon { width: 40px; height: 40px; background: #f8fafc; border-radius: 10px; display: flex; align-items: center; justify-content: center; color: var(--gold); font-size: 1.1rem; }
    .interactive-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 10px; }
    .interactive-area-item { cursor: pointer; }
    .area-box { background: #f1f5f9; border: 2px solid #e2e8f0; border-radius: 12px; padding: 12px 8px; text-align: center; transition: all 0.2s; }
    .area-box-name { font-weight: 800; font-size: 0.85rem; color: #1e293b; }
    .interactive-area-item input:checked + .area-box { background: #fef3c7; border-color: #f59e0b; transform: scale(1.05); }
    .interactive-area-item.is-merged .area-box { background: #fee2e2; border-color: #fca5a5; }
    .is-payment-requested { border: 2px solid #f59e0b !important; animation: premium-border-pulse 2s infinite; }
    @keyframes premium-border-pulse { 0% { border-color: #f59e0b; } 50% { border-color: #fbbf24; } 100% { border-color: #f59e0b; } }
    .payment-pulse-badge { position: absolute; top: 12px; right: 12px; background: #f59e0b; color: white; width: 28px; height: 28px; border-radius: 50%; display: flex; align-items: center; justify-content: center; z-index: 5; font-size: 0.8rem; box-shadow: 0 4px 8px rgba(245, 158, 11, 0.3); }
</style>

<div class="page-content animate-fade-in">
    <nav class="premium-tabs">
        <a href="<?= BASE_URL ?>/tables?type=table" class="premium-tab-item <?= $type === 'table' ? 'active' : '' ?>">
            <i class="fas fa-utensils"></i> BÀN NHÀ HÀNG
        </a>
        <a href="<?= BASE_URL ?>/tables?type=room" class="premium-tab-item <?= $type === 'room' ? 'active' : '' ?>">
            <i class="fas fa-bed"></i> KHÁCH LƯU TRÚ
        </a>
    </nav>

    <div class="summary-shelf d-flex gap-3 mb-5">
        <div class="summary-box flex-grow-1 occupied">
            <div class="box-icon"><i class="fas <?= ($type === 'room') ? 'fa-door-closed' : 'fa-utensils' ?>"></i></div>
            <div class="box-info"><div class="val"><?= $occupiedCount ?></div><div class="lbl">ĐANG CÓ KHÁCH</div></div>
        </div>
        <div class="summary-box flex-grow-1 available">
            <div class="box-icon"><i class="fas <?= ($type === 'room') ? 'fa-door-open' : 'fa-chair' ?>"></i></div>
            <div class="box-info"><div class="val"><?= $availableCount ?></div><div class="lbl">BÀN/PHÒNG TRỐNG</div></div>
        </div>
    </div>

    <div class="d-flex justify-content-end gap-2 mb-4">
        <button type="button" class="btn btn-ghost btn-sm px-3" onclick="Aurora.openModal('modalUnmergeArea')"><i class="fas fa-object-ungroup me-2"></i> TÁCH KHU</button>
        <button type="button" class="btn btn-gold btn-sm px-4" onclick="Aurora.openModal('modalMergeArea')"><i class="fas fa-object-group me-2"></i> GHÉP KHU</button>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="empty-state py-5 text-center"><i class="fas fa-table-cells-large fa-3x mb-3 opacity-20"></i><h4 class="fw-bold">Chưa có sơ đồ</h4></div>
    <?php else: ?>
        <?php if (!empty($vip1_tables) || !empty($vip2_tables)): $vip12 = array_merge($vip1_tables, $vip2_tables); ?>
            <div class="area-section"><div class="area-header"><div class="area-icon"><i class="fas fa-crown"></i></div><h2>Khu vực: VIP 1 & 2</h2></div>
            <div class="table-grid"><?php foreach ($vip12 as $t) renderTableCard($t, $tableModel, $type); ?></div></div>
        <?php endif; ?>
        <?php if (!empty($vip3_tables) || !empty($vip4_tables)): $vip34 = array_merge($vip3_tables, $vip4_tables); ?>
            <div class="area-section"><div class="area-header"><div class="area-icon"><i class="fas fa-crown"></i></div><h2>Khu vực: VIP 3 & 4</h2></div>
            <div class="table-grid"><?php foreach ($vip34 as $t) renderTableCard($t, $tableModel, $type); ?></div></div>
        <?php endif; ?>
        <?php foreach ($other_areas as $area => $tables): ?>
            <div class="area-section"><div class="area-header"><div class="area-icon"><i class="fas fa-map-marker-alt"></i></div><h2>Khu vực: <?= e($area) ?></h2></div>
            <div class="table-grid"><?php foreach ($tables as $t) renderTableCard($t, $tableModel, $type); ?></div></div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal: Mở bàn -->
<div class="modal-backdrop" id="modalOpenTable">
    <div class="modal modal-premium" style="max-width: 400px;">
        <div class="modal-header"><h3>Phục vụ <span id="modalTableName" class="text-gold"></span></h3><button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button></div>
        <form method="POST" action="<?= BASE_URL ?>/tables/open" class="modal-body py-3">
            <input type="hidden" name="table_id" id="openTableId">
            <div class="form-group mb-3">
                <label class="form-label">Số lượng khách</label>
                <div class="guest-selector-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px;">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <label class="guest-option">
                            <input type="radio" name="guest_count" value="<?= $i ?>" <?= ($i === 2) ? 'checked' : '' ?> style="display:none;">
                            <span style="display:block; padding: 10px; background: #f1f5f9; border-radius: 8px; text-align: center; cursor: pointer; font-weight: 800;"><?= $i ?></span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-gold w-100 py-3 fw-bold">BẮT ĐẦU PHỤC VỤ</button>
        </form>
    </div>
</div>

<!-- Modal: Bàn đang bận -->
<div class="modal-backdrop" id="modalOccupied">
    <div class="modal modal-premium" style="max-width: 450px;">
        <div class="modal-header"><h3>Đang phục vụ <span id="modalOccupiedTableName" class="text-gold"></span></h3><button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button></div>
        <div class="modal-body py-4"><div class="d-grid gap-3">
            <a id="viewOrderBtn" href="#" class="btn btn-gold py-3" style="text-decoration: none;"><i class="fas fa-file-invoice-dollar me-2"></i> CHI TIẾT & GỌI MÓN</a>
            <div class="d-flex gap-2"><button type="button" class="btn btn-ghost flex-fill py-3" onclick="handleTransferClick()">CHUYỂN</button><button type="button" class="btn btn-ghost flex-fill py-3" onclick="handleMergeTableClick()">GHÉP</button></div>
            <div class="d-flex gap-2"><button type="button" class="btn btn-outline-danger flex-fill py-3" onclick="handleUnmergeTableClick()" id="unmergeTableBtn" style="display:none;">HỦY GHÉP</button><button type="button" class="btn btn-outline-danger flex-fill py-3" onclick="handleSplitTableClick()">TÁCH BÀN</button></div>
        </div></div>
    </div>
</div>

<!-- Modal: Chọn bàn đích -->
<div class="modal-backdrop" id="modalSelectTarget">
    <div class="modal modal-premium" style="max-width: 450px;">
        <div class="modal-header"><h3 id="targetModalTitle">Chọn đích</h3><button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button></div>
        <form id="targetForm" method="POST" class="modal-body">
            <input type="hidden" name="from_table_id" id="sourceTableId">
            <div class="form-group mb-3">
                <select name="to_table_id" id="targetSelect" class="form-control" required><option value="">-- Chọn --</option>
                    <?php foreach ($grouped as $area => $tables): ?><optgroup label="<?= e($area) ?>">
                        <?php foreach ($tables as $t): if ($t['status'] === 'available' && empty($t['parent_id'])): ?><option value="<?= $t['id'] ?>"><?= e($t['name']) ?></option><?php endif; endforeach; ?>
                    </optgroup><?php endforeach; ?>
                </select>
            </div>
            <button type="submit" id="targetSubmitBtn" class="btn btn-gold w-100 py-3 fw-bold">XÁC NHẬN</button>
        </form>
    </div>
</div>

<!-- Modal: Ghép Khu Vực -->
<div class="modal-backdrop" id="modalMergeArea">
    <div class="modal modal-premium" style="max-width: 500px;">
        <div class="modal-header"><h3>Ghép Khu Vực</h3><button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button></div>
        <form method="POST" action="<?= BASE_URL ?>/tables/merge_areas" class="modal-body">
            <div class="interactive-grid">
                <?php foreach ($uniqueAreas as $a): ?><label class="interactive-area-item"><input type="checkbox" name="areas[]" value="<?= e($a) ?>" class="d-none"><div class="area-box"><div class="area-box-name"><?= e($a) ?></div></div></label><?php endforeach; ?>
            </div>
            <button type="submit" class="btn btn-gold w-100 py-3 fw-bold mt-4">XÁC NHẬN GHÉP</button>
        </form>
    </div>
</div>

<!-- Modal: Tách Khu Vực -->
<div class="modal-backdrop" id="modalUnmergeArea">
    <div class="modal modal-premium" style="max-width: 500px;">
        <div class="modal-header"><h3 class="text-danger">Tách Khu Vực</h3><button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button></div>
        <form method="POST" action="<?= BASE_URL ?>/tables/unmerge_areas" class="modal-body">
            <div class="interactive-grid">
                <?php foreach ($uniqueAreas as $a): ?><label class="interactive-area-item"><input type="checkbox" name="unmerge_areas[]" value="<?= e($a) ?>" class="d-none"><div class="area-box"><div class="area-box-name"><?= e($a) ?></div></div></label><?php endforeach; ?>
            </div>
            <button type="submit" class="btn bg-danger text-white w-100 py-3 fw-bold mt-4">XÁC NHẬN TÁCH</button>
        </form>
    </div>
</div>

<link rel="stylesheet" href="<?= asset('public/css/tables.css') ?>">

<script>
// Định nghĩa các hàm xử lý bàn bên ngoài DOMContentLoaded để đảm bảo handleTableClick được gọi từ HTML onclick
var currentSelectedTable = null;

function handleTableClick(table) {
    currentSelectedTable = table;
    if (table.status === 'occupied') {
        document.getElementById('modalOccupiedTableName').textContent = table.name;
        document.getElementById('viewOrderBtn').href = BASE_URL + '/orders?table_id=' + table.id;
        const unmergeBtn = document.getElementById('unmergeTableBtn');
        if (unmergeBtn) unmergeBtn.style.display = table.parent_id ? 'block' : 'none';
        Aurora.openModal('modalOccupied');
    } else {
        document.getElementById('modalTableName').textContent = table.name;
        document.getElementById('openTableId').value = table.id;
        Aurora.openModal('modalOpenTable');
    }
}

function handleTransferClick() {
    Aurora.closeModal('modalOccupied');
    document.getElementById('targetModalTitle').textContent = 'Chuyển: ' + currentSelectedTable.name;
    document.getElementById('sourceTableId').value = currentSelectedTable.id;
    document.getElementById('targetForm').action = BASE_URL + '/tables/transfer';
    Aurora.openModal('modalSelectTarget');
}

function handleMergeTableClick() {
    Aurora.closeModal('modalOccupied');
    document.getElementById('targetModalTitle').textContent = 'Ghép vào: ' + currentSelectedTable.name;
    document.getElementById('sourceTableId').name = 'parent_id';
    document.getElementById('sourceTableId').value = currentSelectedTable.id;
    document.getElementById('targetSelect').name = 'child_id';
    document.getElementById('targetForm').action = BASE_URL + '/tables/merge';
    Aurora.openModal('modalSelectTarget');
}

function handleUnmergeTableClick() {
    if (!confirm('Tách bàn này ra khỏi nhóm ghép?')) return;
    const f = document.createElement('form'); f.method = 'POST'; f.action = BASE_URL + '/tables/unmerge';
    f.innerHTML = '<input type="hidden" name="table_id" value="' + currentSelectedTable.id + '">';
    document.body.appendChild(f); f.submit();
}

function handleSplitTableClick() { 
    window.location.href = BASE_URL + '/orders?table_id=' + currentSelectedTable.id + '&action=split'; 
}

document.addEventListener('DOMContentLoaded', function() {
    // Styling guest options
    document.querySelectorAll('.guest-option input').forEach(input => {
        input.addEventListener('change', function() {
            document.querySelectorAll('.guest-option span').forEach(s => s.style.background = '#f1f5f9');
            if (this.checked) this.nextElementSibling.style.background = '#b89B5e';
        });
    });
});
</script>
