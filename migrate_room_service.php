<?php
// ============================================================
// migrate_room_service.php
// Chạy 1 lần để: thêm cột service_type + seed dữ liệu mô phỏng
// SAU KHI CHẠY XONG → XÓA FILE NÀY ĐỂ BẢO MẬT
// ============================================================

// Guard: chỉ chạy trực tiếp, không include
if (!defined('BASE_PATH')) {
    define('BASE_PATH', __DIR__);
}
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

$db  = getDB();
$log = [];

function runSQL(PDO $db, string $sql, string $label, array &$log): void {
    $sql = trim($sql);
    if (!$sql) return;
    try {
        $db->exec($sql);
        $log[] = ['ok' => true,  'label' => $label, 'msg' => 'Thành công'];
    } catch (PDOException $e) {
        $msg = $e->getMessage();
        // Bỏ qua lỗi "duplicate column" hoặc "already exists"
        if (str_contains($msg, 'Duplicate column') || str_contains($msg, 'already exists')) {
            $log[] = ['ok' => true, 'label' => $label, 'msg' => 'Đã tồn tại (bỏ qua)'];
        } else {
            $log[] = ['ok' => false, 'label' => $label, 'msg' => $msg];
        }
    }
}

// ── 1. Thêm cột service_type ─────────────────────────────
runSQL($db,
    "ALTER TABLE `menu_items`
     ADD COLUMN `service_type` ENUM('restaurant','room_service','both') NOT NULL DEFAULT 'both' AFTER `is_active`",
    'Thêm cột service_type',
    $log
);

// Thêm cột stock nếu chưa có
runSQL($db,
    "ALTER TABLE `menu_items`
     ADD COLUMN `stock` int(11) NOT NULL DEFAULT '-1' AFTER `service_type`",
    'Thêm cột stock',
    $log
);

// ── 2. Thêm danh mục mới ─────────────────────────────────
$newCategories = [
    [30, 'Đồ Uống',         'Beverages',       'other',    'fa-mug-hot',   1],
    [31, 'Ăn Sáng',         'Breakfast',        'other',    'fa-cloud-sun', 2],
    [32, 'Món Nhẹ & Snack', 'Light Meals',      'other',    'fa-burger',    3],
    [33, 'Tráng Miệng',     'Desserts',         'other',    'fa-ice-cream', 4],
    [34, 'Mì - Cháo - Súp', 'Noodles & Soups',  'asia',     'fa-bowl-food', 7],
    [35, 'Cơm & Món Chính', 'Rice & Mains',     'asia',     'fa-bowl-rice', 8],
];

$stmtCat = $db->prepare(
    "INSERT IGNORE INTO `menu_categories`
     (`id`,`name`,`name_en`,`menu_type`,`icon`,`sort_order`,`is_active`)
     VALUES (?,?,?,?,?,?,1)"
);
foreach ($newCategories as $c) {
    $stmtCat->execute($c);
    $log[] = ['ok' => true, 'label' => "Danh mục [{$c[1]}]", 'msg' => 'Đã thêm / bỏ qua nếu đã tồn tại'];
}

// ── 3. Cập nhật service_type cho món cũ ──────────────────
$oldItems = [
    ['both',       [2, 55]],
    ['restaurant', [62, 88, 89, 90, 149, 150]],
];
foreach ($oldItems as [$type, $ids]) {
    $in  = implode(',', $ids);
    runSQL($db,
        "UPDATE `menu_items` SET `service_type` = '$type', `is_active`=1, `is_available`=1 WHERE `id` IN ($in)",
        "Cập nhật service_type=$type cho id ($in)",
        $log
    );
}

// ── 4. Seed món mô phỏng ─────────────────────────────────
$items = [
  // [cat_id, name, name_en, description, price, service_type, tags, note_vi, note_en, sort]

  // ĐỒ UỐNG (30) — both
  [30,'Cà Phê Đen','Black Coffee','Cà phê phin truyền thống, thơm đậm vị',35000,'both','bestseller','Đường riêng,Không đường,Đá riêng','Sugar on side,No sugar,Ice on side',1],
  [30,'Cà Phê Sữa Đá','Iced Milk Coffee','Cà phê phin pha sữa đặc, rót đá lạnh',45000,'both','bestseller','Ít ngọt,Bình thường,Nhiều sữa','Less sweet,Normal,Extra milk',2],
  [30,'Trà Đào Cam Sả','Peach Lemongrass Tea','Trà thảo mộc thơm vị đào và cam sả',55000,'both','recommended','Ít đá,Không đường,Đá riêng','Less ice,No sugar,Ice on side',3],
  [30,'Nước Ép Cam Tươi','Fresh Orange Juice','Cam vắt tươi 100%, không thêm đường',65000,'both','recommended','Không đường,Có đường,Ít đá','No sugar,With sugar,Less ice',4],
  [30,'Trà Xanh Jasmine','Jasmine Green Tea','Trà xanh ướp hoa lài, pha nóng hoặc đá',40000,'both',null,'Pha nóng,Pha đá,Ít đường','Hot,Iced,Less sugar',5],
  [30,'Bia Tiger Lon','Tiger Beer Can','Bia Tiger 330ml lon lạnh',45000,'restaurant',null,null,null,6],
  [30,'Rượu Vang Đỏ (ly)','Red Wine (glass)','Vang đỏ Chile nhập khẩu, phục vụ theo ly',120000,'restaurant',null,null,null,7],
  [30,'Nước Suối Aquafina','Mineral Water','Nước suối Aquafina 500ml',20000,'both',null,null,null,8],
  [30,'Sữa Tươi Vinamilk','Fresh Milk','Sữa tươi không đường Vinamilk 200ml',30000,'room_service',null,'Nóng,Lạnh','Hot,Cold',9],
  [30,'Sinh Tố Bơ','Avocado Smoothie','Bơ Đắk Lắk, sữa tươi, đường cát',75000,'both','recommended','Ít đường,Không đường','Less sugar,No sugar',10],

  // ĂN SÁNG (31) — room_service / both
  [31,'Phở Bò Tái Chín','Beef Pho','Phở bò truyền thống, nước dùng hầm 12h',95000,'both','bestseller','Tái,Chín,Hành trần,Không hành','Rare,Well-done,Blanched onion,No onion',1],
  [31,'Bánh Mì Trứng Ốp La','Egg Sandwich','Bánh mì giòn, 2 trứng ốp la, pate và rau',55000,'both',null,'Ít muối,Không pate,Thêm trứng','Less salt,No pate,Extra egg',2],
  [31,'Cháo Trắng Sườn Non','Pork Rib Congee','Cháo nấu từ gạo tám, sườn non mềm',75000,'room_service','recommended','Ít hành,Không tiêu,Loãng hơn','Less onion,No pepper,Thinner',3],
  [31,'Trứng Chiên Xúc Xích','Egg & Sausage','Trứng chiên + 2 xúc xích, kèm bánh mì',65000,'room_service',null,'Trứng ốp,Trứng đánh,Không bánh mì','Sunny-side up,Scrambled,No bread',4],
  [31,'Sandwich Thịt Nguội','Cold Cut Sandwich','Bánh sandwich giăm bông, phô mai, rau xà lách',70000,'room_service',null,'Toasted,Không toasted,Không phô mai','Toasted,Not toasted,No cheese',5],
  [31,'Hủ Tiếu Nam Vang','Phnom Penh Noodles','Hủ tiếu dai, thịt bằm, tôm tươi, nước trong',90000,'both','recommended','Khô,Nước,Ít hành,Không tỏi','Dry,Soup,Less onion,No garlic',6],
  [31,'Granola & Sữa Chua','Granola Yogurt','Granola hạt + sữa chua tươi + mứt dâu',80000,'room_service','new',null,null,7],

  // MÓN NHẸ (32) — both / room
  [32,'Khoai Tây Chiên Giòn','French Fries','Khoai tây chiên ORIDA, muối thảo mộc',65000,'both','bestseller','Nhiều muối,Ít muối,Thêm tương cà','Extra salt,Less salt,With ketchup',1],
  [32,'Gà Rán Giòn (4 miếng)','Fried Chicken (4pcs)','Gà rán giòn sốt ngọt mật ong',145000,'both','recommended','Sốt cay,Sốt ngọt,Không sốt','Spicy sauce,Sweet sauce,No sauce',2],
  [32,'Bánh Mì Nướng Bơ Tỏi','Garlic Butter Toast','Bánh mì nướng bơ + tỏi thơm, phục vụ nóng',45000,'room_service',null,null,null,3],
  [32,'Xúc Xích Que Nướng','Grilled Sausage Skewers','4 que xúc xích BBQ nướng than, kèm mù tạt',95000,'both',null,'Ít mù tạt,Thêm mù tạt,Kèm tương ớt','Less mustard,Extra mustard,With chili sauce',4],
  [32,'Phô Mai Que Chiên','Mozzarella Sticks','Phô mai mozzarella chiên giòn, chấm sốt cà chua',95000,'room_service','new',null,null,5],
  [32,'Nachos & Salsa','Nachos & Salsa','Bánh nachos bắp, sốt salsa cà chua tươi',85000,'restaurant',null,'Thêm jalapeño,Không jalapeño','Extra jalapeño,No jalapeño',6],

  // MÌ - CHÁO - SÚP (34)
  [34,'Mì Tôm Trứng','Ramen & Egg','Mì tôm hảo hảo + 2 trứng + rau cải',55000,'room_service','bestseller','Nhiều nước,Ít cay,Thêm trứng','More broth,Less spicy,Extra egg',1],
  [34,'Bún Bò Huế','Hue Style Beef Noodles','Bún bò Huế cay nồng đặc trưng miền Trung',110000,'both','recommended','Ít cay,Không sả,Thêm chả','Less spicy,No lemongrass,Extra sausage',2],
  [34,'Súp Khoai Tây Kem','Cream of Potato Soup','Súp khoai tây kem kiểu Tây, thêm bacon',75000,'both',null,'Không bacon,Thêm kem,Không tiêu','No bacon,Extra cream,No pepper',3],
  [34,'Cháo Gà Nấm','Chicken Mushroom Congee','Cháo gà ta nấu với nấm hương, ăn kèm gừng',85000,'room_service','recommended','Ít hành,Thêm gừng,Không tiêu','Less onion,Extra ginger,No pepper',4],
  [34,'Súp Hải Sản Tom Yum','Tom Yum Seafood Soup','Súp Tom Yum kiểu Thái, tôm mực nấm rơm',135000,'restaurant','spicy','Ít cay,Không cay,Thêm hải sản','Less spicy,Not spicy,Extra seafood',5],

  // CƠM & MÓN CHÍNH (35) — both / room
  [35,'Cơm Chiên Dương Châu','Yang Chow Fried Rice','Cơm chiên trứng, tôm, lạp xưởng kiểu Hồng Kông',110000,'both','bestseller','Ít dầu,Không xúc xích,Thêm trứng','Less oil,No sausage,Extra egg',1],
  [35,'Cơm Gà Xối Mỡ','Crispy Chicken Rice','Cơm trắng + 1/4 gà xối mỡ giòn, nước mắm chanh',125000,'both','recommended','Không da,Thêm nước mắm,Ít ớt','No skin,Extra fish sauce,Less chili',2],
  [35,'Bò Lúc Lắc','Shaken Beef','Bò thăn xào lúc lắc tiêu xanh, kèm cơm trắng hoặc bánh mì',185000,'both','bestseller','Cơm trắng,Bánh mì,Tái,Chín hẳn','Steamed rice,Bread,Medium rare,Well-done',3],
  [35,'Cá Hồi Áp Chảo','Pan-Seared Salmon','Cá hồi Na Uy áp chảo bơ chanh, kèm khoai tây nghiền',285000,'both','recommended','Chín vừa,Chín kỹ,Không bơ','Medium,Well-done,No butter',4],
  [35,'Pasta Carbonara','Spaghetti Carbonara','Spaghetti kem trứng pancetta kiểu Ý',165000,'both',null,'Thêm pho mai,Ít kem,Không thịt xông khói','Extra cheese,Less cream,No bacon',5],
  [35,'Cơm Chiên Tôm','Shrimp Fried Rice','Cơm rang với tôm tươi và rau củ',130000,'room_service',null,'Ít dầu,Không hành,Thêm tôm','Less oil,No onion,Extra shrimp',6],
  [35,'Pizza Margherita (nhỏ)','Margherita Pizza (small)','Pizza đế mỏng, sốt cà chua, mozzarella, húng',175000,'room_service','new','Thêm phô mai,Không húng,Thêm ớt khô','Extra cheese,No basil,Extra chili flakes',7],

  // TRÁNG MIỆNG (33) — both
  [33,'Bánh Flan Caramel','Crème Caramel','Bánh flan mềm mịn truyền thống, rưới caramel',55000,'both','recommended',null,null,1],
  [33,'Chè Ba Màu','Three Color Dessert','Đỗ xanh, đỗ đỏ, hạt lựu, nước cốt dừa và đá bào',65000,'both','bestseller','Ít đường,Không dừa,Thêm đá','Less sugar,No coconut,Extra ice',2],
  [33,'Kem Dừa Tươi','Fresh Coconut Ice Cream','Kem vani + thạch dừa trong trái dừa tươi',85000,'both','recommended',null,null,3],
  [33,'Lava Cake Socola','Chocolate Lava Cake','Bánh socola nhân chảy nóng, kèm kem vani',95000,'both','new',null,null,4],
  [33,'Trái Cây Tươi Thái Sẵn','Fresh Cut Fruits','Đĩa trái cây theo mùa: dưa hấu, dứa, thanh long',75000,'both',null,null,null,5],

  // THÊM KHI VỊ (1) — restaurant
  [1,'Súp Vi Cá','Shark Fin Soup','Súp vi cá hầm lâu, nấm đông cô, trứng cút',185000,'restaurant',null,'Ít tiêu,Không giấm,Thêm nấm','Less pepper,No vinegar,Extra mushroom',5],
  [1,'Bạch Tuộc Nướng Muối Ớt','Grilled Octopus','Bạch tuộc nướng than muối ớt, chấm tương hoisin',165000,'restaurant','spicy','Ít cay,Không cay,Thêm sốt','Less spicy,Not spicy,Extra sauce',6],
  [1,'Tôm Khô Cải Chua','Dried Shrimp & Pickle','Tôm khô rim cải chua, ăn kèm cơm',75000,'both',null,'Ít cay,Ít ngọt','Less spicy,Less sweet',7],
];

$stmtItem = $db->prepare(
    "INSERT INTO `menu_items`
     (`category_id`,`name`,`name_en`,`description`,`price`,
      `is_available`,`is_active`,`service_type`,`tags`,
      `note_options`,`note_options_en`,`sort_order`,`stock`)
     VALUES (?,?,?,?,?, 1,1, ?,?, ?,?,?,?)"
);

$inserted = 0;
foreach ($items as $r) {
    try {
        $stmtItem->execute([
            $r[0], $r[1], $r[2], $r[3], $r[4],
            $r[5], $r[6], $r[7], $r[8], $r[9], -1
        ]);
        $inserted++;
    } catch (PDOException $e) {
        $log[] = ['ok' => false, 'label' => "Insert [{$r[1]}]", 'msg' => $e->getMessage()];
    }
}
$log[] = ['ok' => true, 'label' => 'Seed món mô phỏng', 'msg' => "Đã thêm $inserted món thành công"];

// ── 5. Kết quả thống kê ──────────────────────────────────
$stats = $db->query(
    "SELECT service_type, COUNT(*) as cnt FROM menu_items WHERE is_active=1 GROUP BY service_type"
)->fetchAll(PDO::FETCH_KEY_PAIR);

?><!DOCTYPE html>
<html lang="vi">
<head>
<meta charset="UTF-8">
<title>Migration — Aurora Restaurant</title>
<style>
  body { font-family: 'Segoe UI', sans-serif; max-width: 720px; margin: 40px auto; padding: 0 20px; background: #f8fafc; color: #1e293b; }
  h1 { color: #d4af37; border-bottom: 2px solid #d4af37; padding-bottom: 10px; }
  .card { background: #fff; border-radius: 12px; padding: 20px; margin: 16px 0; box-shadow: 0 2px 8px rgba(0,0,0,.08); }
  .ok  { color: #16a34a; } .err { color: #dc2626; }
  .stat { display: flex; gap: 12px; flex-wrap: wrap; margin-top: 16px; }
  .stat-box { flex: 1; min-width: 140px; background: #f1f5f9; border-radius: 10px; padding: 14px 18px; text-align: center; }
  .stat-box .num { font-size: 2rem; font-weight: 800; color: #d4af37; }
  .stat-box .lbl { font-size: .75rem; color: #64748b; margin-top: 4px; }
  table { width: 100%; border-collapse: collapse; font-size: .875rem; }
  th { background: #f1f5f9; text-align: left; padding: 8px 12px; }
  td { padding: 7px 12px; border-bottom: 1px solid #f1f5f9; }
  .warn { background: #fef9c3; border-left: 4px solid #eab308; padding: 12px 16px; border-radius: 8px; margin-top: 20px; }
</style>
</head>
<body>
<h1>🚀 Migration — Room Service Menu</h1>

<div class="card">
  <h3>📊 Thống kê sau migration</h3>
  <div class="stat">
    <div class="stat-box">
      <div class="num"><?= $stats['restaurant'] ?? 0 ?></div>
      <div class="lbl">🍽️ Chỉ Nhà hàng</div>
    </div>
    <div class="stat-box">
      <div class="num"><?= $stats['room_service'] ?? 0 ?></div>
      <div class="lbl">🛏️ Chỉ Room Service</div>
    </div>
    <div class="stat-box">
      <div class="num"><?= $stats['both'] ?? 0 ?></div>
      <div class="lbl">🔀 Cả hai</div>
    </div>
  </div>
</div>

<div class="card">
  <h3>📋 Log thực thi</h3>
  <table>
    <tr><th>Bước</th><th>Kết quả</th></tr>
    <?php foreach ($log as $entry): ?>
    <tr>
      <td><?= htmlspecialchars($entry['label']) ?></td>
      <td class="<?= $entry['ok'] ? 'ok' : 'err' ?>">
        <?= $entry['ok'] ? '✅' : '❌' ?> <?= htmlspecialchars($entry['msg']) ?>
      </td>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

<div class="warn">
  ⚠️ <strong>Bảo mật:</strong> Vui lòng <strong>xóa file <code>migrate_room_service.php</code></strong> ngay sau khi chạy xong!
</div>
</body>
</html>
