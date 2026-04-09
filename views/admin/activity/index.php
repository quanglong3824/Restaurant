<?php
// views/admin/activity/index.php — Activity Logs Dashboard
// Aurora Restaurant POS System
$levelColors = [
    'info' => 'blue',
    'notice' => 'cyan',
    'warning' => 'orange',
    'error' => 'red',
    'critical' => 'purple',
];

$actionIcons = [
    'login' => 'fa-sign-in-alt',
    'logout' => 'fa-sign-out-alt',
    'create' => 'fa-plus-circle',
    'update' => 'fa-edit',
    'delete' => 'fa-trash',
    'view' => 'fa-eye',
    'export' => 'fa-download',
    'payment' => 'fa-money-bill-wave',
    'transfer' => 'fa-exchange-alt',
    'merge' => 'fa-object-group',
    'split' => 'fa-object-ungroup',
    'scan_qr' => 'fa-qrcode',
    'error' => 'fa-exclamation-triangle',
    'warning' => 'fa-exclamation-circle',
];
?>

<style>
/* ════════════════════════════════════════════════════════════
   CSS Variables & Reset
   ════════════════════════════════════════════════════════════ */
:root {
    --primary: #6366f1;
    --primary-dark: #4f46e5;
    --secondary: #f59e0b;
    --success: #10b981;
    --danger: #ef4444;
    --info: #3b82f6;
    --purple: #8b5cf6;
    --gold: #c5a059;
    --gold-dark: #a68341;
    
    --bg-page: #f1f5f9;
    --bg-card: #ffffff;
    --bg-hover: #f8fafc;
    
    --text-primary: #1e293b;
    --text-secondary: #64748b;
    --text-muted: #94a3b8;
    
    --border: #e2e8f0;
    --border-light: #f1f5f9;
    
    --shadow-sm: 0 1px 2px rgba(0,0,0,0.05);
    --shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    --shadow-lg: 0 10px 15px -3px rgba(0,0,0,0.1);
    
    --radius: 12px;
    --radius-lg: 16px;
}

/* ════════════════════════════════════════════════════════════
   Activity Logs Page
   ════════════════════════════════════════════════════════════ */
.activity-logs-page {
    padding: 0;
}

/* Stats Cards */
.activity-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 20px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: var(--shadow);
    border-left: 4px solid var(--gold);
    transition: all 0.3s;
}

.stat-card:hover {
    transform: translateY(-4px);
    box-shadow: var(--shadow-lg);
}

.stat-card.danger { border-left-color: var(--danger); }
.stat-card.warning { border-left-color: var(--secondary); }
.stat-card.info { border-left-color: var(--info); }

.stat-card-icon {
    position: absolute;
    top: 15px;
    right: 15px;
    width: 50px;
    height: 50px;
    border-radius: var(--radius);
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 1.5rem;
    opacity: 0.1;
}

.stat-card.danger .stat-card-icon {
    background: var(--danger);
    color: #fff;
}

.stat-card.warning .stat-card-icon {
    background: var(--secondary);
    color: #fff;
}

.stat-card.info .stat-card-icon {
    background: var(--info);
    color: #fff;
}

.stat-card:not(.danger):not(.warning):not(.info) .stat-card-icon {
    background: var(--gold);
    color: #fff;
}

.stat-card-label {
    display: block;
    font-size: 0.65rem;
    font-weight: 800;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 8px;
}

.stat-card-value {
    font-size: 2rem;
    font-weight: 900;
    color: var(--text-primary);
    line-height: 1;
    margin-bottom: 4px;
}

.stat-card-sub {
    font-size: 0.75rem;
    color: var(--text-secondary);
}

/* Filter Bar */
.activity-filters {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    padding: 20px;
    margin-bottom: 24px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 6px;
}

.filter-group label {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.65rem;
    font-weight: 700;
    color: var(--text-muted);
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-group label i {
    font-size: 0.7rem;
    color: var(--primary);
}

.filter-group input,
.filter-group select {
    padding: 10px 12px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    font-size: 0.85rem;
    background: var(--bg-card);
    color: var(--text-primary);
    transition: all 0.2s;
    font-weight: 600;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: var(--primary);
    box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}

.filter-actions {
    display: flex;
    gap: 8px;
    align-items: center;
    flex-wrap: wrap;
}

.btn-filter {
    display: flex;
    align-items: center;
    gap: 6px;
    background: linear-gradient(135deg, var(--primary), var(--primary-dark));
    color: #fff;
    border: none;
    padding: 10px 16px;
    border-radius: var(--radius);
    font-size: 0.8rem;
    font-weight: 700;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.3);
}

.btn-filter:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(99, 102, 241, 0.4);
}

.btn-ghost {
    display: flex;
    align-items: center;
    gap: 6px;
    background: var(--bg-hover);
    color: var(--text-secondary);
    border: 1px solid var(--border);
    padding: 10px 14px;
    border-radius: var(--radius);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.btn-ghost:hover {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

/* Logs Table */
.activity-logs-table {
    width: 100%;
    border-collapse: collapse;
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow);
}

.activity-logs-table thead {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #fff;
}

.activity-logs-table th {
    padding: 14px 16px;
    font-size: 0.65rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.activity-logs-table th i {
    margin-right: 6px;
    opacity: 0.8;
}

.activity-logs-table td {
    padding: 14px 16px;
    border-bottom: 1px solid var(--border-light);
    font-size: 0.825rem;
    vertical-align: middle;
}

.activity-logs-table tbody tr {
    transition: all 0.2s;
}

.activity-logs-table tbody tr:hover {
    background: var(--bg-hover);
}

/* Level Badge */
.level-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    padding: 4px 10px;
    border-radius: 50px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.level-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: currentColor;
}

.level-badge.info { 
    background: #dbeafe; 
    color: #1d4ed8; 
}

.level-badge.notice { 
    background: #cffafe; 
    color: #0e7490; 
}

.level-badge.warning { 
    background: #fef3c7; 
    color: #b45309; 
}

.level-badge.error { 
    background: #fee2e2; 
    color: #b91c1c; 
}

.level-badge.critical { 
    background: #f3e8ff; 
    color: #7c3aed; 
}

/* Action Badge */
.action-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 10px;
    border-radius: 8px;
    font-size: 0.7rem;
    font-weight: 700;
    background: var(--bg-hover);
    color: var(--text-secondary);
}

.action-badge i {
    color: var(--gold);
}

/* Entity Badge */
.entity-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 4px 8px;
    border-radius: 6px;
    font-size: 0.7rem;
    background: var(--bg-hover);
    color: var(--text-secondary);
}

.entity-badge i {
    color: var(--gold);
}

.entity-badge span:last-child {
    color: var(--text-muted);
    font-family: monospace;
}

/* Metadata Toggle */
.metadata-toggle {
    cursor: pointer;
    color: var(--gold);
    font-size: 0.8rem;
    transition: transform 0.3s;
    padding: 6px;
    border-radius: 6px;
}

.metadata-toggle:hover {
    background: var(--bg-hover);
}

.metadata-toggle.expanded {
    transform: rotate(90deg);
}

.metadata-content {
    display: none;
    background: var(--bg-hover);
    border-radius: 8px;
    padding: 12px;
    margin-top: 8px;
    font-size: 0.7rem;
    font-family: 'Courier New', monospace;
    white-space: pre-wrap;
    word-break: break-all;
    color: var(--text-secondary);
    border: 1px solid var(--border);
}

.metadata-content.show {
    display: block;
}

/* Pagination */
.activity-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    margin-top: 20px;
    box-shadow: var(--shadow);
    border: 1px solid var(--border);
}

.pagination-info {
    font-size: 0.8rem;
    color: var(--text-secondary);
    font-weight: 600;
}

.pagination-buttons {
    display: flex;
    gap: 8px;
}

.pagination-buttons button {
    padding: 8px 14px;
    border: 1px solid var(--border);
    border-radius: var(--radius);
    background: var(--bg-card);
    color: var(--text-secondary);
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.pagination-buttons button:hover:not(:disabled) {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

.pagination-buttons button:disabled {
    opacity: 0.4;
    cursor: not-allowed;
}

.pagination-buttons button.active {
    background: var(--primary);
    color: #fff;
    border-color: var(--primary);
}

/* Entity Logs Modal */
.entity-logs-modal {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.6);
    backdrop-filter: blur(4px);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
    padding: 20px;
}

.entity-logs-modal.show {
    display: flex;
}

.entity-logs-card {
    background: var(--bg-card);
    border-radius: var(--radius-lg);
    max-width: 600px;
    width: 100%;
    max-height: 80vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
    box-shadow: var(--shadow-lg);
}

.entity-logs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 16px 20px;
    border-bottom: 1px solid var(--border);
    background: var(--bg-hover);
}

.entity-logs-header h3 {
    margin: 0;
    font-size: 1rem;
    font-weight: 800;
    color: var(--text-primary);
}

.entity-logs-close {
    background: var(--bg-card);
    border: 1px solid var(--border);
    width: 32px;
    height: 32px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--text-secondary);
    cursor: pointer;
    transition: all 0.2s;
}

.entity-logs-close:hover {
    background: var(--danger);
    color: #fff;
    border-color: var(--danger);
}

.entity-logs-body {
    padding: 20px;
    overflow-y: auto;
    flex: 1;
}

.entity-log-item {
    padding: 12px;
    border-bottom: 1px solid var(--border-light);
    border-radius: 8px;
    margin-bottom: 8px;
    background: var(--bg-hover);
}

.entity-log-item:last-child {
    border-bottom: none;
}

.entity-log-time {
    font-size: 0.7rem;
    color: var(--text-muted);
    margin-bottom: 4px;
}

.entity-log-action {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 0.8rem;
    font-weight: 700;
    color: var(--text-primary);
    margin-bottom: 4px;
}

.entity-log-action i {
    color: var(--gold);
}

.entity-log-user {
    font-size: 0.7rem;
    color: var(--text-secondary);
}

/* Empty State */
.empty-state {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-muted);
}

.empty-state i {
    font-size: 3rem;
    opacity: 0.3;
    display: block;
    margin-bottom: 16px;
}

.empty-state p {
    font-size: 0.95rem;
    margin: 0;
}

/* Loading State */
.activity-loading {
    text-align: center;
    padding: 48px 24px;
    color: var(--text-muted);
}

.activity-loading i {
    font-size: 2rem;
    animation: spin 1s linear infinite;
    color: var(--gold);
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Responsive */
@media (max-width: 1024px) {
    .activity-logs-table {
        font-size: 0.75rem;
    }
    
    .activity-logs-table th,
    .activity-logs-table td {
        padding: 10px 12px;
    }
}

@media (max-width: 768px) {
    .activity-stats {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .filter-actions {
        width: 100%;
        justify-content: stretch;
    }
    
    .btn-filter,
    .btn-ghost {
        flex: 1;
        justify-content: center;
    }
    
    .activity-logs-table thead {
        display: none;
    }
    
    .activity-logs-table tbody tr {
        display: block;
        border: 1px solid var(--border);
        border-radius: var(--radius);
        margin-bottom: 12px;
        padding: 12px;
    }
    
    .activity-logs-table td {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 8px 0;
        border-bottom: 1px solid var(--border-light);
    }
    
    .activity-logs-table td:last-child {
        border-bottom: none;
    }
    
    .activity-logs-table td::before {
        content: attr(data-label);
        font-weight: 700;
        color: var(--text-muted);
        font-size: 0.7rem;
        text-transform: uppercase;
    }
}

@media (max-width: 480px) {
    .activity-stats {
        grid-template-columns: 1fr;
    }
    
    .stat-card-value {
        font-size: 1.5rem;
    }
}
</style>

<div class="activity-logs-page">
    <!-- Stats Cards -->
    <div class="activity-stats">
        <div class="stat-card">
            <div class="stat-card-icon">
                <i class="fas fa-clipboard-list"></i>
            </div>
            <span class="stat-card-label">Hôm Nay</span>
            <div class="stat-card-value"><?= number_format($stats['today']['total'] ?? 0) ?></div>
            <div class="stat-card-sub">Hoạt động ghi nhận</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-card-icon">
                <i class="fas fa-exclamation-triangle"></i>
            </div>
            <span class="stat-card-label">Lỗi Hôm Nay</span>
            <div class="stat-card-value"><?= number_format($stats['today']['errors'] ?? 0) ?></div>
            <div class="stat-card-sub">Cần xem xét</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-card-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <span class="stat-card-label">Cảnh Báo Hôm Nay</span>
            <div class="stat-card-value"><?= number_format($stats['today']['warnings'] ?? 0) ?></div>
            <div class="stat-card-sub">Lưu ý</div>
        </div>
        <div class="stat-card info">
            <div class="stat-card-icon">
                <i class="fas fa-calendar-week"></i>
            </div>
            <span class="stat-card-label">7 Ngày Qua</span>
            <div class="stat-card-value"><?= number_format($stats['week']['total'] ?? 0) ?></div>
            <div class="stat-card-sub"><?= number_format($stats['week']['unique_users'] ?? 0) ?> người dùng</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="activity-filters">
        <form method="GET" action="<?= BASE_URL ?>/admin/activity" id="filterForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label>
                        <i class="fas fa-calendar-alt"></i>
                        Từ ngày
                    </label>
                    <input type="date" name="from" value="<?= e($filters['from']) ?>">
                </div>
                <div class="filter-group">
                    <label>
                        <i class="fas fa-calendar-check"></i>
                        Đến ngày
                    </label>
                    <input type="date" name="to" value="<?= e($filters['to']) ?>">
                </div>
                <div class="filter-group">
                    <label>
                        <i class="fas fa-bolt"></i>
                        Hành động
                    </label>
                    <select name="action">
                        <option value="">Tất cả</option>
                        <?php foreach ($actions as $action): ?>
                            <option value="<?= e($action) ?>" <?= $filters['action'] === $action ? 'selected' : '' ?>>
                                <?= e(ucfirst(str_replace('_', ' ', $action))) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>
                        <i class="fas fa-database"></i>
                        Thực thể
                    </label>
                    <select name="entity">
                        <option value="">Tất cả</option>
                        <option value="user" <?= $filters['entity'] === 'user' ? 'selected' : '' ?>>User</option>
                        <option value="table" <?= $filters['entity'] === 'table' ? 'selected' : '' ?>>Table</option>
                        <option value="order" <?= $filters['entity'] === 'order' ? 'selected' : '' ?>>Order</option>
                        <option value="menu_item" <?= $filters['entity'] === 'menu_item' ? 'selected' : '' ?>>Menu Item</option>
                        <option value="menu_category" <?= $filters['entity'] === 'menu_category' ? 'selected' : '' ?>>Category</option>
                        <option value="activity_logs" <?= $filters['entity'] === 'activity_logs' ? 'selected' : '' ?>>Activity Logs</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>
                        <i class="fas fa-signal"></i>
                        Mức độ
                    </label>
                    <select name="level">
                        <option value="">Tất cả</option>
                        <option value="info" <?= $filters['level'] === 'info' ? 'selected' : '' ?>>Info</option>
                        <option value="notice" <?= $filters['level'] === 'notice' ? 'selected' : '' ?>>Notice</option>
                        <option value="warning" <?= $filters['level'] === 'warning' ? 'selected' : '' ?>>Warning</option>
                        <option value="error" <?= $filters['level'] === 'error' ? 'selected' : '' ?>>Error</option>
                        <option value="critical" <?= $filters['level'] === 'critical' ? 'selected' : '' ?>>Critical</option>
                    </select>
                </div>
                <div class="filter-group">
                    <label>
                        <i class="fas fa-user"></i>
                        Người dùng
                    </label>
                    <select name="user_id">
                        <option value="">Tất cả</option>
                        <?php foreach ($users as $user): ?>
                            <option value="<?= $user['id'] ?>" <?= $filters['user_id'] == $user['id'] ? 'selected' : '' ?>>
                                <?= e($user['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-actions">
                    <button type="submit" class="btn-filter">
                        <i class="fas fa-filter"></i>
                        <span>Lọc</span>
                    </button>
                    <button type="button" class="btn-ghost" onclick="resetFilters()">
                        <i class="fas fa-redo"></i>
                    </button>
                    <button type="button" class="btn-ghost" onclick="exportLogs()">
                        <i class="fas fa-download"></i>
                        <span>Export</span>
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <table class="activity-logs-table">
        <thead>
            <tr>
                <th><i class="fas fa-clock"></i> Thời Gian</th>
                <th><i class="fas fa-bolt"></i> Hành Động</th>
                <th><i class="fas fa-database"></i> Thực Thể</th>
                <th><i class="fas fa-user"></i> Người Thực Hiện</th>
                <th><i class="fas fa-globe"></i> IP</th>
                <th><i class="fas fa-signal"></i> Mức Độ</th>
                <th><i class="fas fa-code"></i> Chi Tiết</th>
            </tr>
        </thead>
        <tbody id="logsTableBody">
            <?php if (empty($logs)): ?>
            <tr>
                <td colspan="7">
                    <div class="empty-state">
                        <i class="fas fa-inbox"></i>
                        <p>Không có bản ghi nào</p>
                    </div>
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr data-log-id="<?= $log['id'] ?>">
                    <td data-label="Thời Gian">
                        <div style="font-weight: 600; color: var(--text-primary);">
                            <?= date('d/m/Y H:i', strtotime($log['created_at'])) ?>
                        </div>
                        <div style="font-size: 0.7rem; color: var(--text-muted);">
                            <?= date('s', strtotime($log['created_at'])) ?>s
                        </div>
                    </td>
                    <td data-label="Hành Động">
                        <span class="action-badge">
                            <?php 
                            $icon = $actionIcons[$log['action']] ?? 'fa-circle';
                            ?>
                            <i class="fas <?= $icon ?>"></i>
                            <?= e(ucfirst(str_replace('_', ' ', $log['action']))) ?>
                        </span>
                    </td>
                    <td data-label="Thực Thể">
                        <div class="entity-badge">
                            <i class="fas <?= $log['entity'] === 'user' ? 'fa-user' : ($log['entity'] === 'table' ? 'fa-table' : ($log['entity'] === 'order' ? 'fa-receipt' : 'fa-file')) ?>"></i>
                            <span><?= e($log['entity']) ?></span>
                            <?php if ($log['entity_id']): ?>
                                <span>#<?= $log['entity_id'] ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($log['entity_label'])): ?>
                            <div style="font-size: 0.65rem; color: var(--text-muted); margin-top: 4px;"><?= e($log['entity_label']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td data-label="Người Thực Hiện">
                        <?php if ($log['user_id']): ?>
                            <div style="font-weight: 600; color: var(--text-primary);"><?= e($log['user_name']) ?></div>
                            <div style="font-size: 0.65rem; color: var(--text-muted);"><?= e(ucfirst($log['user_role'])) ?></div>
                        <?php else: ?>
                            <span style="color: var(--text-muted);">System</span>
                        <?php endif; ?>
                    </td>
                    <td data-label="IP" style="font-family: monospace; font-size: 0.75rem; color: var(--text-secondary);">
                        <?= e($log['ip_address']) ?>
                    </td>
                    <td data-label="Mức Độ">
                        <span class="level-badge <?= $log['level'] ?>"><?= e($log['level']) ?></span>
                    </td>
                    <td data-label="Chi Tiết">
                        <i class="fas fa-chevron-right metadata-toggle" onclick="toggleMetadata(this, <?= htmlspecialchars(json_encode($log['metadata']), ENT_QUOTES) ?>)"></i>
                        <div class="metadata-content"></div>
                    </td>
                </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if ($pagination['totalPages'] > 1): ?>
    <div class="activity-pagination">
        <div class="pagination-info">
            Trang <?= $pagination['page'] ?> / <?= $pagination['totalPages'] ?>
        </div>
        <div class="pagination-buttons">
            <button onclick="goToPage(1)" <?= $pagination['page'] <= 1 ? 'disabled' : '' ?>>
                <i class="fas fa-angle-double-left"></i>
            </button>
            <button onclick="goToPage(<?= $pagination['page'] - 1 ?>)" <?= $pagination['page'] <= 1 ? 'disabled' : '' ?>>
                <i class="fas fa-angle-left"></i>
            </button>
            <button class="active"><?= $pagination['page'] ?></button>
            <button onclick="goToPage(<?= $pagination['page'] + 1 ?>)" <?= $pagination['page'] >= $pagination['totalPages'] ? 'disabled' : '' ?>>
                <i class="fas fa-angle-right"></i>
            </button>
            <button onclick="goToPage(<?= $pagination['totalPages'] ?>)" <?= $pagination['page'] >= $pagination['totalPages'] ? 'disabled' : '' ?>>
                <i class="fas fa-angle-double-right"></i>
            </button>
        </div>
    </div>
    <?php endif; ?>
</div>

<!-- Entity Logs Modal -->
<div class="entity-logs-modal" id="entityLogsModal">
    <div class="entity-logs-card">
        <div class="entity-logs-header">
            <h3 id="entityLogsTitle">Lịch Sử Hoạt Động</h3>
            <button class="entity-logs-close" onclick="closeEntityLogsModal()">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <div class="entity-logs-body" id="entityLogsBody">
            <div class="activity-loading">
                <i class="fas fa-spinner"></i>
                <p>Đang tải...</p>
            </div>
        </div>
    </div>
</div>

<script>
function toggleMetadata(element, metadata) {
    element.classList.toggle('expanded');
    const content = element.nextElementSibling;
    content.classList.toggle('show');
    if (content.classList.contains('show')) {
        content.textContent = JSON.stringify(metadata, null, 2);
    }
}

function goToPage(page) {
    const url = new URL(window.location.href);
    url.searchParams.set('page', page);
    window.location.href = url.toString();
}

function resetFilters() {
    window.location.href = '<?= BASE_URL ?>/admin/activity';
}

function exportLogs() {
    const params = new URLSearchParams(window.location.search);
    window.location.href = '<?= BASE_URL ?>/admin/activity/export?' + params.toString();
}

function showEntityLogs(entity, entityId, title) {
    const modal = document.getElementById('entityLogsModal');
    const body = document.getElementById('entityLogsBody');
    document.getElementById('entityLogsTitle').textContent = title;
    
    modal.classList.add('show');
    
    fetch(`<?= BASE_URL ?>/admin/activity/entityLogs?entity=${entity}&entity_id=${entityId}`)
        .then(res => res.json())
        .then(data => {
            if (data.ok && data.data.length > 0) {
                body.innerHTML = data.data.map(log => `
                    <div class="entity-log-item">
                        <div class="entity-log-time">${new Date(log.created_at).toLocaleString('vi-VN')}</div>
                        <div class="entity-log-action">
                            <i class="fas fa-${getActionIcon(log.action)}"></i>
                            ${log.action}
                        </div>
                        <div class="entity-log-user">${log.user_name || 'System'}</div>
                    </div>
                `).join('');
            } else {
                body.innerHTML = '<div class="empty-state"><i class="fas fa-inbox"></i><p>Không có lịch sử hoạt động</p></div>';
            }
        })
        .catch(err => {
            body.innerHTML = '<div class="empty-state" style="color: var(--danger);"><i class="fas fa-exclamation-circle"></i><p>Lỗi khi tải dữ liệu</p></div>';
        });
}

function closeEntityLogsModal() {
    document.getElementById('entityLogsModal').classList.remove('show');
}

function getActionIcon(action) {
    const icons = {
        'create': 'plus-circle',
        'update': 'edit',
        'delete': 'trash',
        'view': 'eye',
        'login': 'sign-in-alt',
        'logout': 'sign-out-alt',
        'payment': 'money-bill-wave',
        'transfer': 'exchange-alt',
        'merge': 'object-group',
        'split': 'object-ungroup',
    };
    return icons[action] || 'circle';
}

// Close modal on outside click
document.getElementById('entityLogsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEntityLogsModal();
    }
});
</script>