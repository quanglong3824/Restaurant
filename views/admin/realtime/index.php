<?php
// views/admin/realtime/index.php — Professional Light POS-Style Monitoring
?>

<div class="pos-monitor light-theme">
    <!-- Top Command Bar -->
    <div class="command-bar">
        <div class="brand-unit">
            <span class="unit-code">SYSTEM MONITOR</span>
            <h1 class="unit-name">ĐIỀU HÀNH TRỰC TIẾP</h1>
        </div>
        
        <div class="system-stats">
            <div class="stat-item">
                <span class="label">ĐANG PHỤC VỤ</span>
                <span class="value highlight" id="statOccupied"><?= $counts['occupied'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">BÀN TRỐNG</span>
                <span class="value" id="statAvailable"><?= $counts['available'] ?></span>
            </div>
            <div class="stat-divider"></div>
            <div class="stat-item">
                <span class="label">DOANH THU TẠM TÍNH</span>
                <span class="value gold" id="statTempRevenue">...</span>
            </div>
        </div>

        <div class="sync-box">
            <div class="sync-timer">
                <span id="reloadCount">8</span>s
            </div>
            <button onclick="refreshData()" class="refresh-circle-btn">
                <i class="fas fa-sync-alt"></i>
            </button>
        </div>
    </div>

    <!-- Monitoring Grid -->
    <div id="realtimeListContainer" class="pos-grid">
        <!-- Loader -->
        <div class="pos-loader">
            <div class="spinner-border spinner-border-sm text-primary"></div>
            <span>Đang cập nhật dữ liệu từ các bàn...</span>
        </div>
    </div>
</div>


<!-- External JS: public/js/admin/realtime.js sẽ tự động load qua layout -->
