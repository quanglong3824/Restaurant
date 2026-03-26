<?php
// views/admin/menu/form.php — Add / Edit menu item
$isEdit = !empty($item);
?>
<div class="card" style="max-width:700px;">
    <div class="card-header">
        <h2>
            <i class="fas fa-<?= $isEdit ? 'pen' : 'plus' ?>"></i>
            <?= $isEdit ? 'Sửa món: ' . e($item['name']) : 'Thêm Món mới' ?>
        </h2>
        <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline btn-sm">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <form method="POST" action="<?= BASE_URL ?>/admin/menu/<?= $isEdit ? 'update' : 'store' ?>"
        enctype="multipart/form-data">
        <?php if ($isEdit): ?>
            <input type="hidden" name="id" value="<?= $item['id'] ?>">
        <?php endif; ?>

        <!-- 2-column responsive grid -->
        <div class="form-grid-2">

            <div class="form-group col-span-2">
                <label class="form-label">Danh mục <span style="color:var(--danger)">*</span></label>
                <select name="category_id" class="form-control" required>
                    <option value="">-- Chọn danh mục --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['id'] ?>" <?= ($isEdit && $item['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="form-group">
                <label class="form-label">Tên món (VI) <span style="color:var(--danger)">*</span></label>
                <input type="text" name="name" class="form-control" required
                    value="<?= $isEdit ? e($item['name']) : '' ?>" placeholder="VD: Bò lúc lắc">
            </div>

            <div class="form-group">
                <label class="form-label">Tên món (EN)</label>
                <input type="text" name="name_en" class="form-control"
                    value="<?= $isEdit ? e($item['name_en'] ?? '') : '' ?>" placeholder="VD: Shaken Beef">
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Mô tả</label>
                <textarea name="description" class="form-control" rows="2"
                    placeholder="Mô tả ngắn về món..."><?= $isEdit ? e($item['description'] ?? '') : '' ?></textarea>
            </div>

            <div class="form-group">
                <label class="form-label">Giá (VND) <span style="color:var(--danger)">*</span></label>
                <input type="number" name="price" class="form-control" required min="0" step="1000"
                    value="<?= $isEdit ? $item['price'] : '' ?>" placeholder="VD: 150000">
            </div>

            <div class="form-group">
                <label class="form-label">Thứ tự hiển thị</label>
                <input type="number" name="sort_order" class="form-control" min="0"
                    value="<?= $isEdit ? $item['sort_order'] : '0' ?>">
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Tags hiển thị</label>
                <div style="display:flex;gap:.85rem;flex-wrap:wrap;padding:.5rem 0;">
                    <?php
                    $allTags = ['bestseller', 'new', 'spicy', 'vegetarian', 'recommended'];
                    // Tách tags thường (không phải opt:)
                    $rawTags = $isEdit ? array_map('trim', explode(',', $item['tags'] ?? '')) : [];
                    $activeTags = array_filter($rawTags, fn($t) => $t && strpos($t, 'opt:') !== 0);
                    foreach ($allTags as $tag):
                        ?>
                        <label style="display:flex;align-items:center;gap:.4rem;font-size:.875rem;cursor:pointer;">
                            <input type="checkbox" name="tags[]" value="<?= $tag ?>" <?= in_array($tag, $activeTags) ? 'checked' : '' ?>>
                            <?= ucfirst($tag) ?>
                        </label>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Tùy chọn món (item_options) — khách có thể chọn khi đặt -->
            <div class="form-group col-span-2">
                <label class="form-label">Tùy chọn cho khách <span style="color:var(--text-muted);font-weight:400;">(khách sẽ thấy và chọn khi gọi món)</span></label>
                <?php
                // Các options hiện tại (lưu dạng opt:xxx trong tags)
                $activeOpts = array_filter($rawTags, fn($t) => strpos($t, 'opt:') === 0);
                $activeOpts = array_map(fn($t) => substr($t, 4), $activeOpts);

                $presetOpts = ['Không cay', 'Ít cay', 'Cay vừa', 'Không hành', 'Không tỏi', 'Không rau mùi',
                               'Ít đường', 'Không đường', 'Ít đá', 'Nhiều đá', 'Không đá', 'Không ngọt',
                               'Ít muối', 'Chín kỹ', 'Tái', 'Không phô mai', 'Ít kem'];
                ?>
                <div id="opts-container" style="display:flex;flex-wrap:wrap;gap:.5rem;padding:.6rem 0;">
                    <?php foreach ($presetOpts as $opt): ?>
                    <label class="opt-chip <?= in_array($opt, $activeOpts) ? 'active' : '' ?>"
                           style="cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;padding:.3rem .75rem;border-radius:20px;font-size:.8rem;border:1.5px solid var(--border-color);transition:all .2s;user-select:none;<?= in_array($opt, $activeOpts) ? 'background:rgba(212,175,55,.15);border-color:var(--gold);color:var(--gold-dark);font-weight:600;' : 'background:transparent;color:var(--text-muted);' ?>">
                        <input type="checkbox" name="item_options[]" value="<?= e($opt) ?>"
                               <?= in_array($opt, $activeOpts) ? 'checked' : '' ?>
                               style="display:none;" onchange="toggleOptChip(this)">
                        <?= e($opt) ?>
                    </label>
                    <?php endforeach; ?>
                </div>
                <!-- Thêm option tùy chỉnh -->
                <div style="display:flex;gap:.5rem;margin-top:.4rem;">
                    <input type="text" id="custom-opt-input" class="form-control" style="max-width:220px;font-size:.85rem;"
                           placeholder="Tùy chọn khác..." onkeydown="if(event.key==='Enter'){event.preventDefault();addCustomOpt();}">
                    <button type="button" class="btn btn-outline btn-sm" onclick="addCustomOpt()">
                        <i class="fas fa-plus"></i> Thêm
                    </button>
                </div>
                <p class="form-hint" style="margin-top:.5rem;">Các tùy chọn được tô vàng sẽ hiển thị cho khách lựa chọn khi gọi món.</p>
            </div>

            <style>
                .opt-chip:hover { background: rgba(212,175,55,.08); border-color: rgba(212,175,55,.5); color: var(--gold-dark); }
                .opt-chip.active { background: rgba(212,175,55,.15); border-color: var(--gold); color: var(--gold-dark); font-weight: 600; }
            </style>
            <script>
                function toggleOptChip(input) {
                    const label = input.closest('label');
                    label.classList.toggle('active', input.checked);
                    if (input.checked) {
                        label.style.background = 'rgba(212,175,55,.15)';
                        label.style.borderColor = 'var(--gold)';
                        label.style.color = 'var(--gold-dark)';
                        label.style.fontWeight = '600';
                    } else {
                        label.style.background = 'transparent';
                        label.style.borderColor = 'var(--border-color)';
                        label.style.color = 'var(--text-muted)';
                        label.style.fontWeight = '';
                    }
                }
                function addCustomOpt() {
                    const input = document.getElementById('custom-opt-input');
                    const val = input.value.trim();
                    if (!val) return;
                    const container = document.getElementById('opts-container');
                    const label = document.createElement('label');
                    label.className = 'opt-chip active';
                    label.style.cssText = 'cursor:pointer;display:inline-flex;align-items:center;gap:.3rem;padding:.3rem .75rem;border-radius:20px;font-size:.8rem;border:1.5px solid var(--gold);background:rgba(212,175,55,.15);color:var(--gold-dark);font-weight:600;user-select:none;';
                    label.innerHTML = `<input type="checkbox" name="item_options[]" value="${val}" checked style="display:none;" onchange="toggleOptChip(this)">${val}`;
                    container.appendChild(label);
                    input.value = '';
                }
                // Init click on entire label
                document.addEventListener('DOMContentLoaded', () => {
                    document.querySelectorAll('.opt-chip').forEach(lbl => {
                        lbl.addEventListener('click', () => {
                            const cb = lbl.querySelector('input[type="checkbox"]');
                            cb.checked = !cb.checked;
                            toggleOptChip(cb);
                        });
                    });
                });
            </script>

            <div class="form-group col-span-2">
                <label class="form-label">Kho (Stock) <span class="text-danger">*</span></label>
                <input type="number" name="stock" class="form-control" value="<?= e($item['stock'] ?? -1) ?>" required>
                <p class="form-hint">Nhập -1 nếu món này không giới hạn số lượng bán.</p>
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Ảnh chính (Ảnh đại diện món)</label>
                <?php if ($isEdit && $item['image']): ?>
                    <div style="margin-bottom:.5rem;">
                        <img src="<?= BASE_URL ?>/public/uploads/<?= e($item['image']) ?>"
                            style="width:80px;height:80px;object-fit:cover;border-radius:8px;border:2px solid var(--border-gold);">
                    </div>
                <?php endif; ?>
                <input type="file" name="image" class="form-control" accept="image/*">
                <p class="form-hint">JPG, PNG, WebP. Tối đa 5MB. Đây là ảnh hiển thị ngoài danh sách.</p>
            </div>

            <div class="form-group col-span-2">
                <label class="form-label">Bộ sưu tập ảnh (Slide Thumbnail Chi Tiết Món)</label>
                <input type="file" name="gallery[]" class="form-control" accept="image/*" multiple>
                <p class="form-hint">Chọn <strong>nhiều ảnh</strong> bằng cách giữ Ctrl/Cmd khi chọn file. Các ảnh này
                    sẽ được ghép thành Gallery trượt ngang khi người dùng xem chi tiết món ăn.</p>
            </div>

            <?php if ($isEdit): ?>
                <div class="form-group">
                    <label class="form-label">Trạng thái hiển thị</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?= $item['is_active'] ? 'selected' : '' ?>>Hiển thị</option>
                        <option value="0" <?= !$item['is_active'] ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
            <?php endif; ?>

        </div><!-- /form-grid-2 -->

        <div style="display:flex;gap:.75rem;margin-top:1rem;">
            <button type="submit" class="btn btn-gold btn-lg">
                <i class="fas fa-save"></i>
                <?= $isEdit ? 'Lưu thay đổi' : 'Thêm món' ?>
            </button>
            <a href="<?= BASE_URL ?>/admin/menu" class="btn btn-outline btn-lg">Huỷ</a>
        </div>

    </form>
</div>