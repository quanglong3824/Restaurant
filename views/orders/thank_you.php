<link rel="stylesheet" href="<?= BASE_URL ?>/public/css/orders/thank_you.css">

<div class="thank-you-container">
    
    <div class="thank-you-card">
        <div class="success-icon-wrap">
            <i class="fas fa-check"></i>
        </div>
        
        <h2 class="playfair">Thanh toán thành công!</h2>
        <p>
            Cảm ơn quý khách đã dùng bữa tại Aurora. Chúc quý khách một ngày tuyệt vời và hẹn gặp lại!
        </p>
        
        <hr class="divider-dashed">
        
        <h4 class="rating-title">Đánh giá trải nghiệm của bạn</h4>
        
        <div class="star-rating" id="starRating">
            <i class="fas fa-star" onclick="rate(1)"></i>
            <i class="fas fa-star" onclick="rate(2)"></i>
            <i class="fas fa-star" onclick="rate(3)"></i>
            <i class="fas fa-star" onclick="rate(4)"></i>
            <i class="fas fa-star" onclick="rate(5)"></i>
        </div>
        
        <p id="ratingMessage" class="rating-message"></p>

        <button onclick="window.location.href='<?= BASE_URL ?>/'" class="btn-gold">
            Trở về Trang chủ
        </button>
    </div>
</div>

<script src="<?= BASE_URL ?>/public/js/orders/thank_you.js"></script>
