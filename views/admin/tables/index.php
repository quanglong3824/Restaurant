<?php // views/admin/tables/index.php ?>
<div class="content-with-aside content-with-aside--sm">

    <!-- Table list -->
    <div class="card">
        <div class="card-header">
            <h2><i class="fas fa-chair"></i> Danh sách Bàn</h2>
            <span class="badge badge-gold"><?= count($tables) ?> bàn</span>
        </div>
        <div class="table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Tên bàn</th>
                        <th class="table-hide-sm">Khu vực</th>
                        <th class="table-hide-sm">Sức chứa</th>
                        <th>Trạng thái</th>
                        <th>Kích hoạt</th>
                        <th style="width:160px"></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($tables as $t): ?>
                        <tr>
                            <td><strong><?= e($t['name']) ?></strong></td>
                            <td class="table-hide-sm"><?= e($t['area'] ?? '—') ?></td>
                            <td class="table-hide-sm"><?= $t['capacity'] ?> người</td>
                            <td>
                                <?php if ($t['status'] === 'occupied'): ?>
                                    <span class="badge badge-danger">
                                        <i class="fas fa-circle" style="font-size:.5rem"></i> Có khách
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-success">
                                        <i class="fas fa-circle" style="font-size:.5rem"></i> Trống
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?= $t['is_active'] ? 'badge-success' : 'badge-danger' ?>">
                                    <?= $t['is_active'] ? 'Đang dùng' : 'Tạm ẩn' ?>
                                </span>
                            </td>
                            <td>
                                <div style="display:flex;gap:.4rem;">
                                    <!-- QR Button -->
                                    <button type="button" class="btn btn-outline btn-sm btn-qr" data-id="<?= $t['id'] ?>"
                                        data-name="<?= e($t['name']) ?>" title="Tạo QR">
                                        <i class="fas fa-qrcode"></i>
                                    </button>

                                    <a href="<?= BASE_URL ?>/admin/tables/edit?id=<?= $t['id'] ?>"
                                        class="btn btn-outline btn-sm" title="Sửa">
                                        <i class="fas fa-pen"></i>
                                    </a>
                                    <?php if ($t['status'] !== 'occupied'): ?>
                                        <form method="POST" action="<?= BASE_URL ?>/admin/tables/delete"
                                            style="display:inline;">
                                            <input type="hidden" name="id" value="<?= $t['id'] ?>">
                                            <button type="submit" class="btn btn-danger-outline btn-sm"
                                                data-confirm="Xóa bàn '<?= e($t['name']) ?>'?" title="Xóa">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (empty($tables)): ?>
                        <tr>
                            <td colspan="7" style="text-align:center;padding:2rem;color:#9ca3af;">
                                Chưa có bàn nào.
                            </td>
                        </tr>
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
                <h2><i class="fas fa-pen"></i> Sửa Bàn</h2>
                <a href="<?= BASE_URL ?>/admin/tables" class="btn btn-outline btn-sm">
                    <i class="fas fa-times"></i>
                </a>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/tables/update">
                <input type="hidden" name="id" value="<?= $editItem['id'] ?>">

                <div class="form-group">
                    <label class="form-label">Tên bàn *</label>
                    <input type="text" name="name" class="form-control" required value="<?= e($editItem['name']) ?>"
                        placeholder="VD: Bàn 01">
                </div>
                <div class="form-group">
                    <label class="form-label">Khu vực</label>
                    <input type="text" name="area" class="form-control" value="<?= e($editItem['area'] ?? '') ?>"
                        placeholder="VD: Tầng 1, Sân vườn...">
                </div>
                <div class="form-group">
                    <label class="form-label">Sức chứa (người)</label>
                    <input type="number" name="capacity" class="form-control" min="1" max="20"
                        value="<?= (int) $editItem['capacity'] ?>">
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
                <h2><i class="fas fa-plus"></i> Thêm Bàn</h2>
            </div>
            <form method="POST" action="<?= BASE_URL ?>/admin/tables/store">
                <div class="form-group">
                    <label class="form-label">Tên bàn *</label>
                    <input type="text" name="name" class="form-control" required placeholder="VD: Bàn 01">
                </div>
                <div class="form-group">
                    <label class="form-label">Khu vực</label>
                    <input type="text" name="area" class="form-control" placeholder="VD: Tầng 1, Sân vườn...">
                </div>
                <div class="form-group">
                    <label class="form-label">Sức chứa (người)</label>
                    <input type="number" name="capacity" class="form-control" min="1" max="20" value="4">
                </div>
                <div class="form-group">
                    <label class="form-label">Thứ tự hiển thị</label>
                    <input type="number" name="sort_order" class="form-control" value="0" min="0">
                </div>
                <button type="submit" class="btn btn-gold btn-block">
                    <i class="fas fa-save"></i> Thêm bàn
                </button>
            </form>
        <?php endif; ?>
    </div>
</div>

<!-- QR Modal -->
<div id="qrModal" class="modal">
    <div class="modal-content" style="max-width: 400px; text-align: center;">
        <div class="modal-header">
            <h3 id="qrModalTitle">Mã QR Bàn</h3>
            <button type="button" class="close-modal">&times;</button>
        </div>
        <div class="modal-body" style="padding: 2rem;">
            <div id="qrcode" style="display: flex; justify-content: center; margin-bottom: 1.5rem;"></div>
            <p id="qrUrl" style="font-size: 0.8rem; color: #666; word-break: break-all; margin-bottom: 1rem;"></p>
            <div style="display: flex; gap: 1rem; justify-content: center;">
                <button type="button" class="btn btn-gold" onclick="printQR()">
                    <i class="fas fa-print"></i> In mã QR
                </button>
                <button type="button" class="btn btn-outline close-modal">Đóng</button>
            </div>
        </div>
    </div>
</div>

<style>
    /* QR Modal Styles - Fixed Centering */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        backdrop-filter: blur(8px);
        overflow-y: auto;
        padding: 20px;
    }

    .modal-content {
        background-color: #fff;
        margin: 40px auto;
        border-radius: 20px;
        box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        overflow: hidden;
        position: relative;
        max-width: 400px;
        width: 100%;
    }

    .modal-header {
        padding: 1.5rem;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
        background: #fafafa;
    }

    .modal-header h3 {
        margin: 0;
        color: #1a1a1a;
        font-weight: 700;
    }

    .close-modal {
        background: #eee;
        border: none;
        width: 32px;
        height: 32px;
        border-radius: 50%;
        font-size: 1.2rem;
        cursor: pointer;
        color: #666;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.2s;
    }

    .close-modal:hover {
        background: #e0e0e0;
        color: #000;
    }

    .modal-body {
        padding: 2rem;
        text-align: center;
    }

    #qrcode {
        background: #fff;
        padding: 15px;
        border-radius: 12px;
        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
        display: inline-block !important;
        /* Force inline-block for centering */
    }

    #qrcode img {
        display: block;
        margin: 0 auto;
    }

    @media print {
        body * {
            visibility: hidden;
        }

        #qrModal,
        #qrModal * {
            visibility: visible;
        }

        .modal {
            background: none;
            backdrop-filter: none;
            position: static;
            display: block;
            padding: 0;
        }

        .modal-content {
            box-shadow: none;
            margin: 0;
            width: 100%;
            max-width: none;
        }

        .modal-header,
        .close-modal,
        button,
        #qrUrl {
            display: none !important;
        }

        #qrcode {
            position: absolute;
            left: 50%;
            top: 20%;
            transform: translateX(-50%);
            box-shadow: none;
            padding: 0;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('qrModal');
        const qrContainer = document.getElementById('qrcode');
        const qrUrlText = document.getElementById('qrUrl');
        const qrTitle = document.getElementById('qrModalTitle');
        const closeBtns = document.querySelectorAll('.close-modal');

        let qr = null;

        document.querySelectorAll('.btn-qr').forEach(btn => {
            btn.addEventListener('click', () => {
                const tableId = btn.dataset.id;
                const tableName = btn.dataset.name;

                // Generate URL: Always use production URL for the QR content so it works after printing
                const productionUrl = 'https://aurorahotelplaza.com/restaurant';
                const fullUrl = `${productionUrl}/menu?table_id=${tableId}`;

                qrTitle.innerText = `Mã QR: ${tableName}`;
                qrUrlText.innerText = fullUrl;

                // Clear old QR
                qrContainer.innerHTML = '';

                // Create New QR
                qr = new QRCode(qrContainer, {
                    text: fullUrl,
                    width: 256,
                    height: 256,
                    colorDark: "#1a1a1a",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H
                });

                modal.style.display = 'block';
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.style.display = 'none';
            });
        });

        window.onclick = (event) => {
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        };
    });

    function printQR() {
        window.print();
    }
</script>