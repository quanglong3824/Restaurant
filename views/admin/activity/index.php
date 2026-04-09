<?php
// views/admin/activity/index.php — Activity Logs View
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
/* Activity Logs Styles */
.activity-logs-page {
    padding: 0;
}

/* Stats Cards */
.activity-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.stat-card {
    background: #fff;
    border-radius: 12px;
    padding: 1.25rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-left: 4px solid var(--gold);
}

.stat-card.danger { border-left-color: #ef4444; }
.stat-card.warning { border-left-color: #f59e0b; }
.stat-card.info { border-left-color: #3b82f6; }

.stat-card-label {
    font-size: 0.75rem;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    font-weight: 600;
}

.stat-card-value {
    font-size: 1.75rem;
    font-weight: 800;
    color: #0f172a;
    margin: 0.25rem 0;
}

.stat-card-sub {
    font-size: 0.75rem;
    color: #94a3b8;
}

/* Filter Bar */
.activity-filters {
    background: #f8fafc;
    border-radius: 12px;
    padding: 1rem;
    margin-bottom: 1.5rem;
    border: 1px solid #e2e8f0;
}

.filter-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 0.75rem;
    align-items: end;
}

.filter-group {
    display: flex;
    flex-direction: column;
    gap: 0.25rem;
}

.filter-group label {
    font-size: 0.7rem;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.filter-group input,
.filter-group select {
    padding: 0.5rem 0.75rem;
    border: 1px solid #cbd5e1;
    border-radius: 8px;
    font-size: 0.85rem;
    background: #fff;
    transition: all 0.2s;
}

.filter-group input:focus,
.filter-group select:focus {
    outline: none;
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.1);
}

.filter-actions {
    display: flex;
    gap: 0.5rem;
    align-items: center;
}

/* Logs Table */
.activity-logs-table {
    width: 100%;
    border-collapse: collapse;
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.activity-logs-table thead {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #fff;
}

.activity-logs-table th {
    padding: 0.875rem 1rem;
    font-size: 0.7rem;
    font-weight: 800;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    text-align: left;
}

.activity-logs-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid #f1f5f9;
    font-size: 0.825rem;
    vertical-align: middle;
}

.activity-logs-table tbody tr:hover {
    background: #f8fafc;
}

.activity-logs-table tbody tr:last-child td {
    border-bottom: none;
}

/* Level Badge */
.level-badge {
    display: inline-block;
    padding: 0.2rem 0.5rem;
    border-radius: 50px;
    font-size: 0.65rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.level-badge.info { background: #dbeafe; color: #1d4ed8; }
.level-badge.notice { background: #cffafe; color: #0e7490; }
.level-badge.warning { background: #fef3c7; color: #b45309; }
.level-badge.error { background: #fee2e2; color: #b91c1c; }
.level-badge.critical { background: #f3e8ff; color: #7c3aed; }

/* Action Badge */
.action-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.35rem;
    padding: 0.25rem 0.6rem;
    border-radius: 6px;
    font-size: 0.7rem;
    font-weight: 600;
    background: #f1f5f9;
    color: #475569;
}

/* Entity Badge */
.entity-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.25rem;
    font-size: 0.7rem;
    color: #64748b;
}

.entity-badge i {
    color: var(--gold);
}

/* Metadata Toggle */
.metadata-toggle {
    cursor: pointer;
    color: var(--gold);
    font-size: 0.75rem;
    transition: transform 0.2s;
}

.metadata-toggle.expanded {
    transform: rotate(90deg);
}

.metadata-content {
    display: none;
    background: #f8fafc;
    border-radius: 8px;
    padding: 0.75rem;
    margin-top: 0.5rem;
    font-size: 0.7rem;
    font-family: monospace;
    white-space: pre-wrap;
    word-break: break-all;
    color: #475569;
}

.metadata-content.show {
    display: block;
}

/* Pagination */
.activity-pagination {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem;
    background: #fff;
    border-radius: 12px;
    margin-top: 1rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.pagination-info {
    font-size: 0.8rem;
    color: #64748b;
}

.pagination-buttons {
    display: flex;
    gap: 0.5rem;
}

.pagination-buttons button {
    padding: 0.5rem 0.875rem;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    background: #fff;
    color: #475569;
    font-size: 0.8rem;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
}

.pagination-buttons button:hover:not(:disabled) {
    background: var(--gold);
    color: #fff;
    border-color: var(--gold);
}

.pagination-buttons button:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.pagination-buttons button.active {
    background: var(--gold);
    color: #fff;
    border-color: var(--gold);
}

/* Responsive */
@media (max-width: 768px) {
    .activity-logs-table {
        font-size: 0.7rem;
    }
    
    .activity-logs-table th,
    .activity-logs-table td {
        padding: 0.5rem;
    }
    
    .filter-row {
        grid-template-columns: 1fr;
    }
    
    .activity-stats {
        grid-template-columns: repeat(2, 1fr);
    }
}

/* Loading State */
.activity-loading {
    text-align: center;
    padding: 3rem;
    color: #94a3b8;
}

.activity-loading i {
    font-size: 2rem;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    to { transform: rotate(360deg); }
}

/* Entity Logs Modal */
.entity-logs-modal {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    align-items: center;
    justify-content: center;
    z-index: 10000;
}

.entity-logs-modal.show {
    display: flex;
}

.entity-logs-card {
    background: #fff;
    border-radius: 16px;
    max-width: 600px;
    width: 90%;
    max-height: 80vh;
    overflow: hidden;
    display: flex;
    flex-direction: column;
}

.entity-logs-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #e2e8f0;
}

.entity-logs-header h3 {
    margin: 0;
    font-size: 1rem;
    color: #0f172a;
}

.entity-logs-close {
    background: none;
    border: none;
    font-size: 1.25rem;
    color: #94a3b8;
    cursor: pointer;
}

.entity-logs-body {
    padding: 1rem 1.25rem;
    overflow-y: auto;
    flex: 1;
}

.entity-log-item {
    padding: 0.75rem;
    border-bottom: 1px solid #f1f5f9;
}

.entity-log-item:last-child {
    border-bottom: none;
}

.entity-log-time {
    font-size: 0.7rem;
    color: #94a3b8;
}

.entity-log-action {
    font-size: 0.8rem;
    font-weight: 600;
    color: #0f172a;
}

.entity-log-user {
    font-size: 0.7rem;
    color: #64748b;
}
</style>

<div class="activity-logs-page">
    <!-- Stats Cards -->
    <div class="activity-stats">
        <div class="stat-card">
            <div class="stat-card-label">Hôm Nay</div>
            <div class="stat-card-value"><?= $stats['today']['total'] ?? 0 ?></div>
            <div class="stat-card-sub">Hoạt động ghi nhận</div>
        </div>
        <div class="stat-card danger">
            <div class="stat-card-label">Lỗi Hôm Nay</div>
            <div class="stat-card-value"><?= $stats['today']['errors'] ?? 0 ?></div>
            <div class="stat-card-sub">Cần xem xét</div>
        </div>
        <div class="stat-card warning">
            <div class="stat-card-label">Cảnh Báo Hôm Nay</div>
            <div class="stat-card-value"><?= $stats['today']['warnings'] ?? 0 ?></div>
            <div class="stat-card-sub">Lưu ý</div>
        </div>
        <div class="stat-card info">
            <div class="stat-card-label">7 Ngày Qua</div>
            <div class="stat-card-value"><?= $stats['week']['total'] ?? 0 ?></div>
            <div class="stat-card-sub"><?= $stats['week']['unique_users'] ?? 0 ?> người dùng</div>
        </div>
    </div>

    <!-- Filter Bar -->
    <div class="activity-filters">
        <form method="GET" action="<?= BASE_URL ?>/admin/activity" id="filterForm">
            <div class="filter-row">
                <div class="filter-group">
                    <label>Từ ngày</label>
                    <input type="date" name="from" value="<?= e($filters['from']) ?>">
                </div>
                <div class="filter-group">
                    <label>Đến ngày</label>
                    <input type="date" name="to" value="<?= e($filters['to']) ?>">
                </div>
                <div class="filter-group">
                    <label>Hành động</label>
                    <select name="action">
                        <option value="">Tất cả</option>
                        <?php foreach ($actions as $action): ?>
                            <option value="<?= e($action) ?>" <?= $filters['action'] === $action ? 'selected' : '' ?>>
                                <?= e(ucfirst($action)) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="filter-group">
                    <label>Thực thể</label>
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
                    <label>Mức độ</label>
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
                    <label>Người dùng</label>
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
                    <button type="submit" class="btn-gold" style="padding: 0.5rem 1rem; font-size: 0.8rem;">
                        <i class="fas fa-filter me-1"></i> Lọc
                    </button>
                    <button type="button" class="btn-ghost" style="padding: 0.5rem 1rem; font-size: 0.8rem;" onclick="resetFilters()">
                        <i class="fas fa-redo"></i>
                    </button>
                    <button type="button" class="btn-ghost" style="padding: 0.5rem 1rem; font-size: 0.8rem;" onclick="exportLogs()">
                        <i class="fas fa-download"></i> Export
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Logs Table -->
    <table class="activity-logs-table">
        <thead>
            <tr>
                <th>Thời Gian</th>
                <th>Hành Động</th>
                <th>Thực Thể</th>
                <th>Người Thực Hiện</th>
                <th>IP</th>
                <th>Mức Độ</th>
                <th>Chi Tiết</th>
            </tr>
        </thead>
        <tbody id="logsTableBody">
            <?php if (empty($logs)): ?>
            <tr>
                <td colspan="7" style="text-align: center; padding: 3rem; color: #94a3b8;">
                    <i class="fas fa-inbox" style="font-size: 2rem; display: block; margin-bottom: 1rem; opacity: 0.5;"></i>
                    Không có bản ghi nào
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr data-log-id="<?= $log['id'] ?>">
                    <td>
                        <div style="font-weight: 600; color: #0f172a;"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></div>
                        <div style="font-size: 0.7rem; color: #94a3b8;"><?= date('s', strtotime($log['created_at'])) ?>s</div>
                    </td>
                    <td>
                        <span class="action-badge">
                            <?php 
                            $icon = $actionIcons[$log['action']] ?? 'fa-circle';
                            ?>
                            <i class="fas <?= $icon ?>"></i>
                            <?= e(ucfirst(str_replace('_', ' ', $log['action']))) ?>
                        </span>
                    </td>
                    <td>
                        <div class="entity-badge">
                            <i class="fas <?= $log['entity'] === 'user' ? 'fa-user' : ($log['entity'] === 'table' ? 'fa-table' : ($log['entity'] === 'order' ? 'fa-receipt' : 'fa-file')) ?>"></i>
                            <span><?= e($log['entity']) ?></span>
                            <?php if ($log['entity_id']): ?>
                                <span style="color: #cbd5e1;">#<?= $log['entity_id'] ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($log['entity_label'])): ?>
                            <div style="font-size: 0.65rem; color: #94a3b8; margin-top: 0.25rem;"><?= e($log['entity_label']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($log['user_id']): ?>
                            <div style="font-weight: 600; color: #0f172a;"><?= e($log['user_name']) ?></div>
                            <div style="font-size: 0.65rem; color: #94a3b8;"><?= e(ucfirst($log['user_role'])) ?></div>
                        <?php else: ?>
                            <span style="color: #94a3b8;">System</span>
                        <?php endif; ?>
                    </td>
                    <td style="font-family: monospace; font-size: 0.75rem;"><?= e($log['ip_address']) ?></td>
                    <td>
                        <span class="level-badge <?= $log['level'] ?>"><?= e($log['level']) ?></span>
                    </td>
                    <td>
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
    <?= renderPagination($pagination['page'], $pagination['totalPages'], BASE_URL . '/admin/activity', array_filter([
        'action' => $filters['action'] ?? '',
        'entity' => $filters['entity'] ?? '',
        'user_id' => $filters['user_id'] ?? '',
        'level' => $filters['level'] ?? '',
        'from' => $filters['from'] ?? '',
        'to' => $filters['to'] ?? '',
    ])) ?>
    <?php endif; ?>
</div>

<!-- Entity Logs Modal -->
<div class="entity-logs-modal" id="entityLogsModal">
    <div class="entity-logs-card">
        <div class="entity-logs-header">
            <h3 id="entityLogsTitle">Lịch Sử Hoạt Động</h3>
            <button class="entity-logs-close" onclick="closeEntityLogsModal()"><i class="fas fa-times"></i></button>
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
                body.innerHTML = '<div style="text-align: center; padding: 2rem; color: #94a3b8;">Không có lịch sử hoạt động</div>';
            }
        })
        .catch(err => {
            body.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;">Lỗi khi tải dữ liệu</div>';
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