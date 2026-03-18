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
                                        data-name="<?= e($t['name']) ?>" data-token="<?= e($t['qr_token'] ?? '') ?>" title="Tạo QR">
                                        <i class="fas fa-qrcode"></i>
                                    </button>

                                    <!-- Reset QR Button -->
                                    <button type="button" class="btn btn-outline btn-sm" style="color:var(--warning);" title="Tạo/Reset mã QR"
                                        onclick="confirmResetQR(<?= $t['id'] ?>, '<?= e($t['name']) ?>', <?= (int)$t['is_printed'] ?>, <?= (int)$t['scan_count'] ?>, <?= (int)$t['items_count'] ?>)">
                                        <i class="fas fa-sync-alt"></i>
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
    <div class="modal-content" style="max-width: 400px; width: 90%; border: none; overflow: hidden; margin: 0;">
        <div class="modal-header" style="background: #fcfaf5; border-bottom: 1px solid #f1e6d0; padding: 1rem 1.25rem;">
            <h3 id="qrModalTitle" style="font-family: 'Playfair Display', serif; color: #b8860b; font-weight: 700; font-size: 1.1rem; margin: 0;">Mã QR: Bàn A.01</h3>
            <button type="button" class="close-modal" style="background: #fff; border: 1px solid #eee; width: 30px; height: 30px; font-size: 1rem;">&times;</button>
        </div>
        <div class="modal-body" id="printableQrArea" style="padding: 1.5rem 1.25rem;">
            <!-- UI Header (Premium Look) -->
            <div class="qr-ui-header" style="text-align: center; margin-bottom: 1.25rem;">
                <i class="fas fa-utensils" style="color: #d4af37; font-size: 1.2rem; margin-bottom: 0.3rem;"></i>
                <h4 style="font-family: 'Playfair Display', serif; letter-spacing: 2px; margin: 0; font-size: 1rem; font-weight: 800;">AURORA</h4>
                <p style="font-size: 0.55rem; letter-spacing: 3px; color: #999; margin: 0; text-transform: uppercase;">Restaurant & Bar</p>
            </div>

            <!-- QR Container -->
            <div class="qr-wrapper" style="position: relative; background: #fff; padding: 8px; border-radius: 16px; box-shadow: 0 8px 30px rgba(0,0,0,0.06); margin-bottom: 1.5rem;">
                <div id="qrcode" style="display: flex; justify-content: center; position: relative; padding: 10px;">
                    <div id="qrcode-canvas"></div>
                    <img src="<?= BASE_URL ?>/public/src/logo/favicon.png" class="qr-logo-modal" alt="Logo">
                </div>
                <div style="text-align: center; margin-top: -5px; padding-bottom: 8px;">
                    <span id="qrTableLabel" style="background: #d4af37; color: #fff; padding: 3px 12px; border-radius: 50px; font-size: 0.75rem; font-weight: 700; letter-spacing: 1px;">BÀN A.01</span>
                </div>
            </div>
            
            <p id="qrUrl" style="font-size: 0.65rem; color: #ccc; word-break: break-all; margin-bottom: 1.5rem; text-align: center; font-family: monospace; opacity: 0.5;"></p>
            
            <div style="display: flex; gap: 0.75rem; justify-content: center;" class="no-print">
                <button type="button" class="btn btn-gold" onclick="printQR()" style="padding: 0.6rem 1.2rem; border-radius: 10px; font-size: 0.9rem; flex: 1; justify-content: center;">
                    <i class="fas fa-print me-1"></i> In QR
                </button>
                <button type="button" class="btn btn-outline" onclick="downloadQR()" style="padding: 0.6rem 1rem; border-radius: 10px; border-color: #eee; color: #666; font-size: 0.9rem;">
                    <i class="fas fa-download"></i>
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    /* QR Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 9999;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(15, 12, 8, 0.85);
        backdrop-filter: blur(10px);
        display: none; /* Controlled by JS */
        align-items: center; /* Center vertically */
        justify-content: center; /* Center horizontally */
    }

    .modal-content {
        background-color: #fff;
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(0, 0, 0, 0.6);
        position: relative;
        animation: modalScaleUp 0.4s cubic-bezier(0.165, 0.84, 0.44, 1);
    }

    @keyframes modalScaleUp {
        from { opacity: 0; transform: scale(0.95) translateY(10px); }
        to { opacity: 1; transform: scale(1) translateY(0); }
    }

    .qr-logo-modal {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 48px;
        height: 48px;
        background: white;
        padding: 5px;
        border-radius: 12px;
        border: 1px solid #f0f0f0;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        z-index: 10;
    }

    #qrcode-canvas img {
        border-radius: 8px;
        background: white;
        max-width: 100%;
        height: auto !important;
    }

    @media print {
        body * { visibility: hidden; }
        #qrModal, #qrModal * { visibility: visible; }
        .modal { position: absolute; left: 0; top: 0; background: #fff; padding: 0; display: flex !important; }
        .modal-content { box-shadow: none; margin: 0; border: none; width: 100%; max-width: none; background: #fff; transform: none !important; }
        .no-print, .modal-header, #qrUrl, .qr-ui-header, #qrTableLabel { display: none !important; }
        .qr-print-header, .qr-print-footer { display: block !important; }
        #printableQrArea { padding: 50px !important; display: flex; flex-direction: column; align-items: center; justify-content: center; min-height: 100vh; }
        .qr-wrapper { box-shadow: none !important; padding: 0 !important; margin-bottom: 30px !important; }
        #qrcode { padding: 0 !important; }
        .qr-logo-modal { width: 90px; height: 90px; border-radius: 20px; padding: 10px; border-width: 2px; }
        #qrcode-canvas img { width: 500px !important; height: 500px !important; }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('qrModal');
        const qrContainer = document.getElementById('qrcode-canvas');
        const qrUrlText = document.getElementById('qrUrl');
        const qrTitle = document.getElementById('qrModalTitle');
        const qrTableDisplay = document.getElementById('qrTableDisplay');
        const qrTableLabel = document.getElementById('qrTableLabel');
        const closeBtns = document.querySelectorAll('.close-modal');

        document.querySelectorAll('.btn-qr').forEach(btn => {
            btn.addEventListener('click', () => {
                const tableId = btn.dataset.id;
                const tableName = btn.dataset.name;
                const token = btn.dataset.token;
                
                if (!token) {
                    if (confirm('Bàn này chưa có mã QR định danh. Bạn có muốn hệ thống tự động tạo mã ngay bây giờ?')) {
                        const form = document.createElement('form');
                        form.method = 'POST';
                        form.action = '<?= BASE_URL ?>/admin/qr-codes/generate';
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'table_id';
                        input.value = tableId;
                        form.appendChild(input);
                        document.body.appendChild(form);
                        form.submit();
                    }
                    return;
                }

                const fullUrl = `<?= BASE_URL ?>/q?t=${token}`;

                qrTitle.innerText = `Mã QR: Bàn ${tableName}`;
                if (qrTableDisplay) qrTableDisplay.innerText = `BÀN ${tableName.toUpperCase()}`;
                qrTableLabel.innerText = `BÀN ${tableName.toUpperCase()}`;
                qrUrlText.innerText = fullUrl;
                qrContainer.innerHTML = '';

                new QRCode(qrContainer, {
                    text: fullUrl,
                    width: 260,
                    height: 260,
                    colorDark: "#000000",
                    colorLight: "#ffffff",
                    correctLevel: QRCode.CorrectLevel.H,
                    margin: 0
                });

                modal.style.display = 'flex'; // Use flex instead of block for centering
                document.body.style.overflow = 'hidden';
            });
        });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            });
        });

        window.onclick = (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        };
    });

        closeBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            });
        });

        window.onclick = (e) => {
            if (e.target == modal) {
                modal.style.display = 'none';
                document.body.style.overflow = '';
            }
        };
    });

    function printQR() {
        window.print();
    }

    function downloadQR() {
        const img = document.querySelector('#qrcode-canvas img');
        if (!img) return;
        const link = document.createElement('a');
        link.download = `QR-${document.getElementById('qrTableDisplay').innerText}.png`;
        link.href = img.src;
        link.click();
    }

    function confirmResetQR(tableId, tableName, isPrinted, scanCount, itemsCount) {
        let message = `Bạn có chắc chắn muốn làm mới mã QR cho ${tableName}?\n\n`;
        
        if (itemsCount > 0) {
            alert(`CẢNH BÁO: Bàn ${tableName} đang có khách đã đặt món (${itemsCount} món).\n\nVui lòng hoàn tất đơn hàng và thanh toán trước khi reset QR.`);
            return;
        }

        if (isPrinted) {
            if (!confirm(`Mã QR của ${tableName} ĐÃ ĐƯỢC IN ra giấy.\n\nNếu bạn reset, mã QR cũ trên giấy sẽ không còn tác dụng và khách không thể quét được nữa.\n\nBạn có CHẮC CHẮN vẫn muốn tạo mã mới?`)) {
                return;
            }
        } else if (scanCount > 0) {
             if (!confirm(`Mã QR này đã được quét ${scanCount} lần.\n\nBạn có chắc chắn muốn reset không?`)) {
                return;
            }
        } else {
            if (!confirm(`Xác nhận tạo mã QR mới cho ${tableName}?`)) {
                return;
            }
        }

        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '<?= BASE_URL ?>/admin/qr-codes/generate';
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'table_id';
        input.value = tableId;
        form.appendChild(input);
        document.body.appendChild(form);
        form.submit();
    }
</script>