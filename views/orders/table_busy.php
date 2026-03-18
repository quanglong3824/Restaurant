<div class="container py-5 text-center">
    <div class="mb-4">
        <i class="bi bi-person-x-fill text-warning" style="font-size: 4rem;"></i>
    </div>
    <h2 class="fw-bold mb-3">Bàn này đang bận</h2>
    <p class="lead mb-4 text-muted">
        Dường như bàn <strong><?= htmlspecialchars($table['name'] ?? 'này') ?></strong> hiện đã có khách đang sử dụng.
    </p>
    
    <div class="card border-0 shadow-sm mx-auto mb-5" style="max-width: 450px; background-color: #f8f9fa;">
        <div class="card-body p-4">
            <p class="mb-3 text-start">Nếu quý khách đi cùng đoàn:</p>
            <ul class="text-start mb-0">
                <li class="mb-2">Vui lòng xem chung thực đơn với người cùng bàn.</li>
                <li class="mb-2">Hoặc quét mã QR trên thiết bị đã gọi món để tiếp tục.</li>
            </ul>
        </div>
    </div>

    <div class="d-grid gap-2 d-sm-flex justify-content-sm-center">
        <button type="button" class="btn btn-outline-secondary btn-lg px-4" onclick="location.reload()"> Thử lại</button>
        <a href="javascript:void(0)" class="btn btn-primary btn-lg px-4" onclick="window.history.back()"> Quay lại</a>
    </div>
    
    <div class="mt-5 pt-3 border-top">
        <p class="text-muted small">Nếu quý khách vừa mới đến và bàn còn trống, vui lòng liên hệ nhân viên để được hỗ trợ mở bàn mới.</p>
    </div>
</div>

<style>
.bi-person-x-fill {
    color: #ffc107;
}
</style>
