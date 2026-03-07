<?php // views/it/database.php ?>
<div class="card">
    <div class="card-header">
        <h2><i class="fas fa-database"></i> Quản lý Cơ sở dữ liệu</h2>
    </div>
    <div class="card-body" style="padding: 2rem;">
        <div class="row" style="display: flex; gap: 2rem; flex-wrap: wrap;">
            <div style="flex: 1; min-width: 300px;">
                <h3 style="margin-bottom: 1rem; color: var(--gold);">Sao lưu dữ liệu</h3>
                <p style="margin-bottom: 1.5rem; color: var(--text-muted); line-height: 1.6;">
                    Tải về toàn bộ cấu trúc và dữ liệu của hệ thống dưới dạng tệp tin <code>.sql</code>. 
                    Bạn nên thực hiện việc này định kỳ để đảm bảo an toàn dữ liệu.
                </p>
                <a href="<?= BASE_URL ?>/it/database/backup" class="btn btn-gold">
                    <i class="fas fa-download"></i> Tải bản sao lưu (.sql)
                </a>
            </div>
            
            <div style="flex: 1; min-width: 300px; border-left: 1px solid var(--border); padding-left: 2rem;">
                <h3 style="margin-bottom: 1rem; color: var(--danger);">Phục hồi dữ liệu</h3>
                <p style="margin-bottom: 1.5rem; color: var(--text-muted); line-height: 1.6;">
                    Tính năng phục hồi dữ liệu từ tệp tin <code>.sql</code>. 
                    <br><strong>Cảnh báo:</strong> Việc này sẽ ghi đè toàn bộ dữ liệu hiện tại của hệ thống.
                </p>
                <button class="btn btn-outline" disabled title="Tính năng đang phát triển">
                    <i class="fas fa-upload"></i> Phục hồi dữ liệu
                </button>
                <p class="form-hint" style="margin-top: .5rem;">Tính năng phục hồi đang được cập nhật trong phiên bản tới.</p>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-top: 2rem;">
    <div class="card-header">
        <h2><i class="fas fa-info-circle"></i> Thông tin hệ thống</h2>
    </div>
    <div class="table-wrap">
        <table>
            <tbody>
                <tr>
                    <td style="font-weight: 600; width: 200px;">Database Name</td>
                    <td><code><?= e(DB_NAME) ?></code></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Host</td>
                    <td><code><?= e(DB_HOST) ?></code></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Phiên bản PHP</td>
                    <td><?= phpversion() ?></td>
                </tr>
                <tr>
                    <td style="font-weight: 600;">Kích thước Database</td>
                    <td>
                        <?php
                        $db = getDB();
                        $q = $db->query("SELECT SUM(data_length + index_length) / 1024 / 1024 AS size FROM information_schema.TABLES WHERE table_schema = '" . DB_NAME . "'");
                        $size = $q->fetch()['size'];
                        echo round($size, 2) . ' MB';
                        ?>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
