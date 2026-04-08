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
                    <button type="submit" class="btn-gold">
                        <i class="fas fa-filter me-1"></i> Lọc
                    </button>
                    <button type="button" class="btn-ghost" onclick="resetFilters()">
                        <i class="fas fa-redo"></i>
                    </button>
                    <button type="button" class="btn-ghost" onclick="exportLogs()">
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
                <td colspan="7" class="empty-state">
                    <i class="fas fa-inbox empty-state-icon"></i>
                    Không có bản ghi nào
                </td>
            </tr>
            <?php else: ?>
                <?php foreach ($logs as $log): ?>
                <tr data-log-id="<?= $log['id'] ?>">
                    <td>
                        <div class="log-time-main"><?= date('d/m/Y H:i', strtotime($log['created_at'])) ?></div>
                        <div class="log-time-sub"><?= date('s', strtotime($log['created_at'])) ?>s</div>
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
                                <span class="entity-id">#<?= $log['entity_id'] ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if (!empty($log['entity_label'])): ?>
                            <div class="entity-label"><?= e($log['entity_label']) ?></div>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if ($log['user_id']): ?>
                            <div class="user-name"><?= e($log['user_name']) ?></div>
                            <div class="user-role"><?= e(ucfirst($log['user_role'])) ?></div>
                        <?php else: ?>
                            <span class="user-system">System</span>
                        <?php endif; ?>
                    </td>
                    <td class="ip-address"><?= e($log['ip_address']) ?></td>
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