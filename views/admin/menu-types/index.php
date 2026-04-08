<?php // views/admin/menu-types/index.php ?>
<div class="content-with-2-col">

    <!-- Type list -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-layer-group"></i> Phân Loại Menu</h2>
            <span class="badge badge-gold"><?= count($types) ?> loại</span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Icon</th>
                        <th>Màu</th>
                        <th>Tên VI</th>
                        <th>Tên EN</th>
                        <th>Mã</th>
                        <th>Số DM</th>
                        <th>Thứ tự</th>
                        <th>Trạng thái</th>
                        <th style="width:120px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($types as $type): ?>
                        <tr>
                            <td>
                                <i class="fas <?= e($type['icon'] ?? 'fa-utensils') ?>"
                                    style="color:<?= e($type['color'] ?? '#0ea5e9') ?>;font-size:1.1rem;"></i>
                            </td>
                            <td>
                                <span style="display:inline-block;width:20px;height:20px;border-radius:4px;background:<?= e($type['color'] ?? '#0ea5e9') ?>;border:1px solid #ddd;"></span>
                            </td>
                            <td><strong><?= e($type['name']) ?></strong></td>
                            <td style="color:#9ca3af;"><?= e($type['name_en'] ?? '') ?></td>
                            <td><code style="background:#f1f5f9;padding:2px 6px;border-radius:4px;font-size:0.85em;"><?= e($type['type_key']) ?></code></td>
                            <td>
                                <span class="badge <?= ($categoryCounts[$type['id']] ?? 0) > 0 ? 'badge-info' : 'badge-secondary' ?>">
                                    <?= $categoryCounts[$type['id']] ?? 0 ?>
                                </span>
                            </td>
                            <td><?= $type['sort_order'] ?></td>
                            <td>
                                <span class="badge <?= $type['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                    <?= $type['is_active'] ? 'Hiện' : 'Ẩn' ?>
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:.4rem;">
                                    <a href="<?= BASE_URL ?>/admin/menu-types/edit?id=<?= $type['id'] ?>"
                                        class="btn btn-outline btn-sm" title="Sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <form method="POST" action="<?= BASE_URL ?>/admin/menu-types/delete"
                                        style="display:inline;">
                                        <input type="hidden" name="id" value="<?= $type['id'] ?>">
                                        <button type="submit" class="btn btn-danger-outline btn-sm"
                                            data-confirm="Xóa loại menu '<?= e($type['name']) ?>'?" title="Xóa">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($types)): ?>
                        <tr>
                            <td colspan="9" style="text-align:center;padding:2rem;color:#9ca3af;">
                                Chưa có loại menu nào.
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add / Edit form (sticky aside) -->
    <div class="card sticky-aside">
        <?php if ($editItem): ?>
            <!-- Edit mode -->
            <div class="card-header">
                <h2><i class="fas fa-pen"></i> Sửa Loại Menu</h2>
                <a href="<?= BASE_URL ?>/admin/menu-types" class="btn btn-outline btn-sm">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/menu-types/update">
                <input type="hidden" name="id" value="<?= $editItem['id'] ?>">

                <div class="form-group">
                    <label class="form-label">Tên (VI) *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($editItem['name']) ?>"
                        placeholder="VD: Món Á">
                </div>
                <div class="form-group">
                    <label class="form-label">Tên (EN)</label>
                    <input type="text" name="name_en" class="form-control" value="<?= e($editItem['name_en'] ?? '') ?>"
                        placeholder="VD: Asian Cuisine">
                </div>
                <div class="form-group">
                    <label class="form-label">Mã loại (key) *</label>
                    <input type="text" name="type_key" class="form-control" required value="<?= e($editItem['type_key']) ?>"
                        placeholder="VD: asia" pattern="[a-z_]+" title="Chỉ dùng chữ thường và dấu gạch dưới">
                    <p class="form-hint">Chỉ dùng chữ thường, số và dấu gạch dưới (vd: asia, europe, alacarte)</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Mô tả ngắn về loại menu..."><?= e($editItem['description'] ?? '') ?></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Màu sắc</label>
                    <input type="color" name="color" class="form-control form-control-color" value="<?= e($editItem['color'] ?? '#0ea5e9') ?>"
                        style="width:100%;height:40px;cursor:pointer;">
                </div>
                <div class="form-group">
                    <label class="form-label">Icon (Font Awesome)</label>
                    <input type="text" name="icon" class="form-control" value="<?= e($editItem['icon'] ?? 'fa-utensils') ?>"
                        placeholder="fa-utensils">
                    <p class="form-hint">Xem icon tại <a href="https://fontawesome.com/icons" target="_blank"
                            rel="noopener">fontawesome.com</a></p>
                </div>
                <div class="form-group">
                    <label class="form-label">Thứ tự</label>
                    <input type="number" name="sort_order" class="form-control" min="0"
                        value="<?= (int) $editItem['sort_order'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?= $editItem['is_active'] ? 'selected' : '' ?>>Hiện</option>
                        <option value="0" <?= !$editItem['is_active'] ? 'selected' : '' ?>>Ẩn</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-gold btn-block">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </form>

        <?php else: ?>
            <!-- Add mode -->
            <div class="card-header">
                <h2><i class="fas fa-plus"></i> Thêm Loại Menu</h2>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/menu-types/store">
                <div class="form-group">
                    <label class="form-label">Tên (VI) *</label>
                    <input type="text" name="name" class="form-control" required placeholder="VD: Món Á">
                </div>
                <div class="form-group">
                    <label class="form-label">Tên (EN)</label>
                    <input type="text" name="name_en" class="form-control" placeholder="VD: Asian Cuisine">
                </div>
                <div class="form-group">
                    <label class="form-label">Mã loại (key) *</label>
                    <input type="text" name="type_key" class="form-control" required placeholder="VD: asia" pattern="[a-z_]+" title="Chỉ dùng chữ thường và dấu gạch dưới">
                    <p class="form-hint">Chỉ dùng chữ thường, số và dấu gạch dưới (vd: asia, europe, alacarte)</p>
                </div>
                <div class="form-group">
                    <label class="form-label">Mô tả</label>
                    <textarea name="description" class="form-control" rows="3" placeholder="Mô tả ngắn về loại menu..."></textarea>
                </div>
                <div class="form-group">
                    <label class="form-label">Màu sắc</label>
                    <input type="color" name="color" class="form-control form-control-color" value="#0ea5e9"
                        style="width:100%;height:40px;cursor:pointer;">
                </div>
                <div class="form-group">
                    <label class="form-label">Icon (Font Awesome)</label>
                    <input type="text" name="icon" class="form-control" value="fa-utensils" placeholder="fa-utensils">
                    <p class="form-hint">Xem icon tại <a href="https://fontawesome.com/icons" target="_blank"
                            rel="noopener">fontawesome.com</a></p>
                </div>
                <div class="form-group">
                    <label class="form-label">Thứ tự</label>
                    <input type="number" name="sort_order" class="form-control" value="0" min="0" readonly style="background:#f1f5f9;cursor:not-allowed;">
                    <p class="form-hint">Thứ tự sẽ tự động gán khi lưu (số lớn nhất + 1).</p>
                </div>
                <button type="submit" class="btn btn-gold btn-block">
                    <i class="fas fa-save"></i> Lưu loại menu
                </button>
            </form>
        <?php endif; ?>
    </div>

</div>

<style>
.content-with-2-col {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 1.5rem;
    padding: 1.5rem;
}

@media (max-width: 1024px) {
    .content-with-2-col {
        grid-template-columns: 1fr;
    }
}

.sticky-aside {
    position: sticky;
    top: 1rem;
    align-self: start;
}

.form-control-color {
    padding: 0.25rem;
    border: 1px solid var(--border-color, #e5e7eb);
    border-radius: 8px;
}
</style>