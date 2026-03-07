<!-- views/orders/history.php -->
<div class="page-content" style="padding-bottom: 2rem;">
    
    <!-- Filter Section -->
    <div class="card shadow-sm mb-4" style="border-radius: 12px; border: none; overflow: hidden; background: #fff;">
        <div class="card-body p-3">
            <form action="<?= BASE_URL ?>/orders/history" method="GET" id="historyFilterForm">
                <div class="row g-3">
                    <div class="col-12">
                        <div class="d-flex gap-2 mb-3">
                            <button type="button" class="btn btn-sm <?= $filters['type'] === 'date' ? 'btn-gold' : 'btn-ghost' ?> flex-fill" onclick="setFilter('date')">Ngày</button>
                            <button type="button" class="btn btn-sm <?= $filters['type'] === 'week' ? 'btn-gold' : 'btn-ghost' ?> flex-fill" onclick="setFilter('week')">Tuần</button>
                            <button type="button" class="btn btn-sm <?= $filters['type'] === 'month' ? 'btn-gold' : 'btn-ghost' ?> flex-fill" onclick="setFilter('month')">Tháng</button>
                        </div>
                        <input type="hidden" name="filter_type" id="filter_type" value="<?= e($filters['type']) ?>">
                    </div>

                    <?php if ($filters['type'] === 'date'): ?>
                        <div class="col-12">
                            <label class="form-label small fw-bold">Chọn ngày</label>
                            <input type="date" name="date" class="form-control" value="<?= e($filters['date']) ?>" onchange="this.form.submit()">
                        </div>
                    <?php elseif ($filters['type'] === 'week'): ?>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tuần (1-53)</label>
                            <input type="number" name="week" class="form-control" min="1" max="53" value="<?= e($filters['week']) ?>">
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Năm</label>
                            <input type="number" name="year" class="form-control" value="<?= e($filters['year']) ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-gold w-100 mt-2">Áp dụng lọc tuần</button>
                        </div>
                    <?php elseif ($filters['type'] === 'month'): ?>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Tháng</label>
                            <select name="month" class="form-control">
                                <?php for($m=1; $m<=12; $m++): ?>
                                    <option value="<?= $m ?>" <?= (int)$filters['month'] === $m ? 'selected' : '' ?>>Tháng <?= $m ?></option>
                                <?php endfor; ?>
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">Năm</label>
                            <input type="number" name="year" class="form-control" value="<?= e($filters['year']) ?>">
                        </div>
                        <div class="col-12">
                            <button type="submit" class="btn btn-gold w-100 mt-2">Áp dụng lọc tháng</button>
                        </div>
                    <?php endif; ?>
                </div>
            </form>
        </div>
    </div>

    <!-- Summary Bar -->
    <?php
    $totalRevenue = 0;
    foreach ($orders as $o) $totalRevenue += (float)$o['total'];
    ?>
    <div class="order-total-bar mb-4" style="margin: 0 0 1.5rem; border-radius: 12px; border: none; background: linear-gradient(135deg, #1e293b, #0f172a); color: rgba(255,255,255,0.9); box-shadow: 0 8px 20px rgba(0,0,0,0.15);">
        <div class="d-flex flex-column">
            <span class="small" style="color: var(--gold); font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px;">Tổng doanh thu</span>
            <div class="h2 mb-0 fw-bold" style="color: #fff;"><?= formatPrice($totalRevenue) ?></div>
        </div>
        <div class="text-end">
            <span class="small opacity-75">Số hóa đơn</span>
            <div class="h4 mb-0 fw-bold"><?= count($orders) ?></div>
        </div>
    </div>

    <!-- Results -->
    <div class="history-results">
        <div class="section-title">Hóa đơn đã thanh toán</div>
        <?php if (empty($orders)): ?>
            <div class="empty-state">
                <i class="fas fa-search"></i>
                <h3>Không có dữ liệu</h3>
                <p>Không tìm thấy hóa đơn nào phù hợp với bộ lọc này.</p>
            </div>
        <?php else: ?>
            <div class="d-flex flex-column gap-3">
                <?php foreach ($orders as $order): ?>
                    <div class="card" style="border-radius: 0px; border: 1px solid var(--border); transition: all 0.2s;">
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <div style="width: 40px; height: 40px; border-radius: 0px; background: var(--gold-light); color: var(--gold-dark); display: flex; align-items: center; justify-content: center; font-weight: 800;">
                                        <?= e(str_replace('Bàn ', '', $order['table_name'])) ?>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold" style="font-size: 1rem;"><?= e($order['table_name']) ?></h6>
                                        <div class="small text-muted">
                                            <i class="fas fa-clock me-1"></i> <?= date('H:i d/m', strtotime($order['closed_at'])) ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="text-end">
                                    <div class="fw-bold fs-5 text-dark"><?= formatPrice($order['total']) ?></div>
                                    <div class="badge badge-gold" style="font-size: 0.65rem;">
                                        <i class="fas fa-check-circle me-1"></i> <?= strtoupper(e($order['payment_method'])) ?>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-2">
                                <span class="small text-muted"><i class="fas fa-user-circle me-1"></i> <?= e($order['waiter_name'] ?? 'Hệ thống') ?></span>
                                <a href="<?= BASE_URL ?>/orders/print?order_id=<?= $order['id'] ?>" target="_blank" class="btn btn-sm btn-ghost px-3" style="min-height: 32px; font-size: 0.75rem; border-radius: 20px;">
                                    <i class="fas fa-print me-1"></i> Xem Bill
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<script>
function setFilter(type) {
    document.getElementById('filter_type').value = type;
    document.getElementById('historyFilterForm').submit();
}
</script>