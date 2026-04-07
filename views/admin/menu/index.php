<?php // views/admin/menu/index.php ?>
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-utensils"></i> Danh sách Món ăn</h2>
        <div style="display:flex;gap:.5rem;flex-wrap:wrap;align-items:center;">
            <!-- Menu Type Tabs -->
            <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline <?= !isset($_GET['type']) || $_GET['type'] === '' ? 'active' : '' ?>">
                <i class="fas fa-utensils"></i> Món Lẻ
            </a>
            <a href="<?= BASE_URL ?>/admin/menu/sets" class="btn btn-outline <?= isset($_GET['type']) && $_GET['type'] === 'sets' ? 'active' : '' ?>">
                <i class="fas fa-layer-group"></i> Set & Combo
            </a>

            <!-- Category filter -->
            <select id="catFilter" class="form-control" style="width:auto;min-width:160px;">
                <option value="">Tất cả danh mục</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>">
                        <?= e($cat['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <!-- Service type filter -->
            <select id="serviceFilter" class="form-control" style="width:auto;min-width:170px;">
                <option value="">Tất cả loại phục vụ</option>
                <option value="restaurant">Chỉ Nhà hàng</option>
                <option value="room_service">Chỉ Room Service</option>
                <option value="both">Cả hai</option>
            </select>

            <a href="<?= BASE_URL ?>/admin/menu/create" class="btn btn-gold">
                <i class="fas fa-plus"></i> Thêm món
            </a>
        </div>
    </div>

    <div class="table-wrap">
        <table id="menuTable">
            <thead>
                <tr>
                    <th>Ảnh</th>
                    <th>Tên món</th>
                    <th>Danh mục</th>
                    <th>Phục vụ</th>
                    <th>Giá</th>
                    <th>Tồn kho</th>
                    <th>Hiển thị</th>
                    <th>Còn hàng</th>
                    <th style="width:120px"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <tr data-cat="<?= $item['category_id'] ?>">
                        <td>
                            <?php if ($item['image']): ?>
                                <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>" alt=""
                                    style="width:44px;height:44px;object-fit:cover;border-radius:6px;">
                            <?php else: ?>
                                <div
                                    style="width:44px;height:44px;background:#f3f4f6;border-radius:6px;display:flex;align-items:center;justify-content:center;color:#9ca3af;">
                                    <i class="fas fa-utensils"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong>
                                <?= e($item['name']) ?>
                            </strong>
                            <?php if ($item['name_en']): ?>
                                <span style="display:block;font-size:.75rem;color:#9ca3af;">
                                    <?= e($item['name_en']) ?>
                                </span>
                            <?php endif; ?>
                            <?php
                            // Hiển thị note_options chips
                            $noteChips = array_filter(array_map('trim', explode(',', $item['note_options'] ?? '')));
                            if (!empty($noteChips)):
                            ?>
                            <div style="display:flex;flex-wrap:wrap;gap:.25rem;margin-top:.35rem;" title="Tùy chọn ghi chú nhanh">
                                <?php foreach ($noteChips as $chip): ?>
                                <span style="background:rgba(212,175,55,.12);color:var(--gold-dark,#785e0a);border:1.5px solid rgba(212,175,55,.45);border-radius:12px;padding:.12rem .5rem;font-size:.65rem;font-weight:700;">
                                    <i class="fas fa-tag" style="font-size:.55rem;margin-right:2px;"></i><?= e($chip) ?>
                                </span>
                                <?php endforeach; ?>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td><?= e($item['category_name'] ?? '') ?></td>
                        <td>
                            <?php
                            $st = $item['service_type'] ?? 'both';
                            $stMap = [
                                'restaurant'  => ['label' => 'Nhà hàng', 'icon' => 'fa-utensils',   'color' => '#0ea5e9'],
                                'room_service'=> ['label' => 'Room Service','icon'=> 'fa-bed',       'color' => '#8b5cf6'],
                                'both'        => ['label' => 'Cả hai',    'icon' => 'fa-arrows-left-right', 'color' => '#16a34a'],
                            ];
                            $s = $stMap[$st] ?? $stMap['both'];
                            ?>
                            <span style="display:inline-flex;align-items:center;gap:.3rem;background:<?= $s['color'] ?>18;color:<?= $s['color'] ?>;border:1.5px solid <?= $s['color'] ?>44;border-radius:20px;padding:.2rem .65rem;font-size:.72rem;font-weight:700;white-space:nowrap;" data-service="<?= $st ?>">
                                <i class="fas <?= $s['icon'] ?>"></i> <?= $s['label'] ?>
                            </span>
                        </td>
                        <td><strong style="color:var(--gold)">
                                <?= formatPrice($item['price']) ?>
                            </strong></td>
                        <td>
                            <?php if (!isset($item['stock']) || $item['stock'] == -1): ?>
                                <span class="badge" style="background:var(--success);color:#fff">Không giới hạn</span>
                            <?php else: ?>
                                <span class="badge"
                                    style="background:<?= $item['stock'] < 5 ? 'var(--danger)' : 'var(--bg)' ?>;color:<?= $item['stock'] < 5 ? '#fff' : 'var(--text)' ?>">
                                    <?= $item['stock'] ?>
                                </span>
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
                            <div style="display:flex;gap:.4rem;">
                                <a href="<?= BASE_URL ?>/admin/menu/edit?id=<?= $item['id'] ?>"
                                    class="btn btn-outline btn-sm"><i class="fas fa-pen"></i></a>
                                <form method="POST" action="<?= BASE_URL ?>/admin/menu/delete" style="display:inline">
                                    <input type="hidden" name="id" value="<?= $item['id'] ?>">
                                    <button type="submit" class="btn btn-danger-outline btn-sm"
                                        data-confirm="Xóa món '<?= e($item['name']) ?>'?">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af">Chưa có món nào.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
    function applyFilters() {
        const catVal     = document.getElementById('catFilter').value;
        const serviceVal = document.getElementById('serviceFilter').value;
        document.querySelectorAll('#menuTable tbody tr[data-cat]').forEach(row => {
            const catMatch     = !catVal     || row.dataset.cat === catVal;
            const serviceMatch = !serviceVal || (row.querySelector('[data-service]')?.dataset.service === serviceVal);
            row.style.display  = (catMatch && serviceMatch) ? '' : 'none';
        });
    }
    document.getElementById('catFilter').addEventListener('change', applyFilters);
    document.getElementById('serviceFilter').addEventListener('change', applyFilters);

    function toggleItem(id, type, btn) {
        fetch('<?= BASE_URL ?>/admin/menu/toggle', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ id, type })
        })
            .then(r => r.json())
            .then(data => {
                if (!data.ok) return;
                if (type === 'available') {
                    const on = data.is_available == 1;
                    btn.textContent = on ? 'Còn hàng' : 'Hết hàng';
                    btn.className = 'toggle-btn ' + (on ? 'toggle-btn--on' : 'toggle-btn--off');
                } else {
                    const on = data.is_active == 1;
                    btn.innerHTML = '<i class="fas ' + (on ? 'fa-eye' : 'fa-eye-slash') + '"></i>';
                    btn.className = 'toggle-btn ' + (on ? 'toggle-btn--on' : '');
                    btn.title = on ? 'Đang hiện — Click để ẩn' : 'Đang ẩn — Click để hiện';
                }
            });
    }
</script>