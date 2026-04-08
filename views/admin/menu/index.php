<?php // views/admin/menu/index.php ?>

<?php
// Đếm nhanh theo service_type để hiển thị badge
$countAll        = count($items);
// Nhà hàng = restaurant + both (vì both cũng phục vụ tại nhà hàng)
$countRestaurant = count(array_filter($items, fn($i) => in_array($i['service_type'] ?? 'both', ['restaurant', 'both'])));
$countRoom       = count(array_filter($items, fn($i) => in_array($i['service_type'] ?? 'both', ['room_service', 'both'])));
$countBoth       = count(array_filter($items, fn($i) => ($i['service_type'] ?? 'both') === 'both'));
?>

<div class="card">
    <div class="card-header" style="flex-direction:column;align-items:flex-start;gap:1rem;">
        
        <div style="display:flex;align-items:center;justify-content:space-between;width:100%;flex-wrap:wrap;gap:.75rem;">
            <h2 style="margin:0;"><i class="fas fa-utensils"></i> Danh sách Món ăn
                <span id="countBadge" style="font-size:.75rem;font-weight:600;background:var(--gold);color:#fff;padding:.15rem .65rem;border-radius:20px;margin-left:.5rem;vertical-align:middle;"><?= $countAll ?> món</span>
            </h2>
            <div style="display:flex;gap:.5rem;">
                <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline <?= !isset($_GET['type']) || $_GET['type'] === '' ? 'active' : '' ?>" style="text-decoration:none;">
                    <i class="fas fa-utensils"></i> Món Lẻ
                </a>
                <a href="<?= BASE_URL ?>/admin/menu/sets" class="btn btn-outline <?= isset($_GET['type']) && $_GET['type'] === 'sets' ? 'active' : '' ?>" style="text-decoration:none;">
                    <i class="fas fa-layer-group"></i> Set & Combo
                </a>
                <a href="<?= BASE_URL ?>/admin/menu/create" class="btn btn-gold" style="text-decoration:none;">
                    <i class="fas fa-plus"></i> Thêm món
                </a>
                <?php if (Auth::check() && Auth::user()['role'] === ROLE_IT): ?>
                <a href="<?= BASE_URL ?>/admin/menu/clear" class="btn btn-outline" style="border-color:#dc2626;color:#dc2626;text-decoration:none;" title="Xóa dữ liệu thực đơn (IT only)">
                    <i class="fas fa-trash-alt"></i> Xóa dữ liệu
                </a>
                <?php endif; ?>
            </div>
        </div>

        <!-- ── STAT CHIPS ─────────────────────────────────────── -->
        <div style="display:flex;gap:.6rem;flex-wrap:wrap;">
            <button class="stat-chip active" data-service="" onclick="quickFilter(this,'')">
                <i class="fas fa-border-all"></i> Tất cả <span class="chip-count"><?= $countAll ?></span>
            </button>
            <button class="stat-chip" data-service="restaurant" onclick="quickFilter(this,'restaurant')">
                <i class="fas fa-utensils"></i> Nhà hàng <span class="chip-count"><?= $countRestaurant ?></span>
            </button>
            <button class="stat-chip" data-service="room_service" onclick="quickFilter(this,'room_service')">
                <i class="fas fa-bed"></i> Room Service <span class="chip-count"><?= $countRoom ?></span>
            </button>
            <button class="stat-chip" data-service="both" onclick="quickFilter(this,'both')">
                <i class="fas fa-arrows-left-right"></i> Cả hai <span class="chip-count"><?= $countBoth ?></span>
            </button>
        </div>

        <!-- ── FILTER BAR ──────────────────────────────────────── -->
        <div class="filter-bar">
            <!-- Search -->
            <div class="filter-input-wrap">
                <i class="fas fa-search filter-icon"></i>
                <input type="text" id="searchInput" class="filter-input" placeholder="Tìm tên món...">
                <button id="clearSearch" class="filter-clear" style="display:none;" onclick="clearSearchInput()" title="Xóa">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <!-- Category -->
            <div class="filter-select-wrap">
                <i class="fas fa-folder filter-icon"></i>
                <select id="catFilter" class="filter-select">
                    <option value="">Tất cả danh mục</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>"><?= e($cat['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Status -->
            <div class="filter-select-wrap">
                <i class="fas fa-eye filter-icon"></i>
                <select id="statusFilter" class="filter-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="active">Đang hiển thị</option>
                    <option value="inactive">Đang ẩn</option>
                    <option value="available">Còn hàng</option>
                    <option value="unavailable">Hết hàng</option>
                </select>
            </div>

            <!-- Reset -->
            <button id="resetFilters" class="btn btn-outline btn-sm" onclick="resetAllFilters()" title="Xóa bộ lọc">
                <i class="fas fa-rotate-left"></i> Đặt lại
            </button>
        </div>
    </div>

    <div class="table-wrap">
        <table id="menuTable">
            <thead>
                <tr>
                    <th style="width:56px;">Ảnh</th>
                    <th>Tên món</th>
                    <th>Danh mục</th>
                    <th>Phục vụ</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th style="width:80px;">Hiển thị</th>
                    <th style="width:100px;">Còn hàng</th>
                    <th style="width:110px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item):
                    $st = $item['service_type'] ?? 'both';
                    $stMap = [
                        'restaurant'  => ['label' => 'Nhà hàng',    'icon' => 'fa-utensils',          'color' => '#0ea5e9'],
                        'room_service'=> ['label' => 'Room Service', 'icon' => 'fa-bed',               'color' => '#8b5cf6'],
                        'both'        => ['label' => 'Cả hai',       'icon' => 'fa-arrows-left-right', 'color' => '#16a34a'],
                    ];
                    $s = $stMap[$st] ?? $stMap['both'];
                ?>
                    <tr data-cat="<?= $item['category_id'] ?>"
                        data-service="<?= $st ?>"
                        data-name="<?= strtolower(e($item['name'])) ?> <?= strtolower(e($item['name_en'] ?? '')) ?>"
                        data-active="<?= $item['is_active'] ?>"
                        data-available="<?= $item['is_available'] ?>">
                        <td>
                            <?php if ($item['image']): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt=""
                                    style="width:44px;height:44px;object-fit:cover;border-radius:8px;box-shadow:0 1px 4px rgba(0,0,0,.12);">
                            <?php else: ?>
                                <div style="width:44px;height:44px;background:linear-gradient(135deg,#f3f4f6,#e5e7eb);border-radius:8px;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?= e($item['name']) ?></strong>
                            <?php if ($item['name_en']): ?>
                                <span style="display:block;font-size:.75rem;color:#9ca3af;"><?= e($item['name_en']) ?></span>
                            <?php endif; ?>
                            <?php
                            $noteChips = array_filter(array_map('trim', explode(',', $item['note_options'] ?? '')));
                            if (!empty($noteChips)): ?>
                            <div style="display:flex;flex-wrap:wrap;gap:.25rem;margin-top:.35rem;">
                                <?php foreach ($noteChips as $chip): ?>
                                <span style="background:rgba(212,175,55,.12);color:var(--gold-dark,#785e0a);border:1px solid rgba(212,175,55,.4);border-radius:12px;padding:.1rem .45rem;font-size:.62rem;font-weight:700;">
                                    <i class="fas fa-tag" style="font-size:.5rem;opacity:.7;"></i> <?= e($chip) ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td style="font-size:.85rem;color:#64748b;"><?= e($item['category_name'] ?? '') ?></td>
                        <td>
                            <span class="service-badge" style="--c:<?= $s['color'] ?>">
                                <i class="fas <?= $s['icon'] ?>"></i> <?= $s['label'] ?>
                            </span>
                        </td>
                        <td><strong style="color:var(--gold);font-size:.95rem;"><?= formatPrice($item['price']) ?></strong></td>
                        <td>
                            <?php if (!isset($item['stock']) || $item['stock'] == -1): ?>
                                <span style="font-size:.72rem;color:#16a34a;font-weight:700;">∞ Không giới hạn</span>
                            <?php elseif ($item['stock'] < 5): ?>
                                <span class="badge" style="background:#fee2e2;color:#dc2626;border:1px solid #fca5a5;"><?= $item['stock'] ?> còn lại</span>
                            <?php else: ?>
                                <span class="badge" style="background:#f0fdf4;color:#16a34a;border:1px solid #86efac;"><?= $item['stock'] ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <button class="toggle-btn <?= $item['is_active'] ? 'toggle-btn--on' : '' ?>"
                                onclick="toggleItem(<?= $item['id'] ?>, 'active', this)"
                                title="<?= $item['is_active'] ? 'Đang hiện — Click để ẩn' : 'Đang ẩn — Click để hiện' ?>">
                                <i class="fas <?= $item['is_active'] ? 'fa-eye' : 'fa-eye-slash' ?>"></i>
                            </button>
                        </td>
                        <td>
                            <button class="toggle-btn <?= $item['is_available'] ? 'toggle-btn--on' : 'toggle-btn--off' ?>"
                                onclick="toggleItem(<?= $item['id'] ?>, 'available', this)"
                                title="<?= $item['is_available'] ? 'Còn hàng — Click để đánh Hết' : 'Hết hàng — Click để Mở lại' ?>">
                                <?= $item['is_available'] ? 'Còn hàng' : 'Hết hàng' ?>
                            </button>
                        </td>
                        <td>
                            <div style="display:flex;gap:.35rem;">
                                <a href="<?= BASE_URL ?>/admin/menu/edit?id=<?= $item['id'] ?>"
                                    class="btn btn-outline btn-sm" title="Sửa món"><i class="fas fa-pen"></i></a>
                                <form method="POST" action="<?= BASE_URL ?>/admin/menu/delete" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger-outline btn-sm"
                                        data-confirm="Xóa món '<?= e($item['name']) ?>'?" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr><td colspan="9" style="text-align:center;padding:2.5rem;color:#9ca3af;">
                        <i class="fas fa-utensils fa-2x" style="opacity:.3;display:block;margin-bottom:.75rem;"></i>
                        Chưa có món nào.
                    </td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- No results state -->
        <div id="noResultsState" style="display:none;text-align:center;padding:3rem 1rem;color:#9ca3af;">
            <i class="fas fa-search fa-2x" style="opacity:.3;display:block;margin-bottom:.75rem;"></i>
            <p style="font-weight:600;">Không tìm thấy món phù hợp</p>
            <button onclick="resetAllFilters()" class="btn btn-outline btn-sm" style="margin-top:.5rem;">
                <i class="fas fa-rotate-left me-1"></i> Xóa bộ lọc
            </button>
        </div>
    </div>
</div>

<style>
/* ── Stat Chips ──────────────────────────────────────────── */
.stat-chip {
    display: inline-flex;
    align-items: center;
    gap: .4rem;
    padding: .35rem .9rem;
    border-radius: 20px;
    font-size: .8rem;
    font-weight: 600;
    border: 1.5px solid var(--border-color, #e5e7eb);
    background: transparent;
    color: var(--text-secondary, #64748b);
    cursor: pointer;
    transition: all .18s;
}
.stat-chip:hover { border-color: var(--gold); color: var(--gold); }
.stat-chip.active {
    background: var(--gold);
    border-color: var(--gold);
    color: #fff;
    box-shadow: 0 2px 8px rgba(212,175,55,.35);
}
.chip-count {
    background: rgba(255,255,255,.25);
    border-radius: 10px;
    padding: .05rem .45rem;
    font-size: .72rem;
    font-weight: 700;
}
.stat-chip:not(.active) .chip-count {
    background: rgba(0,0,0,.07);
}

/* ── Filter Bar ──────────────────────────────────────────── */
.filter-bar {
    display: flex;
    align-items: center;
    gap: .6rem;
    flex-wrap: wrap;
    width: 100%;
    padding: .65rem .85rem;
    background: var(--card-bg-alt, #f8fafc);
    border: 1.5px solid var(--border-color, #e5e7eb);
    border-radius: 12px;
}
.filter-input-wrap,
.filter-select-wrap {
    display: flex;
    align-items: center;
    gap: .45rem;
    background: #fff;
    border: 1.5px solid var(--border-color, #e5e7eb);
    border-radius: 8px;
    padding: 0 .65rem;
    transition: border-color .18s;
}
.filter-input-wrap:focus-within,
.filter-select-wrap:focus-within {
    border-color: var(--gold, #d4af37);
    box-shadow: 0 0 0 3px rgba(212,175,55,.1);
}
.filter-icon {
    color: #9ca3af;
    font-size: .8rem;
    flex-shrink: 0;
}
.filter-input {
    border: none !important;
    outline: none !important;
    font-size: .875rem;
    padding: .5rem 0;
    background: transparent;
    min-width: 180px;
    color: var(--text-primary, #1e293b);
}
.filter-input::placeholder { color: #bdc3cc; }
.filter-select {
    border: none !important;
    outline: none !important;
    font-size: .875rem;
    padding: .5rem 0;
    background: transparent;
    color: var(--text-primary, #1e293b);
    cursor: pointer;
    min-width: 155px;
    appearance: none;
    -webkit-appearance: none;
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%239ca3af' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-repeat: no-repeat;
    background-position: right .25rem center;
    background-size: 1.1em;
    padding-right: 1.6rem;
}
.filter-clear {
    background: none;
    border: none;
    color: #9ca3af;
    cursor: pointer;
    padding: 0 .1rem;
    font-size: .8rem;
    line-height: 1;
    transition: color .15s;
}
.filter-clear:hover { color: var(--danger, #dc2626); }

/* ── Service Badge ───────────────────────────────────────── */
.service-badge {
    display: inline-flex;
    align-items: center;
    gap: .3rem;
    background: color-mix(in srgb, var(--c) 12%, transparent);
    color: var(--c);
    border: 1.5px solid color-mix(in srgb, var(--c) 35%, transparent);
    border-radius: 20px;
    padding: .2rem .7rem;
    font-size: .72rem;
    font-weight: 700;
    white-space: nowrap;
}

/* Fallback cho browser cũ không hỗ trợ color-mix */
@supports not (color: color-mix(in srgb, red, blue)) {
    .service-badge { background: rgba(100,100,100,.1); border-color: rgba(100,100,100,.3); }
}
</style>

<script>
let _activeServiceFilter = '';

/* ── Quick Filter (Stat chips) ──────────────────────────── */
function quickFilter(btn, serviceVal) {
    document.querySelectorAll('.stat-chip').forEach(c => c.classList.remove('active'));
    btn.classList.add('active');
    _activeServiceFilter = serviceVal;
    document.getElementById('serviceFilter')?.remove(); // ensure legacy select is gone
    applyFilters();
}

/* ── Main filter logic ───────────────────────────────────── */
function applyFilters() {
    const search    = document.getElementById('searchInput').value.toLowerCase().trim();
    const catVal    = document.getElementById('catFilter').value;
    const statusVal = document.getElementById('statusFilter').value;
    const svcVal    = _activeServiceFilter;

    let visible = 0;
    document.querySelectorAll('#menuTable tbody tr[data-cat]').forEach(row => {
        const nameMatch   = !search  || row.dataset.name.includes(search);
        const catMatch    = !catVal  || row.dataset.cat === catVal;
        const svcMatch    = !svcVal  || row.dataset.service === svcVal;
        let   statusMatch = true;
        if (statusVal === 'active')      statusMatch = row.dataset.active    === '1';
        if (statusVal === 'inactive')    statusMatch = row.dataset.active    === '0';
        if (statusVal === 'available')   statusMatch = row.dataset.available === '1';
        if (statusVal === 'unavailable') statusMatch = row.dataset.available === '0';

        const show = nameMatch && catMatch && svcMatch && statusMatch;
        row.style.display = show ? '' : 'none';
        if (show) visible++;
    });

    // Update badge count
    document.getElementById('countBadge').textContent = visible + ' món';

    // No results state
    const noRes = document.getElementById('noResultsState');
    const tbody = document.querySelector('#menuTable tbody');
    if (visible === 0 && document.querySelectorAll('#menuTable tbody tr[data-cat]').length > 0) {
        tbody.style.display = 'none';
        noRes.style.display = 'block';
    } else {
        tbody.style.display = '';
        noRes.style.display = 'none';
    }
}

/* ── Search with debounce ────────────────────────────────── */
let _debounceTimer;
document.getElementById('searchInput').addEventListener('input', function() {
    const clearBtn = document.getElementById('clearSearch');
    clearBtn.style.display = this.value ? '' : 'none';
    clearTimeout(_debounceTimer);
    _debounceTimer = setTimeout(applyFilters, 220);
});

function clearSearchInput() {
    document.getElementById('searchInput').value = '';
    document.getElementById('clearSearch').style.display = 'none';
    applyFilters();
}

document.getElementById('catFilter').addEventListener('change', applyFilters);
document.getElementById('statusFilter').addEventListener('change', applyFilters);

/* ── Reset all ───────────────────────────────────────────── */
function resetAllFilters() {
    document.getElementById('searchInput').value = '';
    document.getElementById('clearSearch').style.display = 'none';
    document.getElementById('catFilter').value = '';
    document.getElementById('statusFilter').value = '';
    _activeServiceFilter = '';
    document.querySelectorAll('.stat-chip').forEach((c,i) => c.classList.toggle('active', i===0));
    applyFilters();
}

/* ── Toggle item ─────────────────────────────────────────── */
function toggleItem(id, type, btn) {
    fetch('<?= BASE_URL ?>/admin/menu/toggle', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams({ id, type })
    })
    .then(r => r.json())
    .then(data => {
        if (!data.ok) return;
        const row = btn.closest('tr');
        if (type === 'available') {
            const on = data.is_available == 1;
            btn.textContent = on ? 'Còn hàng' : 'Hết hàng';
            btn.className = 'toggle-btn ' + (on ? 'toggle-btn--on' : 'toggle-btn--off');
            row.dataset.available = on ? '1' : '0';
        } else {
            const on = data.is_active == 1;
            btn.innerHTML = '<i class="fas ' + (on ? 'fa-eye' : 'fa-eye-slash') + '"></i>';
            btn.className = 'toggle-btn ' + (on ? 'toggle-btn--on' : '');
            btn.title = on ? 'Đang hiện — Click để ẩn' : 'Đang ẩn — Click để hiện';
            row.dataset.active = on ? '1' : '0';
        }
        applyFilters(); // re-count after toggle
    });
}
</script>