/**
 * Admin Menu Management - Aurora Restaurant
 * Handles filtering, searching, and toggling menu items
 */

(function() {
    'use strict';

    let _activeServiceFilter = '';
    let _debounceTimer = null;

    /**
     * Apply all filters to the menu table
     */
    function applyFilters() {
        const searchInput = document.getElementById('searchInput');
        const catFilter = document.getElementById('catFilter');
        const statusFilter = document.getElementById('statusFilter');
        const countBadge = document.getElementById('countBadge');
        const noResultsState = document.getElementById('noResultsState');
        const menuTableBody = document.querySelector('#menuTable tbody');

        if (!searchInput || !catFilter || !statusFilter) return;

        const search = searchInput.value.toLowerCase().trim();
        const catVal = catFilter.value;
        const statusVal = statusFilter.value;
        const svcVal = _activeServiceFilter;

        let visible = 0;

        document.querySelectorAll('#menuTable tbody tr[data-cat]').forEach(row => {
            const nameMatch = !search || row.dataset.name.includes(search);
            const catMatch = !catVal || row.dataset.cat === catVal;
            const svcMatch = !svcVal || row.dataset.service === svcVal;
            let statusMatch = true;

            if (statusVal === 'active') statusMatch = row.dataset.active === '1';
            if (statusVal === 'inactive') statusMatch = row.dataset.active === '0';
            if (statusVal === 'available') statusMatch = row.dataset.available === '1';
            if (statusVal === 'unavailable') statusMatch = row.dataset.available === '0';

            const show = nameMatch && catMatch && svcMatch && statusMatch;
            row.style.display = show ? '' : 'none';

            if (show) visible++;
        });

        // Update count badge
        if (countBadge) {
            countBadge.textContent = visible + ' món';
        }

        // Show/hide no results state
        if (noResultsState && menuTableBody) {
            const totalRows = document.querySelectorAll('#menuTable tbody tr[data-cat]').length;
            if (visible === 0 && totalRows > 0) {
                menuTableBody.style.display = 'none';
                noResultsState.style.display = 'block';
            } else {
                menuTableBody.style.display = '';
                noResultsState.style.display = 'none';
            }
        }
    }

    /**
     * Quick filter by service type (stat chips)
     * @param {HTMLElement} btn - The clicked button
     * @param {string} serviceVal - The service type value
     */
    function quickFilter(btn, serviceVal) {
        document.querySelectorAll('.stat-chip').forEach(c => c.classList.remove('active'));
        btn.classList.add('active');
        _activeServiceFilter = serviceVal;

        // Remove any existing service filter select
        const existingServiceFilter = document.getElementById('serviceFilter');
        if (existingServiceFilter) {
            existingServiceFilter.remove();
        }

        applyFilters();
    }

    /**
     * Clear search input and apply filters
     */
    function clearSearchInput() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');

        if (searchInput) {
            searchInput.value = '';
        }
        if (clearBtn) {
            clearBtn.style.display = 'none';
        }
        applyFilters();
    }

    /**
     * Reset all filters to default state
     */
    function resetAllFilters() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');
        const catFilter = document.getElementById('catFilter');
        const statusFilter = document.getElementById('statusFilter');

        if (searchInput) searchInput.value = '';
        if (clearBtn) clearBtn.style.display = 'none';
        if (catFilter) catFilter.value = '';
        if (statusFilter) statusFilter.value = '';

        _activeServiceFilter = '';

        // Reset stat chips
        document.querySelectorAll('.stat-chip').forEach((c, i) => {
            c.classList.toggle('active', i === 0);
        });

        applyFilters();
    }

    /**
     * Toggle item status (active/available)
     * @param {number} id - Item ID
     * @param {string} type - Toggle type ('active' or 'available')
     * @param {HTMLElement} btn - The toggle button
     */
    function toggleItem(id, type, btn) {
        fetch(BASE_URL + '/admin/menu/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id, type })
        })
        .then(response => response.json())
        .then(data => {
            if (!data || !data.ok) return;

            const row = btn.closest('tr');
            if (!row) return;

            if (type === 'available') {
                const isAvailable = data.is_available == 1;
                btn.textContent = isAvailable ? 'Còn hàng' : 'Hết hàng';
                btn.className = 'toggle-btn ' + (isAvailable ? 'toggle-btn--on' : 'toggle-btn--off');
                row.dataset.available = isAvailable ? '1' : '0';
            } else {
                const isActive = data.is_active == 1;
                btn.innerHTML = '<i class="fas ' + (isActive ? 'fa-eye' : 'fa-eye-slash') + '"></i>';
                btn.className = 'toggle-btn ' + (isActive ? 'toggle-btn--on' : '');
                btn.title = isActive ? 'Đang hiện — Click để ẩn' : 'Đang ẩn — Click để hiện';
                row.dataset.active = isActive ? '1' : '0';
            }

            applyFilters();
        })
        .catch(error => {
            console.error('Toggle error:', error);
        });
    }

    /**
     * Initialize event listeners
     */
    function init() {
        const searchInput = document.getElementById('searchInput');
        const clearBtn = document.getElementById('clearSearch');
        const catFilter = document.getElementById('catFilter');
        const statusFilter = document.getElementById('statusFilter');

        // Search with debounce
        if (searchInput) {
            searchInput.addEventListener('input', function() {
                if (clearBtn) {
                    clearBtn.style.display = this.value ? '' : 'none';
                }

                clearTimeout(_debounceTimer);
                _debounceTimer = setTimeout(applyFilters, 220);
            });
        }

        // Category filter change
        if (catFilter) {
            catFilter.addEventListener('change', applyFilters);
        }

        // Status filter change
        if (statusFilter) {
            statusFilter.addEventListener('change', applyFilters);
        }

        // Expose global functions for onclick handlers
        window.quickFilter = quickFilter;
        window.clearSearchInput = clearSearchInput;
        window.resetAllFilters = resetAllFilters;
        window.toggleItem = toggleItem;
        window.applyFilters = applyFilters;
    }

    // Initialize when DOM is ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init);
    } else {
        init();
    }
})();