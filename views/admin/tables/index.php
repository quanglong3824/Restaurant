<?php // views/admin/tables/index.php ?>
<link rel="stylesheet" href="<?= asset('public/css/admin/tables.css') ?>">

<div class="content-with-aside content-with-aside--sm">

    <!-- Tabs -->
    <div class="tabs-container">
        <div class="tabs">
            <a href="<?= BASE_URL ?>/admin/tables?type=table" class="tab-item <?= $type === 'table' ? 'active' : '' ?>">
                <i class="fas fa-chair"></i> Bàn Nhà Hàng
            </a>
            <a href="<?= BASE_URL ?>/admin/tables?type=room" class="tab-item <?= $type === 'room' ? 'active' : '' ?>">
                <i class="fas fa-bed"></i> Khách Lưu Trú (Phòng)
            </a>
        </div>
    </div>

    <!-- Table list -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas <?= $type === 'room' ? 'fa-bed' : 'fa-chair' ?>"></i> Danh sách
                <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h2>
            <span class="badge badge-gold"><?= count($tables) ?> <?= $type === 'room' ? 'phòng' : 'bàn' ?></span>
        </div>

        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th><?= $type === 'room' ? 'Số phòng' : 'Tên bàn' ?></th>
                        <th class="table-hide-sm">Khu vực</th>
                        <th class="table-hide-sm">Sức chứa</th>
                        <th>Trạng thái</th>
                        <th>Kích hoạt</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($groupedTables)): ?>
                        <tr>
                            <td colspan="6" class="table-empty-state">
                                Chưa có <?= $type === 'room' ? 'phòng' : 'bàn' ?> nào.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($groupedTables as $area => $areaTables): ?>
                            <!-- Group Header Row -->
                            <tr class="group-header-row">
                                <td colspan="6">
                                    <h3>
                                        <i class="fas fa-layer-group"></i>
                                        Khu vực: <?= e($area) ?>
                                        <span class="badge badge-outline">
                                            <?= count($areaTables) ?> <?= $type === 'room' ? 'phòng' : 'bàn' ?>
                                        </span>
                                    </h3>
                                </td>
                            </tr>

                            <!-- Items in Group -->
                            <?php foreach ($areaTables as $t): ?>
                                <tr>
                                    <td><strong><?= e($t['name']) ?></strong></td>
                                    <td class="table-hide-sm"><?= e($t['area'] ?? '—') ?></td>
                                    <td class="table-hide-sm"><?= $t['capacity'] ?>
                                        <?= $type === 'room' ? 'người' : 'người' ?></td>
                                    <td>
                                        <?php if ($t['status'] === 'occupied'): ?>
                                            <span class="badge badge-danger">
                                                <i class="fas fa-circle"></i> Có khách
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-success">
                                                <i class="fas fa-circle"></i>
                                                <?= $type === 'room' ? 'Sẵn sàng' : 'Trống' ?>
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge <?= $t['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                            <?= $t['is_active'] ? 'Đang dùng' : 'Tạm ẩn' ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div class="table-actions">
                                            <!-- QR Button -->
                                            <button type="button" class="btn btn-outline btn-sm btn-qr"
                                                data-id="<?= $t['id'] ?>" data-name="<?= e($t['name']) ?>"
                                                data-token="<?= e($t['qr_token'] ?? '') ?>" title="Tạo QR">
                                                <i class="fas fa-qrcode"></i>
                                            </button>

                                            <!-- Reset QR Button -->
                                            <button type="button" class="btn btn-outline btn-sm btn-reset-qr"
                                                title="Tạo/Reset mã QR"
                                                onclick="confirmResetQR(<?= $t['id'] ?>, '<?= e($t['name']) ?>', <?= (int) $t['is_printed'] ?>, <?= (int) $t['scan_count'] ?>, <?= (int) $t['items_count'] ?>)">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>

                                            <a href="<?= BASE_URL ?>/admin/tables/edit?id=<?= $t['id'] ?>"
                                                class="btn btn-outline btn-sm" title="Sửa">
                                                <i class="fas fa-pen"></i>
                                            </a>
                                            <?php if ($t['status'] !== 'occupied'): ?>
                                                <form method="POST" action="<?= BASE_URL ?>/admin/tables/delete">
                                                    <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                                    <button type="submit" class="btn btn-danger-outline btn-sm"
                                                        data-confirm="Xóa <?= $type === 'room' ? 'phòng' : 'bàn' ?> '<?= e($t['name']) ?>'?"
                                                        title="Xóa">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            <?php endif; ?>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Add / Edit form -->
    <div class="card sticky-aside">
        <?php if ($editItem): ?>
            <!-- Edit mode -->
            <div class="card-header">
                <h2><i class="fas fa-pen"></i> Sửa <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h2>
                <a href="<?= BASE_URL ?>/admin/tables?type=<?= $type ?>" class="btn btn-outline btn-sm">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/tables/update">
                <input type="hidden" name="id" value="<?= $editItem['id'] ?>">
                <input type="hidden" name="type" value="<?= $type ?>">

                <div class="form-group">
                    <label class="form-label"><?= $type === 'room' ? 'Số phòng' : 'Tên bàn' ?> *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($editItem['name']) ?>"
                        placeholder="VD: <?= $type === 'room' ? '701' : 'Bàn 01' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Khu vực</label>
                    <input type="text" name="area" class="form-control" value="<?= e($editItem['area'] ?? '') ?>"
                        placeholder="VD: <?= $type === 'room' ? 'Tầng 7' : 'Tầng 1, Sân vườn...' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Sức chứa (người)</label>
                    <input type="number" name="capacity" class="form-control" min="1"
                        max="<?= $type === 'room' ? '3' : '20' ?>" value="<?= (int) $editItem['capacity'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" min="0"
                        value="<?= (int) $editItem['sort_order'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Trạng thái</label>
                    <select name="is_active" class="form-control">
                        <option value="1" <?= $editItem['is_active'] ? 'selected' : '' ?>>Đang dùng</option>
                        <option value="0" <?= !$editItem['is_active'] ? 'selected' : '' ?>>Tạm ẩn</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-gold btn-block">
                    <i class="fas fa-save"></i> Lưu thay đổi
                </button>
            </form>

        <?php else: ?>
            <!-- Add mode -->
            <div class="card-header">
                <h2><i class="fas fa-plus"></i> Thêm <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h2>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/tables/store">
                <input type="hidden" name="type" value="<?= $type ?>">
                <div class="form-group">
                    <label class="form-label"><?= $type === 'room' ? 'Số phòng' : 'Tên bàn' ?> *</label>
                    <input type="text" name="name" class="form-control" required
                        placeholder="VD: <?= $type === 'room' ? '701' : 'Bàn 01' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Khu vực</label>
                    <input type="text" name="area" class="form-control"
                        placeholder="VD: <?= $type === 'room' ? 'Tầng 7' : 'Tầng 1, Sân vườn...' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Sức chứa (người)</label>
                    <input type="number" name="capacity" class="form-control" min="1"
                        max="<?= $type === 'room' ? '3' : '20' ?>" value="<?= $type === 'room' ? '3' : '4' ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                </div>
                <button type="submit" class="btn btn-gold btn-block">
                    <i class="fas fa-save"></i> Thêm <?= $type === 'room' ? 'phòng' : 'bàn' ?>
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 id="qrModalTitle">Mã QR <?= $type === 'room' ? 'Phòng' : 'Bàn' ?></h3>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <div class="modal-body" id="printableQrArea">
            <div class="qr-print-header">
                <h1>AURORA HOTEL PLAZA</h1>
                <p>RESTAURANT & BAR</p>
                <div>
                    <h2 id="qrTableDisplay">BÀN 01</h2>
                </div>
            </div>

            <div id="qrcode">
                <div id="qrcode-canvas"></div>
                <img src="<?= BASE_URL ?>/public/src/logo/favicon.png" class="qr-logo-modal" alt="Logo">
            </div>

            <div class="qr-print-footer">
                <p>QUÉT MÃ ĐỂ ĐẶT MÓN</p>
                <p>Cảm ơn Quý khách / Thank you!</p>
            </div>

            <p id="qrUrl"></p>

            <div class="no-print qr-modal-actions">
                <button type="button" class="btn btn-gold" onclick="printQR()">
                    <i class="fas fa-print"></i> In QR
                </button>
                <button type="button" class="btn btn-outline" onclick="downloadQR()">
                    <i class="fas fa-download"></i> Tải ảnh
                </button>
                <button type="button" class="btn btn-outline close-modal">Đóng</button>
            </div>
        </div>
    </div>
</div>
