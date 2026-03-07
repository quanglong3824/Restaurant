<!-- views/orders/history.php -->
<div class="page-content" style="padding-bottom: 3rem;">

    <!-- Filter Section - Luxury Glassmorphism -->
    <div class="card mb-4" style="background: var(--surface); border: 1px solid var(--border-gold); padding: 1.5rem;">
        <div class="card-body p-0">
            <form action="<?= BASE_URL ?>/orders/history" method="GET" id="historyFilterForm">
                <div class="d-flex flex-column gap-4">
                    <!-- Type Selector -->
                    <div class="filter-type-pill d-flex p-1 bg-light rounded-pill"
                        style="background: #f1f5f9 !important;">
                        <button type="button" class="btn btn-pill <?= $filters['type'] === 'date' ? 'active' : '' ?>"
                            onclick="setFilter('date')">Theo Ngày</button>
                        <button type="button" class="btn btn-pill <?= $filters['type'] === 'week' ? 'active' : '' ?>"
                            onclick="setFilter('week')">Theo Tuần</button>
                        <button type="button" class="btn btn-pill <?= $filters['type'] === 'month' ? 'active' : '' ?>"
                            onclick="setFilter('month')">Theo Tháng</button>
                    </div>
                    <input type="hidden" name="filter_type" id="filter_type" value="<?= e($filters['type']) ?>">

                    <div class="row g-3 align-items-end">
                        <?php if ($filters['type'] === 'date'): ?>
                            <div class="col-12">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Chọn ngày báo
                                    cáo</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white border-end-0"><i
                                            class="fas fa-calendar-alt text-gold"></i></span>
                                    <input type="date" name="date" class="form-control border-start-0 ps-0"
                                        value="<?= e($filters['date']) ?>" onchange="this.form.submit()">
                                </div>
                            </div>
                        <?php elseif ($filters['type'] === 'week'): ?>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Số tuần</label>
                                <input type="number" name="week" class="form-control" min="1" max="53"
                                    value="<?= e($filters['week']) ?>">
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Năm</label>
                                <input type="number" name="year" class="form-control" value="<?= e($filters['year']) ?>">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold w-100 mt-2 shadow-sm">XÁC NHẬN LỌC TUẦN</button>
                            </div>
                        <?php elseif ($filters['type'] === 'month'): ?>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Chọn tháng</label>
                                <select name="month" class="form-control">
                                    <?php for ($m = 1; $m <= 12; $m++): ?>
                                        <option value="<?= $m ?>" <?= (int) $filters['month'] === $m ? 'selected' : '' ?>>Tháng
                                            <?= $m ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <div class="col-6">
                                <label class="form-label small fw-bold text-muted text-uppercase mb-2">Năm</label>
                                <input type="number" name="year" class="form-control" value="<?= e($filters['year']) ?>">
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-gold w-100 mt-2 shadow-sm">XÁC NHẬN LỌC THÁNG</button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Spotlight -->
    <?php
    $totalRevenue = 0;
    foreach ($orders as $o)
        $totalRevenue += (float) $o['total'];
    ?>
    <div class="summary-spotlight mb-5">
        <div class="spotlight-bg"></div>
        <div class="d-flex justify-content-between align-items-center position-relative">
            <div class="spotlight-info">
                <span class="spotlight-label">TỔNG DOANH THU TRONG KỲ</span>
                <div class="spotlight-val"><?= formatPrice($totalRevenue) ?></div>
            </div>
            <div class="spotlight-stats text-end">
                <div class="stat-bubble">
                    <span class="val"><?= count($orders) ?></span>
                    <span class="lbl">HÓA ĐƠN</span>
                </div>
            </div>
        </div>
    </div>

    <!-- History Stream -->
    <div class="history-stream">
        <div class="stream-title mb-3 d-flex align-items-center gap-2">
            <i class="fas fa-history text-gold"></i>
            <h5 class="mb-0 fw-800 playfair">Lịch sử hóa đơn</h5>
        </div>

        <?php if (empty($orders)): ?>
            <div class="card text-center py-5 border-dashed">
                <div class="opacity-30 mb-3">
                    <i class="fas fa-receipt fa-4x"></i>
                </div>
                <h5 class="fw-bold">Chưa có dữ liệu</h5>
                <p class="text-muted small">Không tìm thấy giao dịch nào trong khoảng thời gian này.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($orders as $order): ?>
                    <div class="history-card">
                        <div class="card-main">
                            <div class="table-tag">
                                <span class="table-num"><?= e(str_replace('Bàn ', '', $order['table_name'])) ?></span>
                            </div>
                            <div class="order-info">
                                <div class="top-row">
                                    <h6 class="table-name"><?= e($order['table_name']) ?></h6>
                                    <span class="order-time"><?= date('H:i d/m', strtotime($order['closed_at'])) ?></span>
                                </div>
                                <div class="mid-row">
                                    <span class="waiter-name"><i class="far fa-user me-1"></i>
                                        <?= e($order['waiter_name'] ?? 'Hệ thống') ?></span>
                                    <span class="pay-method"><i class="fas fa-wallet me-1"></i>
                                        <?= strtoupper(e($order['payment_method'])) ?></span>
                                </div>
                            </div>
                            <div class="order-amount">
                                <div class="amount-val"><?= formatPrice($order['total']) ?></div>
                            </div>
                        </div>
                        <div class="card-footer-actions">
                            <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank"
                                class="action-btn">
                                <i class="fas fa-print me-1"></i> XEM CHI TIẾT & IN LẠI
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Luxury History View Styles */
    .fw-800 {
        font-weight: 800;
    }

    .filter-type-pill .btn-pill {
        flex: 1;
        border: none;
        background: transparent;
        color: var(--text-muted);
        font-weight: 700;
        font-size: 0.8rem;
        padding: 0.6rem;
        border-radius: 999px;
        transition: all 0.3s;
    }

    .filter-type-pill .btn-pill.active {
        background: white;
        color: var(--gold-dark);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
    }

    .summary-spotlight {
        position: relative;
        background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
        border-radius: var(--radius-lg);
        padding: 2rem;
        color: white;
        overflow: hidden;
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.2);
    }

    .spotlight-bg {
        position: absolute;
        top: -50%;
        right: -20%;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, var(--gold) 0%, transparent 70%);
        opacity: 0.15;
        filter: blur(40px);
    }

    .spotlight-label {
        font-size: 0.7rem;
        font-weight: 800;
        letter-spacing: 2px;
        color: var(--gold);
        display: block;
        margin-bottom: 0.5rem;
    }

    .spotlight-val {
        font-size: 2.25rem;
        font-weight: 800;
        font-family: 'Outfit', sans-serif;
        letter-spacing: -1px;
    }

    .stat-bubble {
        background: rgba(255, 255, 255, 0.05);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 0.75rem 1.25rem;
        border-radius: var(--radius);
        display: inline-flex;
        flex-direction: column;
        align-items: center;
    }

    .stat-bubble .val {
        font-size: 1.5rem;
        font-weight: 800;
    }

    .stat-bubble .lbl {
        font-size: 0.6rem;
        font-weight: 700;
        opacity: 0.6;
    }

    .history-card {
        background: white;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        overflow: hidden;
        transition: all 0.3s;
    }

    .history-card:hover {
        border-color: var(--gold);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
        transform: translateY(-2px);
    }

    .card-main {
        padding: 1.25rem;
        display: flex;
        align-items: center;
        gap: 1.25rem;
    }

    .table-tag {
        width: 48px;
        height: 48px;
        background: var(--gold-light);
        border: 1px solid var(--border-gold);
        border-radius: var(--radius-sm);
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }

    .table-num {
        font-size: 1.25rem;
        font-weight: 800;
        color: var(--gold-dark);
    }

    .order-info {
        flex: 1;
    }

    .top-row {
        display: flex;
        justify-content: space-between;
        align-items: baseline;
        margin-bottom: 0.25rem;
    }

    .table-name {
        margin: 0;
        font-weight: 700;
        font-size: 1rem;
        color: var(--text);
    }

    .order-time {
        font-size: 0.75rem;
        color: var(--text-dim);
        font-weight: 600;
    }

    .mid-row {
        display: flex;
        gap: 1rem;
        font-size: 0.78rem;
        color: var(--text-muted);
    }

    .amount-val {
        font-size: 1.15rem;
        font-weight: 800;
        color: var(--text);
    }

    .card-footer-actions {
        background: #f8fafc;
        border-top: 1px solid var(--border);
        padding: 0.75rem 1.25rem;
    }

    .action-btn {
        text-decoration: none;
        color: var(--gold-dark);
        font-size: 0.75rem;
        font-weight: 800;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: opacity 0.2s;
    }

    .action-btn:hover {
        opacity: 0.7;
    }

    .border-dashed {
        border-style: dashed !important;
        opacity: 0.6;
    }
</style>

<script>
    function setFilter(type) {
        document.getElementById('filter_type').value = type;
        document.getElementById('historyFilterForm').submit();
    }
</script>