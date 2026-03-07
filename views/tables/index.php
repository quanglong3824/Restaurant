<?php
// views/tables/index.php — Professional Floor Plan
$availableCount = $counts['available'] ?? 0;
$occupiedCount = $counts['occupied'] ?? 0;

$allAreas = array_keys($grouped);
sort($allAreas);
?>

<div class="page-content" style="padding-bottom: 2rem;">
    <!-- Summary Header - Luxury Floating Cards -->
    <div class="summary-shelf d-flex gap-3 mb-5">
        <div class="summary-box glass occupied">
            <div class="box-icon"><i class="fas fa-utensils"></i></div>
            <div class="box-info">
                <div class="val"><?= $occupiedCount ?></div>
                <div class="lbl">ĐANG CÓ KHÁCH</div>
            </div>
        </div>
        <div class="summary-box glass available">
            <div class="box-icon"><i class="fas fa-door-open"></i></div>
            <div class="box-info">
                <div class="val"><?= $availableCount ?></div>
                <div class="lbl">BÀN ĐANG TRỐNG</div>
            </div>
        </div>
    </div>

    <?php if (empty($grouped)): ?>
        <div class="empty-state py-5 text-center">
            <i class="fas fa-table-cells-large fa-3x mb-3 opacity-20"></i>
            <h4 class="fw-bold">Chưa thiết lập sơ đồ bàn</h4>
            <p class="text-muted small">Vui lòng quản lý bàn trong bảng điều khiển Admin.</p>
        </div>
    <?php else: ?>
        <?php foreach ($allAreas as $area): ?>
            <div class="area-section mb-5">
                <h2 class="area-title playfair mb-4">
                    <span class="title-accent"></span>
                    <i class="fas fa-map-marked-alt text-gold me-2"></i> Khu vực: <?= e($area) ?>
                </h2>

                <div class="table-grid">
                    <?php foreach ($grouped[$area] as $t):
                        $isOccupied = ($t['status'] === 'occupied');
                        $isChild = !empty($t['parent_id']);
                        $cardClass = $isOccupied ? 'occupied' : 'available';
                        if ($isChild)
                            $cardClass .= ' merged-child';
                        ?>
                        <div class="table-token <?= $cardClass ?>"
                            onclick="handleTableClick(<?= htmlspecialchars(json_encode($t)) ?>)" role="button">

                            <div class="token-status-ring"></div>

                            <div class="token-body">
                                <div class="token-name"><?= e($t['name']) ?></div>
                                <div class="token-meta">
                                    <?php if ($isChild): ?>
                                        <i class="fas fa-link"></i> Ghép → <?= e($t['parent_name'] ?? 'P') ?>
                                    <?php else: ?>
                                        <?php
                                        $mergedChildren = $tableModel->getMergedTables($t['id']);
                                        if (!empty($mergedChildren)):
                                            ?>
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
                    <?php endforeach; ?>
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
                <label class="form-label text-gold small fw-bold text-uppercase">Số lượng khách hàng</label>
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
    <div class="modal modal-premium" style="max-width: 400px;">
        <div class="modal-header">
            <h3 id="occupiedTableName" class="playfair">Bàn đang phục vụ</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <div class="modal-body py-4">
            <div id="childTableInfo" class="alert-premium mb-4" style="display:none;">
                <div class="d-flex align-items-center gap-3">
                    <i class="fas fa-link text-gold"></i>
                    <div>
                        <div class="small fw-bold">BÀN ĐANG GHÉP</div>
                        <div class="tiny text-muted">Đang theo <span id="parentNameLabel" class="text-white"></span>
                        </div>
                    </div>
                    <form method="POST" action="<?= BASE_URL ?>/tables/unmerge" class="ms-auto">
                        <input type="hidden" name="table_id" id="unmergeTableId">
                        <button type="submit"
                            class="btn btn-sm btn-ghost p-1 px-2 border-danger text-danger">TÁCH</button>
                    </form>
                </div>
            </div>

            <div class="d-grid gap-3">
                <a id="viewOrderBtn" href="#" class="btn btn-gold py-3 shadow-lg">
                    <i class="fas fa-file-invoice-dollar me-2"></i> XEM CHI TIẾT & GỌI MÓN
                </a>
                <div class="row g-2">
                    <div class="col-6">
                        <button type="button" id="btnTransfer" class="btn btn-ghost w-100 py-3">
                            <i class="fas fa-exchange-alt mb-1 d-block"></i> <span class="small">CHUYỂN</span>
                        </button>
                    </div>
                    <div class="col-6">
                        <button type="button" id="btnMerge" class="btn btn-ghost w-100 py-3">
                            <i class="fas fa-link mb-1 d-block"></i> <span class="small">GHÉP</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal: Ghép/Chuyển Bàn (Target Selection) -->
<div class="modal-backdrop" id="modalSelectTarget">
    <div class="modal modal-premium" style="max-width: 450px;">
        <div class="modal-header">
            <h3 id="targetModalTitle" class="playfair">Chọn bàn đích</h3>
            <button class="modal-close" data-modal-close type="button"><i class="fas fa-times"></i></button>
        </div>
        <form id="targetForm" method="POST" class="modal-body">
            <input type="hidden" name="source_table_id" id="sourceTableId">
            <p id="targetModalDesc" class="small text-muted mb-3">Vui lòng chọn một bàn trống để thực hiện thao tác này.
            </p>

            <div class="form-group mb-4">
                <label class="form-label">Danh sách bàn khả dụng</label>
                <select name="target_id" id="targetSelect" class="form-control" required>
                    <option value="">-- Chọn bàn --</option>
                    <?php foreach ($grouped as $area => $tables): ?>
                        <optgroup label="<?= e($area) ?>">
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

<style>
    /* Luxury Floor Plan Styles */
    .summary-shelf {
        margin-inline: -0.25rem;
    }

    .summary-box {
        flex: 1;
        background: white;
        padding: 1.25rem;
        border-radius: var(--radius);
        display: flex;
        align-items: center;
        gap: 1rem;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.03);
        border: 1px solid var(--border);
    }

    .summary-box .box-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.25rem;
    }

    .summary-box.available .box-icon {
        background: #ecfdf5;
        color: var(--success);
    }

    .summary-box.occupied .box-icon {
        background: #fef2f2;
        color: var(--danger);
    }

    .summary-box .val {
        font-size: 1.5rem;
        font-weight: 800;
        color: var(--text);
        line-height: 1;
    }

    .summary-box .lbl {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-dim);
        margin-top: 2px;
    }

    .area-title {
        font-size: 1.5rem;
        font-weight: 700;
        position: relative;
        padding-left: 1.5rem;
    }

    .title-accent {
        position: absolute;
        left: 0;
        top: 0.5rem;
        bottom: 0.5rem;
        width: 4px;
        border-radius: 2px;
        background: var(--gold);
    }

    .table-token {
        background: white;
        border-radius: var(--radius);
        height: 110px;
        position: relative;
        border: 1px solid var(--border);
        transition: all 0.3s;
        cursor: pointer;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        padding: 1.25rem;
        justify-content: space-between;
    }

    .table-token:active {
        transform: scale(0.96);
    }

    .table-token.available {
        border-top: 4px solid var(--success);
    }

    .table-token.occupied {
        border-top: 4px solid var(--danger);
        background: #fff;
    }

    .table-token.merged-child {
        border-top-color: var(--gold);
        border-top-style: dashed;
    }

    .table-token .token-name {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text);
    }

    .table-token .token-meta {
        font-size: 0.65rem;
        font-weight: 700;
        color: var(--text-dim);
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .table-token .token-icon {
        position: absolute;
        bottom: -10px;
        right: -10px;
        font-size: 3rem;
        opacity: 0.05;
        transform: rotate(-15deg);
    }

    .table-token.occupied .token-icon {
        opacity: 0.05;
        color: var(--danger);
    }

    .master-badge {
        background: var(--gold);
        color: white;
        padding: 1px 4px;
        border-radius: 4px;
        font-size: 0.6rem;
    }

    .guest-selector-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 0.75rem;
    }

    .guest-option input {
        position: absolute;
        opacity: 0;
        width: 0;
        height: 0;
    }

    .guest-option span {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 48px;
        background: #f1f5f9;
        border-radius: var(--radius-sm);
        font-weight: 800;
        cursor: pointer;
        transition: all 0.2s;
    }

    .guest-option input:checked+span {
        background: var(--gold);
        color: white;
        transform: scale(1.05);
    }

    .alert-premium {
        background: rgba(184, 155, 94, 0.08);
        border: 1px solid var(--border-gold);
        border-radius: var(--radius);
        padding: 1rem;
    }

    .tiny {
        font-size: 0.65rem;
    }
</style>

<script>
    function handleTableClick(table) {
        if (table.status === 'occupied') {
            document.getElementById('occupiedTableName').textContent = table.name;
            const childInfo = document.getElementById('childTableInfo');
            const viewOrderBtn = document.getElementById('viewOrderBtn');
            const btnTransfer = document.getElementById('btnTransfer');
            const btnMerge = document.getElementById('btnMerge');

            if (table.parent_id) {
                childInfo.style.display = 'block';
                document.getElementById('parentNameLabel').textContent = table.parent_name;
                document.getElementById('unmergeTableId').value = table.id;
                viewOrderBtn.href = '<?= BASE_URL ?>/orders?table_id=' + table.parent_id;
                btnTransfer.parentElement.style.display = 'none';
                btnMerge.parentElement.style.display = 'none';
            } else {
                childInfo.style.display = 'none';
                viewOrderBtn.href = '<?= BASE_URL ?>/orders?table_id=' + table.id;
                btnTransfer.parentElement.style.display = 'block';
                btnMerge.parentElement.style.display = 'block';
            }

            btnTransfer.onclick = () => { Aurora.closeModal('modalOccupied'); openTargetModal('transfer', table); };
            btnMerge.onclick = () => { Aurora.closeModal('modalOccupied'); openTargetModal('merge', table); };

            Aurora.openModal('modalOccupied');
        } else {
            document.getElementById('modalTableName').textContent = table.name;
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
        targetSelect.name = type === 'transfer' ? 'to_table_id' : 'child_id';
        if (type === 'transfer') {
            sourceInput.name = 'from_table_id';
            form.action = '<?= BASE_URL ?>/tables/transfer';
            title.innerHTML = 'Chuyển bàn';
            submitBtn.textContent = 'CHUYỂN SANG ' + sourceTable.name;
        } else {
            sourceInput.name = 'parent_id';
            form.action = '<?= BASE_URL ?>/tables/merge';
            title.innerHTML = 'Ghép thêm bàn';
            submitBtn.textContent = 'GHÉP VỚI ' + sourceTable.name;
        }
        Aurora.openModal('modalSelectTarget');
    }
</script>