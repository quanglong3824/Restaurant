<?php
// views/tables/index.php — Professional Floor Plan
$availableCount = $counts['available'] ?? 0;
$occupiedCount = $counts['occupied'] ?? 0;

$allAreas = array_keys($grouped);
sort($allAreas);

// Process areas to group VIP 1 and 2 together, VIP 3 and 4 together
$vip1_tables = [];
$vip2_tables = [];
$vip3_tables = [];
$vip4_tables = [];
$other_areas = [];

foreach ($allAreas as $area) {
    if ($area === 'VIP 1') {
        $vip1_tables = $grouped[$area];
    } elseif ($area === 'VIP 2') {
        $vip2_tables = $grouped[$area];
    } elseif ($area === 'VIP 3') {
        $vip3_tables = $grouped[$area];
    } elseif ($area === 'VIP 4') {
        $vip4_tables = $grouped[$area];
    } else {
        if ($area === 'VIP 1 2' || $area === 'VIP 3 4') {
            continue;
        }
        $other_areas[$area] = $grouped[$area];
    }
}

// Prepare unique areas for modal forms (excluding merged areas)
$uniqueAreas = [];
foreach ($allAreas as $a) {
    if ($a === 'VIP 1 2' || $a === 'VIP 3 4') {
        continue;
    }
    $uniqueAreas[] = $a;
}

// Phân loại khu vực cho modal
$abcAreas = [];
$vipAreas = [];
$auAreas = [];
$otherModalAreas = [];

foreach ($uniqueAreas as $a) {
    if (in_array($a, ['A1', 'B1', 'C1'])) {
        $abcAreas[] = $a;
    } elseif (strpos($a, 'VIP') !== false) {
        $vipAreas[] = $a;
    } elseif ($a === 'Âu') {
        $auAreas[] = $a;
    } else {
        $otherModalAreas[] = $a;
    }
}

// Helper function to render table token
function renderTableToken($t, $tableModel) {
    $isOccupied = ($t['status'] === 'occupied');
    $isChild = !empty($t['parent_id']);
    $isPaymentRequested = isset($t['order_note']) && strpos($t['order_note'], 'KHÁCH YÊU CẦU THANH TOÁN') !== false;
    
    $cardClass = $isOccupied ? 'occupied' : 'available';
    if ($isChild) {
        $cardClass .= ' merged-child';
    }
    if ($isPaymentRequested) {
        $cardClass .= ' payment-requested';
    }
    ?>
    <div class="table-token <?= $cardClass ?>" onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)" role="button">
        <?php if ($isPaymentRequested): ?>
            <div class="payment-badge-pulse"><i class="fas fa-hand-holding-usd"></i></div>
        <?php endif; ?>
        <div class="token-status-ring"></div>
        <div class="token-body">
            <div class="token-name"><?= e($t['name']) ?></div>
            <div class="token-meta">
                <?php if ($isChild): ?>
                    <i class="fas fa-link"></i> Ghép → <?= e($t['parent_name'] ?? 'P') ?>
                <?php else: ?>
                    <?php
                    $mergedChildren = $tableModel->getMergedTables($t['id']);
                    if (!empty($mergedChildren)): ?>
                        <span class="master-badge">MASTER</span>
                    <?php else: ?>
                        <?= $t['capacity'] ?> GHE
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="token-icon">
            <i class="fas <?= $isOccupied ? 'fa-utensils' : 'fa-chair' ?>"></i>
        </div>
    </div>
    <?php
}
?>

<style>
    .payment-requested {
        border: 3px solid #f59e0b !important;
        background: #fffbeb !important;
        animation: border-pulse 1.5s infinite;
    }
    .payment-badge-pulse {
        position: absolute;
        top: -10px;
        right: -10px;
        background: #f59e0b;
        color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        z-index: 10;
        box-shadow: 0 4px 10px rgba(245, 158, 11, 0.4);
        animation: badge-pulse 1s infinite;
    }
    @keyframes border-pulse {
        0% { border-color: #f59e0b; }
        50% { border-color: #fbbf24; }
        100% { border-color: #f59e0b; }
    }
    @keyframes badge-pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.2); }
        100% { transform: scale(1); }
    }
</style>

<div class="page-content" style="padding-bottom: 2rem;">
    <!-- Summary Header -->
    <div class="summary-shelf d-flex gap-2 mb-4">
        <div class="summary-box glass occupied flex-grow-1">
            <div class="box-icon"><i class="fas fa-utensils"></i></div>
            <div class="box-info">
                <div class="val"><?= $occupiedCount ?></div>
                <div class="lbl">ĐANG CÓ KHÁCH</div>
            </div>
        </div>
        <div class="summary-box glass available flex-grow-1">
            <div class="box-icon"><i class="fas fa-door-open"></i></div>
            <div class="box-info">
                <div class="val"><?= $availableCount ?></div>
                <div class="lbl">BÀN ĐANG TRỐNG</div>
            </div>
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="d-flex justify-content-end gap-2" style="margin-top: 1rem; margin-bottom: 4rem;">
        <button type="button" class="btn btn-outline-danger py-2 px-4 shadow-sm fw-bold" style="border-radius: 8px;" onclick="Aurora.openModal('modalUnmergeArea')">
            <i class="fas fa-object-ungroup me-2"></i> TÁCH KHU
        </button>
        <button type="button" class="btn btn-gold py-2 px-4 shadow-sm fw-bold" style="border-radius: 8px;" onclick="Aurora.openModal('modalMergeArea')">
            <i class="fas fa-object-group me-2"></i> GHÉP KHU
        </button>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="empty-state py-5 text-center">
            <i class="fas fa-table-cells-large fa-3x mb-3 opacity-20"></i>
            <h4 class="fw-bold">Chưa thiết lập sơ đồ bàn</h4>
            <p class="text-muted small">Vui lòng quản lý bàn trong bảng điều khiển Admin.</p>
        </div>
    <?php else: ?>
        <?php
        // Display VIP 1 & 2 combined
        if (!empty($vip1_tables) || !empty($vip2_tables)):
            $vip12_combined = array_merge($vip1_tables, $vip2_tables);
            ?>
            <div class="area-section mb-5">
                <div class="area-header mb-4">
                    <h2 class="area-title playfair">
                        <span class="title-accent"></span>
                        <i class="fas fa-map-marked-alt text-gold me-3"></i> Khu vực: VIP 1 & 2
                    </h2>
                </div>
                <div class="table-grid">
                    <?php foreach ($vip12_combined as $t) renderTableToken($t, $tableModel); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
        // Display VIP 3 & 4 combined
        if (!empty($vip3_tables) || !empty($vip4_tables)):
            $vip34_combined = array_merge($vip3_tables, $vip4_tables);
            ?>
            <div class="area-section mb-5">
                <div class="area-header mb-4">
                    <h2 class="area-title playfair">
                        <span class="title-accent"></span>
                        <i class="fas fa-map-marked-alt text-gold me-3"></i> Khu vực: VIP 3 & 4
                    </h2>
                </div>
                <div class="table-grid">
                    <?php foreach ($vip34_combined as $t) renderTableToken($t, $tableModel); ?>
                </div>
            </div>
        <?php endif; ?>

        <?php
        // Display other areas normally
        foreach ($other_areas as $area => $tables):
            ?>
            <div class="area-section mb-5">
                <div class="area-header mb-4">
                    <h2 class="area-title playfair">
                        <span class="title-accent"></span>
                        <i class="fas fa-map-marked-alt text-gold me-3"></i> Khu vực: <?= e($area) ?>
                    </h2>
                </div>
                <div class="table-grid">
                    <?php foreach ($tables as $t) renderTableToken($t, $tableModel); ?>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>

<!-- Modal: Mở bàn -->
<div class="modal-backdrop" id="modalOpenTable">
    <div class="modal modal-premium" style="max-width: 400px;">
        <div class="modal-header">
            <h3 id="modalTableName" class="playfair">Phục vụ bàn</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/tables/open" class="modal-body">
            <input type="hidden" name="table_id" id="openTableId">
            <div class="form-group mb-4">
                <label class="form-label text-gold small fw-bold text-uppercase">Số lượng khách</label>
                <div class="guest-selector-grid">
                    <?php for ($i = 1; $i <= 12; $i++): ?>
                        <label class="guest-option">
                            <input type="radio" name="guest_count" value="<?= $i ?>" <?= $i === 2 ? 'checked' : '' ?>>
                            <span><?= $i ?></span>
                        </label>
                    <?php endfor; ?>
                </div>
            </div>
            <button type="submit" class="btn btn-gold w-100 py-3 fw-bold">
                <i class="fas fa-play me-2"></i> BẮT ĐẦU PHỤC VỤ
            </button>
        </form>
    </div>
</div>

<!-- Modal: Bàn đang bận -->
<div class="modal-backdrop" id="modalOccupied">
    <div class="modal modal-premium" style="max-width: 450px;">
        <div class="modal-header">
            <h3 id="occupiedTableName" class="playfair">Bàn đang phục vụ</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body py-4">
            <div class="d-grid gap-3">
                <a id="viewOrderBtn" href="#" class="btn btn-gold py-3 shadow-sm">
                    <i class="fas fa-file-invoice-dollar me-2"></i> XEM CHI TIẾT & GỌI MÓN
                </a>
                
                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-ghost flex-fill py-3" onclick="handleTransferClick()">
                        <i class="fas fa-exchange-alt me-2"></i> CHUYỂN BÀN
                    </button>
                    <button type="button" class="btn btn-ghost flex-fill py-3" onclick="handleMergeTableClick()">
                        <i class="fas fa-link me-2"></i> GHÉP BÀN
                    </a>
                </div>

                <div class="d-flex gap-2">
                    <button type="button" class="btn btn-outline-danger flex-fill py-3" onclick="handleUnmergeTableClick()" id="unmergeTableBtn" style="display:none;">
                        <i class="fas fa-unlink me-2"></i> HỦY GHÉP
                    </button>
                    <button type="button" class="btn btn-outline-danger flex-fill py-3" onclick="handleSplitTableClick()">
                        <i class="fas fa-cut me-2"></i> TÁCH BÀN
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Chọn bàn đích -->
<div class="modal-backdrop" id="modalSelectTarget">
    <div class="modal modal-premium" style="max-width: 450px;">
        <div class="modal-header">
            <h3 id="targetModalTitle" class="playfair">Chọn bàn đích</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form id="targetForm" method="POST" class="modal-body">
            <input type="hidden" name="source_table_id" id="sourceTableId">
            <p id="targetModalDesc" class="small text-muted mb-3">Chọn bàn để thực hiện thao tác này.</p>

            <div class="form-group mb-4">
                <label class="form-label">Danh sách bàn</label>
                <select name="target_id" id="targetSelect" class="form-control" required>
                    <option value="">-- Chọn bàn --</option>
                    <?php foreach ($grouped as $area => $tables): ?>
                        <optgroup label="<?= e($area) ?>" data-area="<?= e($area) ?>">
                            <?php foreach ($tables as $t): ?>
                                <?php if ($t['status'] === 'available' && empty($t['parent_id'])): ?>
                                    <option value="<?= $t['id'] ?>"><?= e($t['name']) ?> (<?= $t['capacity'] ?> ghế)</option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" id="targetSubmitBtn" class="btn btn-gold py-3 fw-bold">XÁC NHẬN</button>
                <button type="button" class="btn btn-ghost" data-modal-close>HỦY</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Ghép Khu Vực -->
<div class="modal-backdrop" id="modalMergeArea">
    <div class="modal modal-premium" style="max-width: 450px;">
        <div class="modal-header">
            <h3 class="playfair">Ghép Khu Vực</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/tables/merge_areas" class="modal-body">
            <p class="small text-muted mb-3">Chọn các khu vực muốn ghép chung (tối thiểu 2).</p>

            <div class="form-group mb-5">
                <label class="form-label text-muted d-block mb-3 border-bottom pb-2">CÁC KHU VỰC:</label>

                <?php if (!empty($abcAreas)): ?>
                    <div class="mb-4">
                        <div class="area-group-title"><i class="fas fa-layer-group me-2"></i> Sảnh Thường (A, B, C)</div>
                        <div class="guest-selector-grid" style="grid-template-columns: repeat(3, 1fr);">
                            <?php foreach ($abcAreas as $a): ?>
                                <label class="area-option guest-option">
                                    <input type="checkbox" name="areas[]" value="<?= e($a) ?>">
                                    <span><?= e($a) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($vipAreas)): ?>
                    <div class="mb-4">
                        <div class="area-group-title"><i class="fas fa-crown me-2"></i> Khu VIP</div>
                        <div class="guest-selector-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <?php foreach ($vipAreas as $a): ?>
                                <label class="area-option guest-option">
                                    <input type="checkbox" name="areas[]" value="<?= e($a) ?>">
                                    <span><?= e($a) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($auAreas)): ?>
                    <div class="mb-4">
                        <div class="area-group-title"><i class="fas fa-wine-glass me-2"></i> Khu Âu</div>
                        <div class="guest-selector-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <?php foreach ($auAreas as $a): ?>
                                <label class="area-option guest-option">
                                    <input type="checkbox" name="areas[]" value="<?= e($a) ?>">
                                    <span><?= e($a) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <?php if (!empty($otherModalAreas)): ?>
                    <div class="mb-4">
                        <div class="area-group-title"><i class="fas fa-ellipsis-h me-2"></i> Khác</div>
                        <div class="guest-selector-grid" style="grid-template-columns: repeat(2, 1fr);">
                            <?php foreach ($otherModalAreas as $a): ?>
                                <label class="area-option guest-option">
                                    <input type="checkbox" name="areas[]" value="<?= e($a) ?>">
                                    <span><?= e($a) ?></span>
                                </label>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-gold py-3 fw-bold">XÁC NHẬN GHÉP KHU</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal: Tách Khu Vực -->
<div class="modal-backdrop" id="modalUnmergeArea">
    <div class="modal modal-premium" style="max-width: 500px;">
        <div class="modal-header">
            <h3 class="playfair text-danger"><i class="fas fa-object-ungroup me-2"></i> Tách Khu Vực</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form method="POST" action="<?= BASE_URL ?>/tables/unmerge_areas" class="modal-body">
            <p class="small text-muted mb-3">Khu vực <span class="badge bg-danger">Màu đỏ</span> là đang ghép. Bấm chọn để đổi sang <span class="badge bg-warning text-dark">Màu vàng</span> để tách.</p>

            <div class="form-group mb-4">
                <div class="area-selector-interactive">
                    <?php 
                    // Logic xác định khu nào đang được ghép (có bàn là con hoặc cha của bàn ở khu khác)
                    $allTablesFlat = $tableModel->getAll();
                    $areaConnections = []; // area_name => is_merged (bool)
                    
                    // Simple heuristic: an area is "merged" if it contains ANY table that has a parent_id
                    // OR if it contains ANY table that is a parent of a table in ANOTHER area.
                    foreach ($allTablesFlat as $tbl) {
                        $area = $tbl['area'];
                        if (!empty($tbl['parent_id'])) {
                            $areaConnections[$area] = true;
                            // Also mark the parent's area
                            foreach ($allTablesFlat as $p) {
                                if ($p['id'] == $tbl['parent_id'] && $p['area'] !== $area) {
                                    $areaConnections[$p['area']] = true;
                                }
                            }
                        }
                    }
                    
                    $displayAreas = [
                        'Sảnh Thường' => $abcAreas,
                        'Khu VIP' => $vipAreas,
                        'Khu Âu' => $auAreas,
                        'Khác' => $otherModalAreas
                    ];
                    
                    foreach ($displayAreas as $groupName => $areas): if (empty($areas)) continue; ?>
                        <div class="mb-3">
                            <div class="area-group-title small fw-bold text-muted mb-2"><?= e($groupName) ?></div>
                            <div class="interactive-grid">
                                <?php foreach ($areas as $a): 
                                    $isMerged = !empty($areaConnections[$a]);
                                ?>
                                    <label class="interactive-area-item <?= $isMerged ? 'is-merged' : '' ?>">
                                        <input type="checkbox" name="unmerge_areas[]" value="<?= e($a) ?>" class="d-none">
                                        <div class="area-box">
                                            <div class="area-box-name"><?= e($a) ?></div>
                                            <div class="area-box-status"><?= $isMerged ? 'ĐANG GHÉP' : 'ĐỘC LẬP' ?></div>
                                        </div>
                                    </label>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <div class="d-grid gap-2">
                <button type="submit" class="btn fw-bold text-white bg-danger py-3">XÁC NHẬN TÁCH CÁC KHU ĐÃ CHỌN</button>
            </div>
        </form>
    </div>
</div>

<style>
    .interactive-grid {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 10px;
    }
    .interactive-area-item {
        cursor: pointer;
    }
    .area-box {
        background: #f1f5f9;
        border: 2px solid #e2e8f0;
        border-radius: 12px;
        padding: 12px 8px;
        text-align: center;
        transition: all 0.2s ease;
    }
    .area-box-name {
        font-weight: 800;
        font-size: 1rem;
        color: var(--text);
    }
    .area-box-status {
        font-size: 0.55rem;
        font-weight: 700;
        color: var(--text-muted);
        margin-top: 4px;
    }
    
    /* Khu vực đang ghép hiển thị màu đỏ */
    .interactive-area-item.is-merged .area-box {
        background: #fee2e2;
        border-color: #fca5a5;
    }
    .interactive-area-item.is-merged .area-box-name { color: #b91c1c; }
    .interactive-area-item.is-merged .area-box-status { color: #ef4444; }
    
    /* Khi được chọn để tách hiển thị màu vàng */
    .interactive-area-item input:checked + .area-box {
        background: #fef3c7 !important;
        border-color: #f59e0b !important;
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(245, 158, 11, 0.2);
    }
    .interactive-area-item input:checked + .area-box .area-box-name { color: #92400e !important; }
    .interactive-area-item input:checked + .area-box .area-box-status { color: #d97706 !important; }
</style>

<!-- Link CSS -->
<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/tables.css">

<script>
let currentSelectedTable = null;

document.addEventListener('DOMContentLoaded', function() {
    window.handleTableClick = function(table) {
        currentSelectedTable = table;
        if (table.status === 'occupied') {
            document.getElementById('occupiedTableName').textContent = table.name;
            document.getElementById('viewOrderBtn').href = '<?= BASE_URL ?>/orders?table_id=' + table.id;
            
            // Hiện nút Hủy ghép nếu là bàn con
            const unmergeBtn = document.getElementById('unmergeTableBtn');
            if (unmergeBtn) {
                unmergeBtn.style.display = table.parent_id ? 'block' : 'none';
            }
            
            Aurora.openModal('modalOccupied');
        } else {
            document.getElementById('modalTableName').textContent = table.name;
            document.getElementById('openTableId').value = table.id;
            Aurora.openModal('modalOpenTable');
        }
    };

    window.handleTransferClick = function() {
        Aurora.closeModal('modalOccupied');
        document.getElementById('targetModalTitle').textContent = 'Chuyển bàn: ' + currentSelectedTable.name;
        document.getElementById('targetModalDesc').textContent = 'Chọn bàn trống để chuyển khách sang.';
        document.getElementById('sourceTableId').value = currentSelectedTable.id;
        document.getElementById('targetForm').action = '<?= BASE_URL ?>/tables/transfer';
        document.getElementById('targetSubmitBtn').textContent = 'CHUYỂN BÀN';
        Aurora.openModal('modalSelectTarget');
    };

    window.handleMergeTableClick = function() {
        Aurora.closeModal('modalOccupied');
        document.getElementById('targetModalTitle').textContent = 'Ghép bàn vào: ' + currentSelectedTable.name;
        document.getElementById('targetModalDesc').textContent = 'Chọn bàn trống để ghép chung với bàn này.';
        document.getElementById('sourceTableId').value = currentSelectedTable.id;
        document.getElementById('targetForm').action = '<?= BASE_URL ?>/tables/merge';
        
        // Cần đổi tên input trong form hoặc xử lý route ghép bàn (thường là POST child_id, parent_id)
        // Trong TableController->merge: child_id = input('child_id'), parent_id = input('parent_id')
        const form = document.getElementById('targetForm');
        form.innerHTML = `
            <input type="hidden" name="parent_id" value="${currentSelectedTable.id}">
            <p class="small text-muted mb-3">Chọn bàn trống để ghép vào <strong>${currentSelectedTable.name}</strong>.</p>
            <div class="form-group mb-4">
                <label class="form-label">Chọn bàn con</label>
                <select name="child_id" class="form-control" required>
                    <option value="">-- Chọn bàn --</option>
                    <?php foreach ($grouped as $area => $tables): ?>
                        <optgroup label="<?= e($area) ?>">
                            <?php foreach ($tables as $t): ?>
                                <?php if ($t['status'] === 'available' && empty($t['parent_id'])): ?>
                                    <option value="<?= $t['id'] ?>"><?= e($t['name']) ?></option>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </optgroup>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-gold py-3 fw-bold">GHÉP BÀN</button>
                <button type="button" class="btn btn-ghost" data-modal-close>HỦY</button>
            </div>
        `;
        Aurora.openModal('modalSelectTarget');
    };

    window.handleUnmergeTableClick = function() {
        if (!confirm('Bạn có chắc muốn tách bàn này ra khỏi nhóm ghép?')) return;
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = '<?= BASE_URL ?>/tables/unmerge';
        f.innerHTML = `<input type="hidden" name="table_id" value="${currentSelectedTable.id}">`;
        document.body.appendChild(f);
        f.submit();
    };

    window.handleSplitTableClick = function() {
        // Tách bàn (Split Items) -> Chuyển sang trang order để chọn món cho chính xác
        window.location.href = '<?= BASE_URL ?>/orders?table_id=' + currentSelectedTable.id + '&action=split';
    };
});
</script>
