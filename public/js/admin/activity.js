/**
 * Admin Activity Logs - JavaScript
 * Aurora Restaurant
 */

(function() {
    'use strict';

    /**
     * Toggle metadata display
     * @param {HTMLElement} element - The clicked chevron icon
     * @param {Object} metadata - Metadata object to display
     */
    function toggleMetadata(element, metadata) {
        element.classList.toggle('expanded');
        const content = element.nextElementSibling;
        content.classList.toggle('show');
        if (content.classList.contains('show')) {
            content.textContent = JSON.stringify(metadata, null, 2);
        }
    }

    /**
     * Navigate to a specific page
     * @param {number} page - Page number
     */
    function goToPage(page) {
        const url = new URL(window.location.href);
        url.searchParams.set('page', page);
        window.location.href = url.toString();
    }

    /**
     * Reset all filters
     */
    function resetFilters() {
        window.location.href = BASE_URL + '/admin/activity';
    }

    /**
     * Export activity logs
     */
    function exportLogs() {
        const params = new URLSearchParams(window.location.search);
        window.location.href = BASE_URL + '/admin/activity/export?' + params.toString();
    }

    /**
     * Show entity logs modal
     * @param {string} entity - Entity type
     * @param {number} entityId - Entity ID
     * @param {string} title - Modal title
     */
    function showEntityLogs(entity, entityId, title) {
        const modal = document.getElementById('entityLogsModal');
        const body = document.getElementById('entityLogsBody');
        const titleEl = document.getElementById('entityLogsTitle');
        
        if (!modal || !body || !titleEl) return;
        
        titleEl.textContent = title;
        modal.classList.add('show');
        
        fetch(BASE_URL + '/admin/activity/entityLogs?entity=' + encodeURIComponent(entity) + '&entity_id=' + encodeURIComponent(entityId))
            .then(res => res.json())
            .then(data => {
                if (data.ok && data.data.length > 0) {
                    body.innerHTML = data.data.map(function(log) {
                        return '<div class="entity-log-item">' +
                            '<div class="entity-log-time">' + new Date(log.created_at).toLocaleString('vi-VN') + '</div>' +
                            '<div class="entity-log-action">' +
                                '<i class="fas fa-' + getActionIcon(log.action) + '"></i> ' +
                                log.action +
                            '</div>' +
                            '<div class="entity-log-user">' + (log.user_name || 'System') + '</div>' +
                        '</div>';
                    }).join('');
                } else {
                    body.innerHTML = '<div style="text-align: center; padding: 2rem; color: #94a3b8;">Không có lịch sử hoạt động</div>';
                }
            })
            .catch(function(err) {
                console.error(err);
                body.innerHTML = '<div style="text-align: center; padding: 2rem; color: #ef4444;">Lỗi khi tải dữ liệu</div>';
            });
    }

    /**
     * Close entity logs modal
     */
    function closeEntityLogsModal() {
        const modal = document.getElementById('entityLogsModal');
        if (modal) {
            modal.classList.remove('show');
        }
    }

    /**
     * Get icon class for action
     * @param {string} action - Action name
     * @returns {string} Icon class
     */
    function getActionIcon(action) {
        var icons = {
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

    /**
     * Initialize event listeners
     */
    function init() {
        // Close modal on outside click
        const modal = document.getElementById('entityLogsModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    closeEntityLogsModal();
                }
            });
        }

        // Expose global functions for onclick handlers
        window.toggleMetadata = toggleMetadata;
        window.goToPage = goToPage;
        window.resetFilters = resetFilters;
        window.exportLogs = exportLogs;
        window.showEntityLogs = showEntityLogs;
        window.closeEntityLogsModal = closeEntityLogsModal;
        window.getActionIcon = getActionIcon;
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();