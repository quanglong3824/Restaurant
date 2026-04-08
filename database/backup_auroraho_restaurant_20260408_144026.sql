-- Aurora Restaurant Database Backup
-- Generated: 2026-04-08 14:40:26

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `activity_logs`;
CREATE TABLE `activity_logs` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `action` varchar(50) NOT NULL COMMENT 'Hành động thực hiện (login, create, update, delete...)',
  `entity` varchar(50) NOT NULL COMMENT 'Thực thể bị tác động (user, table, order, menu_item...)',
  `entity_id` int(10) unsigned DEFAULT NULL COMMENT 'ID của thực thể',
  `user_id` int(10) unsigned DEFAULT NULL COMMENT 'ID người thực hiện (NULL = system)',
  `ip_address` varchar(45) DEFAULT NULL COMMENT 'IP address',
  `user_agent` text DEFAULT NULL COMMENT 'User agent string',
  `request_uri` varchar(500) DEFAULT NULL COMMENT 'URI yêu cầu',
  `request_method` varchar(10) DEFAULT 'GET' COMMENT 'HTTP method',
  `metadata` text DEFAULT NULL COMMENT 'Dữ liệu metadata (JSON)',
  `level` enum('info','notice','warning','error','critical') NOT NULL DEFAULT 'info' COMMENT 'Mức độ quan trọng',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Thời điểm ghi log',
  PRIMARY KEY (`id`),
  KEY `idx_action` (`action`),
  KEY `idx_entity` (`entity`,`entity_id`),
  KEY `idx_user` (`user_id`),
  KEY `idx_level` (`level`),
  KEY `idx_created_at` (`created_at`),
  KEY `idx_ip` (`ip_address`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Nhật ký hoạt động hệ thống';

INSERT INTO `activity_logs` VALUES ('1', 'logout', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 18:29:25');
INSERT INTO `activity_logs` VALUES ('2', 'error', 'user', '0', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: admin\"}', 'warning', '2026-04-07 18:29:31');
INSERT INTO `activity_logs` VALUES ('3', 'login', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:29:33');
INSERT INTO `activity_logs` VALUES ('4', 'logout', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 18:39:52');
INSERT INTO `activity_logs` VALUES ('5', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:39:56');
INSERT INTO `activity_logs` VALUES ('6', 'logout', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 18:46:27');
INSERT INTO `activity_logs` VALUES ('7', 'error', 'user', '0', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: waiter01\"}', 'warning', '2026-04-07 18:46:31');
INSERT INTO `activity_logs` VALUES ('8', 'login', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:46:34');
INSERT INTO `activity_logs` VALUES ('9', 'login', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 18:47:15');
INSERT INTO `activity_logs` VALUES ('10', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":189,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"Ít  cay / mildly spicy\"}', 'info', '2026-04-07 18:48:18');
INSERT INTO `activity_logs` VALUES ('11', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-07 19:21:27');
INSERT INTO `activity_logs` VALUES ('12', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"Ít  cay / mildly spicy\"}', 'info', '2026-04-07 19:21:42');
INSERT INTO `activity_logs` VALUES ('13', 'create', 'order', '191', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/tables/open', 'POST', '{\"table_id\":36,\"waiter_id\":\"3\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-07 19:25:01');
INSERT INTO `activity_logs` VALUES ('14', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":191,\"menu_item_id\":55,\"item_name\":\"Gỏi cuốn tôm thịt\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-07 19:25:05');
INSERT INTO `activity_logs` VALUES ('15', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":191,\"menu_item_id\":197,\"item_name\":\"Tôm Khô Cải Chua\",\"quantity\":1,\"note\":\"Ít ngọt / Less sweet\"}', 'info', '2026-04-07 19:25:08');
INSERT INTO `activity_logs` VALUES ('16', 'create', 'order', '192', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/tables/open', 'POST', '{\"table_id\":3,\"waiter_id\":\"3\",\"guest_count\":2,\"shift_id\":3}', 'info', '2026-04-07 19:29:17');
INSERT INTO `activity_logs` VALUES ('17', 'error', 'user', '0', NULL, '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: waiter01\"}', 'warning', '2026-04-07 19:31:57');
INSERT INTO `activity_logs` VALUES ('18', 'login', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:32:00');
INSERT INTO `activity_logs` VALUES ('19', 'login', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:33:48');
INSERT INTO `activity_logs` VALUES ('20', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":195,\"item_name\":\"Súp Vi Cá\",\"quantity\":1,\"note\":\"\"}', 'info', '2026-04-07 19:34:06');
INSERT INTO `activity_logs` VALUES ('21', 'create', 'order_item', NULL, '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/orders/add', 'POST', '{\"order_id\":190,\"menu_item_id\":196,\"item_name\":\"Bạch Tuộc Nướng Muối Ớt\",\"quantity\":1,\"note\":\"Không cay / Not spicy\"}', 'info', '2026-04-07 19:34:09');
INSERT INTO `activity_logs` VALUES ('22', 'logout', 'user', '3', '3', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 19:35:51');
INSERT INTO `activity_logs` VALUES ('23', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/16.6.1 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:35:54');
INSERT INTO `activity_logs` VALUES ('24', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (iPad; CPU OS 16_7_11 like Mac OS X) AppleWebKit/605.1.15 (KHTML, like Gecko) CriOS/137.0.7151.107 Mobile/15E148 Safari/604.1', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:49:39');
INSERT INTO `activity_logs` VALUES ('25', 'logout', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-07 19:50:56');
INSERT INTO `activity_logs` VALUES ('26', 'login', 'user', '1', '1', '118.69.64.122', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-07 19:50:58');
INSERT INTO `activity_logs` VALUES ('27', 'login', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 13:22:34');
INSERT INTO `activity_logs` VALUES ('28', 'logout', 'user', '1', '1', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/logout', 'GET', '[]', 'info', '2026-04-08 13:23:06');
INSERT INTO `activity_logs` VALUES ('29', 'error', 'user', '0', NULL, '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":false,\"reason\":\"Invalid PIN for user: it\"}', 'warning', '2026-04-08 13:23:09');
INSERT INTO `activity_logs` VALUES ('30', 'login', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 13:23:11');
INSERT INTO `activity_logs` VALUES ('31', 'login', 'user', '2', '2', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/auth/login', 'POST', '{\"success\":true,\"reason\":\"\"}', 'info', '2026-04-08 13:47:08');
INSERT INTO `activity_logs` VALUES ('32', 'delete', 'menu_clear', '0', '2', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/menu/clear', 'POST', '{\"type\":\"all\",\"deleted\":{\"items\":50,\"categories\":11,\"sets\":2,\"setItems\":0},\"user_id\":\"2\"}', 'warning', '2026-04-08 13:58:58');
INSERT INTO `activity_logs` VALUES ('33', 'create', 'menu_category', '36', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Khai Vị\",\"name_en\":\"Appertizer\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":1}', 'info', '2026-04-08 14:03:44');
INSERT INTO `activity_logs` VALUES ('34', 'create', 'menu_category', '37', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Món Chính\",\"name_en\":\"Main Course\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":2}', 'info', '2026-04-08 14:05:46');
INSERT INTO `activity_logs` VALUES ('35', 'create', 'menu_category', '38', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Xà Lách\",\"name_en\":\"Salad\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":3}', 'info', '2026-04-08 14:08:11');
INSERT INTO `activity_logs` VALUES ('36', 'create', 'menu_category', '39', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Súp\",\"name_en\":\"Soup\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":0}', 'info', '2026-04-08 14:08:49');
INSERT INTO `activity_logs` VALUES ('37', 'create', 'menu_category', '40', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Mì Ý\",\"name_en\":\"Spaghetti\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":4}', 'info', '2026-04-08 14:09:31');
INSERT INTO `activity_logs` VALUES ('38', 'create', 'menu_category', '41', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Bánh Mì\",\"name_en\":\"Sandwich\",\"menu_type\":\"other\",\"icon\":\"fa-utensils\",\"sort_order\":0}', 'info', '2026-04-08 14:09:48');
INSERT INTO `activity_logs` VALUES ('39', 'delete', 'menu_clear', '0', '2', '115.74.225.100', 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/17.6 Safari/605.1.15', '/restaurant/admin/menu/clear', 'POST', '{\"type\":\"all\",\"deleted\":{\"items\":0,\"categories\":6,\"sets\":0,\"setItems\":0},\"user_id\":\"2\"}', 'warning', '2026-04-08 14:11:21');
INSERT INTO `activity_logs` VALUES ('40', 'update', 'menu_type', '1', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/menu-types/update', 'POST', '{\"name\":\"Món Á\",\"name_en\":\"Asian Cuisine\",\"type_key\":\"asia\",\"description\":\"Các món ăn truyền thống châu Á\",\"color\":\"#0ea5e9\",\"icon\":\"fa-bowl-rice\",\"sort_order\":1,\"is_active\":1}', 'info', '2026-04-08 14:19:00');
INSERT INTO `activity_logs` VALUES ('41', 'create', 'menu_category', '42', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Cơm\",\"name_en\":\"Rice\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":0}', 'info', '2026-04-08 14:26:57');
INSERT INTO `activity_logs` VALUES ('42', 'create', 'menu_category', '43', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Mì & Bún\",\"name_en\":\"Noodle\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":1}', 'info', '2026-04-08 14:27:14');
INSERT INTO `activity_logs` VALUES ('43', 'create', 'menu_category', '44', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Cháo\",\"name_en\":\"Porridge\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":2}', 'info', '2026-04-08 14:27:42');
INSERT INTO `activity_logs` VALUES ('44', 'create', 'menu_category', '45', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Heo\",\"name_en\":\"Pork\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":3}', 'info', '2026-04-08 14:28:37');
INSERT INTO `activity_logs` VALUES ('45', 'create', 'menu_category', '46', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Bò\",\"name_en\":\"BEEF\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":4}', 'info', '2026-04-08 14:28:48');
INSERT INTO `activity_logs` VALUES ('46', 'create', 'menu_category', '47', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Tôm\",\"name_en\":\"Shrimp\",\"menu_type\":\"asia\",\"icon\":\"fa-utensils\",\"sort_order\":5}', 'info', '2026-04-08 14:29:09');
INSERT INTO `activity_logs` VALUES ('47', 'create', 'menu_category', '48', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/store', 'POST', '{\"name\":\"Súp\",\"name_en\":\"Soup\",\"menu_type\":\"europe\",\"icon\":\"fa-utensils\",\"sort_order\":6}', 'info', '2026-04-08 14:29:44');
INSERT INTO `activity_logs` VALUES ('48', 'delete', 'menu_category', '48', '2', '115.74.225.100', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/146.0.0.0 Safari/537.36', '/restaurant/admin/categories/delete', 'POST', '{\"name\":\"Súp\"}', 'info', '2026-04-08 14:30:10');

DROP TABLE IF EXISTS `customer_sessions`;
CREATE TABLE `customer_sessions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `session_id` varchar(255) NOT NULL,
  `table_id` int(10) unsigned NOT NULL,
  `order_id` int(10) unsigned DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `last_activity` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `idx_session_id` (`session_id`),
  KEY `idx_table_active` (`table_id`,`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `location_limits`;
CREATE TABLE `location_limits` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL DEFAULT 'Giới hạn QR Restaurant',
  `center_lat` decimal(10,8) NOT NULL COMMENT 'Vĩ độ trung tâm (Aurora Hotel)',
  `center_lng` decimal(11,8) NOT NULL COMMENT 'Kinh độ trung tâm',
  `radius_meters` int(10) unsigned NOT NULL DEFAULT 500 COMMENT 'Bán kính giới hạn (m)',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Bật/tắt giới hạn vị trí',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `location_limits` VALUES ('1', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:36:35', '2026-03-08 16:36:35');
INSERT INTO `location_limits` VALUES ('2', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:46:06', '2026-03-08 16:46:06');

DROP TABLE IF EXISTS `menu_categories`;
CREATE TABLE `menu_categories` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên danh mục: Khai vị, Chính, Tráng miệng...',
  `name_en` varchar(100) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `menu_type` varchar(50) DEFAULT 'asia' COMMENT 'Tham chiếu đến type_key trong menu_types',
  `icon` varchar(50) DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` smallint(5) unsigned DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=49 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `menu_categories` VALUES ('42', 'Cơm', 'Rice', 'asia', 'fa-utensils', '0', '1', '2026-04-08 14:26:57', '2026-04-08 14:26:57');
INSERT INTO `menu_categories` VALUES ('43', 'Mì & Bún', 'Noodle', 'asia', 'fa-utensils', '1', '1', '2026-04-08 14:27:14', '2026-04-08 14:27:14');
INSERT INTO `menu_categories` VALUES ('44', 'Cháo', 'Porridge', 'asia', 'fa-utensils', '2', '1', '2026-04-08 14:27:42', '2026-04-08 14:27:42');
INSERT INTO `menu_categories` VALUES ('45', 'Heo', 'Pork', 'asia', 'fa-utensils', '3', '1', '2026-04-08 14:28:37', '2026-04-08 14:28:37');
INSERT INTO `menu_categories` VALUES ('46', 'Bò', 'BEEF', 'asia', 'fa-utensils', '4', '1', '2026-04-08 14:28:48', '2026-04-08 14:28:48');
INSERT INTO `menu_categories` VALUES ('47', 'Tôm', 'Shrimp', 'asia', 'fa-utensils', '5', '1', '2026-04-08 14:29:09', '2026-04-08 14:29:09');

DROP TABLE IF EXISTS `menu_categories_backup`;
CREATE TABLE `menu_categories_backup` (
  `id` int(10) unsigned NOT NULL DEFAULT 0,
  `name` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL COMMENT 'Tên danh mục: Khai vị, Chính, Tráng miệng...',
  `name_en` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `menu_type` enum('asia','europe','alacarte','set','other') CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'asia',
  `icon` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` smallint(5) unsigned DEFAULT 0 COMMENT 'Thứ tự hiển thị',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;


DROP TABLE IF EXISTS `menu_items`;
CREATE TABLE `menu_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `category_id` int(10) unsigned NOT NULL,
  `menu_type` varchar(50) DEFAULT 'asia' COMMENT 'Tham chiếu đến type_key trong menu_types',
  `name` varchar(150) NOT NULL COMMENT 'Tên món',
  `name_en` varchar(150) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `description` text DEFAULT NULL COMMENT 'Mô tả món',
  `price` decimal(10,0) NOT NULL DEFAULT 0 COMMENT 'Giá (VND)',
  `image` varchar(255) DEFAULT NULL COMMENT 'Đường dẫn ảnh món',
  `is_available` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=còn hàng, 0=hết hàng',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hiển thị, 0=ẩn',
  `service_type` enum('restaurant','room_service','both') NOT NULL DEFAULT 'both',
  `stock` int(11) NOT NULL DEFAULT -1,
  `tags` set('bestseller','new','spicy','vegetarian','recommended') DEFAULT NULL,
  `note_options` text DEFAULT NULL,
  `note_options_en` text DEFAULT NULL,
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_items_category` (`category_id`),
  KEY `idx_items_available` (`is_available`,`is_active`),
  CONSTRAINT `fk_items_category` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=198 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `menu_set_items`;
CREATE TABLE `menu_set_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `set_id` int(10) unsigned NOT NULL,
  `menu_item_id` int(10) unsigned NOT NULL,
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `is_required` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=bắt buộc, 0=tuỳ chọn',
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_set_items_set` (`set_id`),
  KEY `fk_set_items_menu` (`menu_item_id`),
  CONSTRAINT `fk_set_items_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_set_items_set` FOREIGN KEY (`set_id`) REFERENCES `menu_sets` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `menu_sets`;
CREATE TABLE `menu_sets` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(150) NOT NULL COMMENT 'Tên set',
  `name_en` varchar(150) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `price` decimal(10,0) NOT NULL DEFAULT 0,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `menu_types`;
CREATE TABLE `menu_types` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên tiếng Việt',
  `name_en` varchar(100) DEFAULT NULL COMMENT 'Tên tiếng Anh (tuỳ chọn)',
  `type_key` varchar(50) NOT NULL COMMENT 'Mã định danh (vd: asia, europe, alacarte)',
  `description` text DEFAULT NULL COMMENT 'Mô tả ngắn',
  `color` varchar(20) DEFAULT '#0ea5e9' COMMENT 'Màu sắc đại diện (hex)',
  `icon` varchar(50) DEFAULT 'fa-utensils' COMMENT 'Font Awesome icon class',
  `sort_order` int(11) DEFAULT 0 COMMENT 'Thứ tự sắp xếp',
  `is_active` tinyint(1) DEFAULT 1 COMMENT '1: Hiện, 0: Ẩn',
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uk_type_key` (`type_key`),
  KEY `idx_types_active` (`is_active`),
  KEY `idx_types_sort` (`sort_order`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Phân loại menu (Á, Âu, Alacarte, Khác)';

INSERT INTO `menu_types` VALUES ('1', 'Món Á', 'Asian Cuisine', 'asia', 'Các món ăn truyền thống châu Á', '#0ea5e9', 'fa-bowl-rice', '1', '1', '2026-04-08 14:13:10', '2026-04-08 14:19:00');
INSERT INTO `menu_types` VALUES ('2', 'Món Âu', 'European Cuisine', 'europe', 'Các món ăn châu Âu', '#8b5cf6', 'fa-utensils', '2', '1', '2026-04-08 14:13:10', '2026-04-08 14:13:10');
INSERT INTO `menu_types` VALUES ('3', 'Ala Carte', 'Ala Carte', 'alacarte', 'Các món gọi lẻ theo thực đơn', '#f59e0b', 'fa-clipboard-list', '3', '1', '2026-04-08 14:13:10', '2026-04-08 14:13:10');
INSERT INTO `menu_types` VALUES ('4', 'Khác', 'Other', 'other', 'Các loại món khác (đồ uống, tráng miệng...)', '#16a34a', 'fa-utensils', '4', '1', '2026-04-08 14:13:10', '2026-04-08 14:13:10');

DROP TABLE IF EXISTS `order_items`;
CREATE TABLE `order_items` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned NOT NULL,
  `table_id` int(11) unsigned DEFAULT NULL COMMENT 'Bàn vật lý mà món này thuộc về (cho merged tables)',
  `menu_item_id` int(10) unsigned NOT NULL,
  `item_name` varchar(150) NOT NULL COMMENT 'Snapshot tên món tại thời điểm ghi',
  `item_price` decimal(10,0) NOT NULL COMMENT 'Snapshot giá tại thời điểm ghi',
  `quantity` tinyint(3) unsigned NOT NULL DEFAULT 1,
  `note` varchar(255) DEFAULT NULL COMMENT 'Ghi chú: không hành, ít cay...',
  `split_from_item_id` int(11) unsigned DEFAULT NULL COMMENT 'ID của món gốc mà món này được tách từ đó',
  `is_split_item` tinyint(1) unsigned DEFAULT 0 COMMENT '1 = món này đã được tách từ bàn khác',
  `status` enum('draft','confirmed','cancelled') DEFAULT 'draft',
  `customer_id` varchar(64) DEFAULT NULL COMMENT 'Session ID của khách hàng (cho customer ordering)',
  `submitted_at` timestamp NULL DEFAULT NULL COMMENT 'Thời gian khách gửi món (chuyển từ draft sang pending)',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_order_items_order` (`order_id`),
  KEY `fk_order_items_menu` (`menu_item_id`),
  KEY `idx_order_items_table` (`table_id`),
  KEY `idx_split_tracking` (`is_split_item`,`split_from_item_id`),
  KEY `idx_table_status` (`table_id`,`status`),
  KEY `idx_customer_id` (`customer_id`),
  KEY `idx_submitted_at` (`submitted_at`),
  CONSTRAINT `fk_order_items_menu` FOREIGN KEY (`menu_item_id`) REFERENCES `menu_items` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `fk_order_items_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `order_notifications`;
CREATE TABLE `order_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(10) unsigned DEFAULT NULL,
  `table_id` int(10) unsigned NOT NULL,
  `notification_type` enum('new_order','order_item','support_request','payment_request','scan_qr') NOT NULL DEFAULT 'new_order',
  `title` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT 0,
  `read_at` timestamp NULL DEFAULT NULL,
  `read_by` int(10) unsigned DEFAULT NULL COMMENT 'Nhân viên đã đọc',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_notification_order` (`order_id`),
  KEY `idx_notification_table` (`table_id`),
  KEY `idx_notification_unread` (`is_read`),
  KEY `idx_notification_type` (`notification_type`),
  KEY `idx_notification_created` (`created_at`),
  CONSTRAINT `fk_notification_order` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_notification_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lưu trữ thông báo order cho waiter';


DROP TABLE IF EXISTS `orders`;
CREATE TABLE `orders` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `waiter_id` int(10) unsigned DEFAULT NULL,
  `shift_id` int(10) unsigned DEFAULT NULL,
  `guest_count` tinyint(3) unsigned DEFAULT 1 COMMENT 'Số khách',
  `note` text DEFAULT NULL COMMENT 'Ghi chú cho cả order',
  `customer_notes` text DEFAULT NULL COMMENT 'Ghi chú từ khách hàng (lý do hủy, đặc biệt...)',
  `requires_confirmation` tinyint(1) NOT NULL DEFAULT 1 COMMENT 'Cần xác nhận từ nhân viên: 1=Có, 0=Không',
  `status` enum('open','closed') NOT NULL DEFAULT 'open' COMMENT 'open=đang phục vụ, closed=khách ra',
  `order_source` enum('waiter','customer_qr') NOT NULL DEFAULT 'waiter' COMMENT 'Nguồn tạo order: waiter (phục vụ) hoặc customer_qr (khách quét QR)',
  `is_realtime_hidden` tinyint(1) DEFAULT 0,
  `payment_method` varchar(50) DEFAULT 'cash',
  `payment_status` enum('pending','paid','canceled') DEFAULT 'pending',
  `opened_at` timestamp NOT NULL DEFAULT current_timestamp() COMMENT 'Giờ mở bàn',
  `closed_at` timestamp NULL DEFAULT NULL COMMENT 'Giờ đóng bàn',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `session_id` varchar(64) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_orders_table` (`table_id`),
  KEY `idx_orders_waiter` (`waiter_id`),
  KEY `idx_orders_status` (`status`),
  KEY `idx_orders_opened` (`opened_at`),
  KEY `idx_order_source` (`order_source`),
  KEY `idx_orders_session` (`session_id`),
  CONSTRAINT `fk_orders_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON UPDATE CASCADE,
  CONSTRAINT `fk_orders_waiter` FOREIGN KEY (`waiter_id`) REFERENCES `users` (`id`) ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `qr_tables`;
CREATE TABLE `qr_tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL COMMENT 'Mã bàn (foreign key)',
  `qr_code` varchar(255) DEFAULT NULL COMMENT 'URL hoặc nội dung QR code',
  `qr_hash` varchar(64) NOT NULL COMMENT 'Mã hash duy nhất cho QR (dùng cho URL)',
  `generated_at` timestamp NULL DEFAULT current_timestamp() COMMENT 'Thời gian tạo QR',
  `updated_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=đ aktiv, 0=ẩn',
  `scan_count` int(10) unsigned NOT NULL DEFAULT 0 COMMENT 'Số lần quét QR code',
  `last_scanned_at` timestamp NULL DEFAULT NULL COMMENT 'Lần quét cuối cùng',
  `is_printed` tinyint(1) DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `table_id` (`table_id`),
  UNIQUE KEY `qr_hash` (`qr_hash`),
  KEY `idx_qr_active` (`is_active`),
  CONSTRAINT `fk_qr_tables_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=222 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `qr_tables` VALUES ('2', '2', '/qr/menu?table_id=2&token=5aea0ec36591ecddd837fa739b2a5786', '5aea0ec36591ecddd837fa739b2a5786', '2026-03-08 16:50:22', '2026-03-23 09:01:50', '1', '5', '2026-03-23 09:01:50', '0');
INSERT INTO `qr_tables` VALUES ('3', '3', '/qr/menu?table_id=3&token=42a2c52875c7bfc390916ca1c33a7157', '42a2c52875c7bfc390916ca1c33a7157', '2026-03-08 16:50:22', '2026-03-23 09:15:33', '1', '25', '2026-03-23 09:15:33', '0');
INSERT INTO `qr_tables` VALUES ('4', '4', '/qr/menu?table_id=4&token=59151733a4403e2ba90a3668b91ef209', '59151733a4403e2ba90a3668b91ef209', '2026-03-08 16:50:22', '2026-03-23 09:16:48', '1', '4', '2026-03-23 09:16:48', '0');
INSERT INTO `qr_tables` VALUES ('5', '5', '/qr/menu?table_id=5&token=618e597619b7339cb04747a43747b086', '618e597619b7339cb04747a43747b086', '2026-03-08 16:50:22', '2026-03-17 18:30:03', '1', '2', '2026-03-17 18:30:03', '0');
INSERT INTO `qr_tables` VALUES ('6', '6', '/qr/menu?table_id=6&token=4d594e96b4578dd6eb6a2772eeb342d4', '4d594e96b4578dd6eb6a2772eeb342d4', '2026-03-08 16:50:22', '2026-03-23 09:21:29', '1', '10', '2026-03-23 09:21:29', '0');
INSERT INTO `qr_tables` VALUES ('7', '7', '/qr/menu?table_id=7&token=9debf619d8155b5e4218cdd77c9caa19', '9debf619d8155b5e4218cdd77c9caa19', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('8', '8', '/qr/menu?table_id=8&token=1a19d6276459abb3b0c92c9d7dd7dc0f', '1a19d6276459abb3b0c92c9d7dd7dc0f', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('9', '9', '/qr/menu?table_id=9&token=399e1ce0c5dd5fcaeb4cc583e17b45c5', '399e1ce0c5dd5fcaeb4cc583e17b45c5', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('10', '10', '/qr/menu?table_id=10&token=fb7e119368932a90d53465b48456597e', 'fb7e119368932a90d53465b48456597e', '2026-03-08 16:50:22', '2026-03-18 11:00:45', '1', '1', '2026-03-18 11:00:45', '0');
INSERT INTO `qr_tables` VALUES ('11', '11', '/qr/menu?table_id=11&token=d6590664eb462875de436efba585885b', 'd6590664eb462875de436efba585885b', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('12', '12', '/qr/menu?table_id=12&token=7714f8eb8cd1d9f1f4119665b538b9ec', '7714f8eb8cd1d9f1f4119665b538b9ec', '2026-03-08 16:50:22', '2026-03-18 21:00:18', '1', '8', '2026-03-18 21:00:18', '0');
INSERT INTO `qr_tables` VALUES ('13', '13', '/qr/menu?table_id=13&token=77d2001b22efcd63714ee5ec39cd624f', '77d2001b22efcd63714ee5ec39cd624f', '2026-03-08 16:50:22', '2026-03-19 08:59:30', '1', '3', '2026-03-19 08:59:30', '0');
INSERT INTO `qr_tables` VALUES ('14', '14', '/qr/menu?table_id=14&token=4f49388e51b134fabd30a789026ef9d0', '4f49388e51b134fabd30a789026ef9d0', '2026-03-08 16:50:22', '2026-03-18 15:18:58', '1', '4', '2026-03-18 15:18:58', '0');
INSERT INTO `qr_tables` VALUES ('15', '15', '/qr/menu?table_id=15&token=e939bdc269e7ea98da7f779691f9c63f', 'e939bdc269e7ea98da7f779691f9c63f', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('16', '16', '/qr/menu?table_id=16&token=5271c6745193cbd78b6d60fff9fb2863', '5271c6745193cbd78b6d60fff9fb2863', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('17', '17', '/qr/menu?table_id=17&token=ced798a790cc9fc268d03bcb7d5ccb93', 'ced798a790cc9fc268d03bcb7d5ccb93', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('18', '18', '/qr/menu?table_id=18&token=fa8932bc456533b1d680f807d16f35e9', 'fa8932bc456533b1d680f807d16f35e9', '2026-03-08 16:50:22', '2026-03-23 08:11:18', '1', '2', '2026-03-23 08:11:18', '1');
INSERT INTO `qr_tables` VALUES ('19', '19', '/qr/menu?table_id=19&token=b756d7566f9984bbc5190a823b00497a', 'b756d7566f9984bbc5190a823b00497a', '2026-03-08 16:50:22', '2026-03-25 10:52:32', '1', '0', NULL, '1');
INSERT INTO `qr_tables` VALUES ('20', '20', '/qr/menu?table_id=20&token=21b46e1f5b1d62e2f92ff2f02b28cf20', '21b46e1f5b1d62e2f92ff2f02b28cf20', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('21', '21', '/qr/menu?table_id=21&token=8a76b7a961c5343e09d947e7d762e032', '8a76b7a961c5343e09d947e7d762e032', '2026-03-08 16:50:22', '2026-03-17 21:46:45', '1', '3', '2026-03-17 21:46:45', '0');
INSERT INTO `qr_tables` VALUES ('22', '22', '/qr/menu?table_id=22&token=bd35d8e8560defeda360b4d99677350e', 'bd35d8e8560defeda360b4d99677350e', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('23', '23', '/qr/menu?table_id=23&token=36fcd88597412647c56d588a4f136e49', '36fcd88597412647c56d588a4f136e49', '2026-03-08 16:50:22', '2026-03-18 21:31:34', '1', '5', '2026-03-18 21:31:34', '0');
INSERT INTO `qr_tables` VALUES ('25', '25', '/qr/menu?table_id=25&token=4feee1c893e3c3ae36a029a11fdcd143', '4feee1c893e3c3ae36a029a11fdcd143', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('26', '26', '/qr/menu?table_id=26&token=a13c5111c2fea2dbf170ad159d88b3d5', 'a13c5111c2fea2dbf170ad159d88b3d5', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('27', '27', '/qr/menu?table_id=27&token=ab4722e5df763a17d54a46afa3506d39', 'ab4722e5df763a17d54a46afa3506d39', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('28', '28', '/qr/menu?table_id=28&token=197c68ac3e576c4ca2ecc90ba0035749', '197c68ac3e576c4ca2ecc90ba0035749', '2026-03-08 16:50:22', '2026-03-18 21:29:40', '1', '10', '2026-03-18 21:29:40', '0');
INSERT INTO `qr_tables` VALUES ('29', '29', '/qr/menu?table_id=29&token=540c56c80e81ad174c96292e2a32e55b', '540c56c80e81ad174c96292e2a32e55b', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('30', '30', '/qr/menu?table_id=30&token=892c41525bae1635bc4dd6e00049db09', '892c41525bae1635bc4dd6e00049db09', '2026-03-08 16:50:22', '2026-03-17 18:29:16', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('31', '31', '/qr/menu?table_id=31&token=c4d60af58f19e189cfbae688a169a499', 'c4d60af58f19e189cfbae688a169a499', '2026-03-08 16:50:22', '2026-03-18 21:41:17', '1', '7', '2026-03-18 21:41:17', '0');
INSERT INTO `qr_tables` VALUES ('32', '32', '/qr/menu?table_id=32&token=3132152b936f32b9bd4833020db70d8e', '3132152b936f32b9bd4833020db70d8e', '2026-03-08 16:50:22', '2026-03-18 21:36:11', '1', '12', '2026-03-18 21:36:11', '0');
INSERT INTO `qr_tables` VALUES ('64', '1', '/qr/menu?table_id=1&token=c1674174442ac69294484eb54ffe1e2b', 'c1674174442ac69294484eb54ffe1e2b', '2026-03-17 17:59:01', '2026-03-23 09:02:04', '1', '74', '2026-03-23 09:02:04', '1');
INSERT INTO `qr_tables` VALUES ('99', '34', '/qr/menu?table_id=34&token=9853c359c274bc07', '9853c359c274bc07', '2026-03-21 18:15:32', '2026-03-22 14:23:53', '1', '5', '2026-03-22 14:23:53', '0');
INSERT INTO `qr_tables` VALUES ('100', '35', '/qr/menu?table_id=35&token=657bb64a5236cd07', '657bb64a5236cd07', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('101', '36', '/qr/menu?table_id=36&token=9e6e864e8dcd4d16', '9e6e864e8dcd4d16', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('102', '37', '/qr/menu?table_id=37&token=542e9fb5755eb267', '542e9fb5755eb267', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('103', '38', '/qr/menu?table_id=38&token=e1deba631cedf38e', 'e1deba631cedf38e', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('104', '39', '/qr/menu?table_id=39&token=d14c1f3cc89aac11', 'd14c1f3cc89aac11', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('105', '40', '/qr/menu?table_id=40&token=3bf496f44e08b889', '3bf496f44e08b889', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('106', '41', '/qr/menu?table_id=41&token=0d260ec51b770ce8', '0d260ec51b770ce8', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('107', '42', '/qr/menu?table_id=42&token=9001444b50b373db', '9001444b50b373db', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('108', '43', '/qr/menu?table_id=43&token=dbf00f10b62db39a', 'dbf00f10b62db39a', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('109', '44', '/qr/menu?table_id=44&token=cb05824f42f77532', 'cb05824f42f77532', '2026-03-21 18:15:32', '2026-03-21 18:15:32', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('110', '45', '/qr/menu?table_id=45&token=fdc33c6963b61a5f', 'fdc33c6963b61a5f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('111', '46', '/qr/menu?table_id=46&token=ceddfbf3399ab9cb', 'ceddfbf3399ab9cb', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('112', '47', '/qr/menu?table_id=47&token=b9dc9f230ab371ca', 'b9dc9f230ab371ca', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('113', '48', '/qr/menu?table_id=48&token=92778081df46e36a', '92778081df46e36a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('114', '49', '/qr/menu?table_id=49&token=4ed6c469b2367b6b', '4ed6c469b2367b6b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('115', '50', '/qr/menu?table_id=50&token=5caa7d2b02b578a3', '5caa7d2b02b578a3', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('116', '51', '/qr/menu?table_id=51&token=e92de0aa41aebbd1', 'e92de0aa41aebbd1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('117', '52', '/qr/menu?table_id=52&token=1f3bd48265060d7f', '1f3bd48265060d7f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('118', '53', '/qr/menu?table_id=53&token=95e7f49e988c9349', '95e7f49e988c9349', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('119', '54', '/qr/menu?table_id=54&token=074d0a40b61ad55f', '074d0a40b61ad55f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('120', '55', '/qr/menu?table_id=55&token=4a40e346b77da1a1', '4a40e346b77da1a1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('121', '56', '/qr/menu?table_id=56&token=c426d510ddc9d02e', 'c426d510ddc9d02e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('122', '57', '/qr/menu?table_id=57&token=0e0fce6b5913b27d', '0e0fce6b5913b27d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('123', '58', '/qr/menu?table_id=58&token=ca71a8d84a9f53a1', 'ca71a8d84a9f53a1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('124', '59', '/qr/menu?table_id=59&token=2f722fa35a1b19a0', '2f722fa35a1b19a0', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('125', '60', '/qr/menu?table_id=60&token=8f4cdfd92d0da925', '8f4cdfd92d0da925', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('126', '61', '/qr/menu?table_id=61&token=c26b7a5d6e373c8d', 'c26b7a5d6e373c8d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('127', '62', '/qr/menu?table_id=62&token=75f24d352b7930e0', '75f24d352b7930e0', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('128', '63', '/qr/menu?table_id=63&token=b233ed219adb5ff6', 'b233ed219adb5ff6', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('129', '64', '/qr/menu?table_id=64&token=faf4cc3087786929', 'faf4cc3087786929', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('130', '65', '/qr/menu?table_id=65&token=55ef6da9717fe36c', '55ef6da9717fe36c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('131', '66', '/qr/menu?table_id=66&token=b5cbe2119ca3584b', 'b5cbe2119ca3584b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('132', '67', '/qr/menu?table_id=67&token=34864e0af2f15b4e', '34864e0af2f15b4e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('133', '68', '/qr/menu?table_id=68&token=13b52b9cdc71396b', '13b52b9cdc71396b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('134', '69', '/qr/menu?table_id=69&token=3e9b4e4c66093170', '3e9b4e4c66093170', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('135', '70', '/qr/menu?table_id=70&token=c8fc938812da7595', 'c8fc938812da7595', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('136', '71', '/qr/menu?table_id=71&token=72ed0a67f3e418bc', '72ed0a67f3e418bc', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('137', '72', '/qr/menu?table_id=72&token=5f63dba64276ec9b', '5f63dba64276ec9b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('138', '73', '/qr/menu?table_id=73&token=18d2b01d34b02538', '18d2b01d34b02538', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('139', '74', '/qr/menu?table_id=74&token=c78d0727cc42e972', 'c78d0727cc42e972', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('140', '75', '/qr/menu?table_id=75&token=6c564c3fe3cb9817', '6c564c3fe3cb9817', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('141', '76', '/qr/menu?table_id=76&token=c33726dbdb0358a2', 'c33726dbdb0358a2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('142', '77', '/qr/menu?table_id=77&token=96f602f4e71c331a', '96f602f4e71c331a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('143', '78', '/qr/menu?table_id=78&token=572dab2f380d1ca7', '572dab2f380d1ca7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('144', '79', '/qr/menu?table_id=79&token=c522360dfc9445d2', 'c522360dfc9445d2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('145', '80', '/qr/menu?table_id=80&token=a88b48db14c892da', 'a88b48db14c892da', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('146', '81', '/qr/menu?table_id=81&token=4df8d4ba2e32312d', '4df8d4ba2e32312d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('147', '82', '/qr/menu?table_id=82&token=cb3f6608ec4d5a72', 'cb3f6608ec4d5a72', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('148', '83', '/qr/menu?table_id=83&token=5aaedb0e390a7313', '5aaedb0e390a7313', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('149', '84', '/qr/menu?table_id=84&token=0ad57b083cac6260', '0ad57b083cac6260', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('150', '85', '/qr/menu?table_id=85&token=af6af4b4511a99e4', 'af6af4b4511a99e4', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('151', '86', '/qr/menu?table_id=86&token=b4420cde02c1b182', 'b4420cde02c1b182', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('152', '87', '/qr/menu?table_id=87&token=4ea6748a036a17a9', '4ea6748a036a17a9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('153', '88', '/qr/menu?table_id=88&token=7eb45a95a506e6e2', '7eb45a95a506e6e2', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('154', '89', '/qr/menu?table_id=89&token=d63333a8c7b4ca3f', 'd63333a8c7b4ca3f', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('155', '90', '/qr/menu?table_id=90&token=4e2eb855787bcaf3', '4e2eb855787bcaf3', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('156', '91', '/qr/menu?table_id=91&token=2c90fda9e1180a1c', '2c90fda9e1180a1c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('157', '92', '/qr/menu?table_id=92&token=6167dc28edb38a55', '6167dc28edb38a55', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('158', '93', '/qr/menu?table_id=93&token=1e93ecca46389d15', '1e93ecca46389d15', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('159', '94', '/qr/menu?table_id=94&token=2805dc1870802cfe', '2805dc1870802cfe', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('160', '95', '/qr/menu?table_id=95&token=97d9f38183834cd4', '97d9f38183834cd4', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('161', '96', '/qr/menu?table_id=96&token=14aacaae5e8a2d9a', '14aacaae5e8a2d9a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('162', '97', '/qr/menu?table_id=97&token=4de1fb2e24430677', '4de1fb2e24430677', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('163', '98', '/qr/menu?table_id=98&token=b938fa1231a36d2d', 'b938fa1231a36d2d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('164', '99', '/qr/menu?table_id=99&token=2b3b89ba01f3f085', '2b3b89ba01f3f085', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('165', '100', '/qr/menu?table_id=100&token=446bfe04c58be616', '446bfe04c58be616', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('166', '101', '/qr/menu?table_id=101&token=82c6002b648817c7', '82c6002b648817c7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('167', '102', '/qr/menu?table_id=102&token=d6edbc3d315a0ef1', 'd6edbc3d315a0ef1', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('168', '103', '/qr/menu?table_id=103&token=74f7a42d4a33b9a7', '74f7a42d4a33b9a7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('169', '104', '/qr/menu?table_id=104&token=e875bbb203cb84d7', 'e875bbb203cb84d7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('170', '105', '/qr/menu?table_id=105&token=1f7c164a788f78dc', '1f7c164a788f78dc', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('171', '106', '/qr/menu?table_id=106&token=37b088c946864e19', '37b088c946864e19', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('172', '107', '/qr/menu?table_id=107&token=7c24fa1b0ceb862b', '7c24fa1b0ceb862b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('173', '108', '/qr/menu?table_id=108&token=2c7ae1c5c5a61adf', '2c7ae1c5c5a61adf', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('174', '109', '/qr/menu?table_id=109&token=bc03b90c607c4bc9', 'bc03b90c607c4bc9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('175', '110', '/qr/menu?table_id=110&token=fc067eba34f4dba9', 'fc067eba34f4dba9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('176', '111', '/qr/menu?table_id=111&token=f5926dec48bfbe9c', 'f5926dec48bfbe9c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('177', '112', '/qr/menu?table_id=112&token=2383544b8b0258e7', '2383544b8b0258e7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('178', '113', '/qr/menu?table_id=113&token=db8c74cc5321febd', 'db8c74cc5321febd', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('179', '114', '/qr/menu?table_id=114&token=03516ce24046399e', '03516ce24046399e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('180', '115', '/qr/menu?table_id=115&token=56c2454f1b84c62c', '56c2454f1b84c62c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('181', '116', '/qr/menu?table_id=116&token=da286833edbd8947', 'da286833edbd8947', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('182', '117', '/qr/menu?table_id=117&token=b99a416c123bbb03', 'b99a416c123bbb03', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('183', '118', '/qr/menu?table_id=118&token=4bc458bc62aa55d7', '4bc458bc62aa55d7', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('184', '119', '/qr/menu?table_id=119&token=ac330ef97f991520', 'ac330ef97f991520', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('185', '120', '/qr/menu?table_id=120&token=6d204a5f0433b0e8', '6d204a5f0433b0e8', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('186', '121', '/qr/menu?table_id=121&token=9e26e6b6c034b261', '9e26e6b6c034b261', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('187', '122', '/qr/menu?table_id=122&token=fcc56cc9ac7f6c8e', 'fcc56cc9ac7f6c8e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('188', '123', '/qr/menu?table_id=123&token=d11f52647ab07b8b', 'd11f52647ab07b8b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('189', '124', '/qr/menu?table_id=124&token=38a0175b8c3cce4b', '38a0175b8c3cce4b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('190', '125', '/qr/menu?table_id=125&token=cad72d36fe9ca269', 'cad72d36fe9ca269', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('191', '126', '/qr/menu?table_id=126&token=83f76774214ee84e', '83f76774214ee84e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('192', '127', '/qr/menu?table_id=127&token=9991fa34d9fb2d59', '9991fa34d9fb2d59', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('193', '128', '/qr/menu?table_id=128&token=86ae7a9242ada453', '86ae7a9242ada453', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('194', '129', '/qr/menu?table_id=129&token=23f166f0940f2ef6', '23f166f0940f2ef6', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('195', '130', '/qr/menu?table_id=130&token=b499b3b3782bd9db', 'b499b3b3782bd9db', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('196', '131', '/qr/menu?table_id=131&token=f85e5f115dd00f1b', 'f85e5f115dd00f1b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('197', '132', '/qr/menu?table_id=132&token=ec420c13716ca4fa', 'ec420c13716ca4fa', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('198', '133', '/qr/menu?table_id=133&token=6df5879b353e73ac', '6df5879b353e73ac', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('199', '134', '/qr/menu?table_id=134&token=cafdfa0c96dc556b', 'cafdfa0c96dc556b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('200', '135', '/qr/menu?table_id=135&token=3cc749ee1b83a365', '3cc749ee1b83a365', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('201', '136', '/qr/menu?table_id=136&token=d253f49e98fa7d34', 'd253f49e98fa7d34', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('202', '137', '/qr/menu?table_id=137&token=0c6cb912e525da5e', '0c6cb912e525da5e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('203', '138', '/qr/menu?table_id=138&token=1968c9b0b8de1f5a', '1968c9b0b8de1f5a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('204', '139', '/qr/menu?table_id=139&token=28952c5a3abedad9', '28952c5a3abedad9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('205', '140', '/qr/menu?table_id=140&token=d2fbb6f6deef5f30', 'd2fbb6f6deef5f30', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('206', '141', '/qr/menu?table_id=141&token=f9f63ab0e0db6707', 'f9f63ab0e0db6707', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('207', '142', '/qr/menu?table_id=142&token=d644c2729c67fa8d', 'd644c2729c67fa8d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('208', '143', '/qr/menu?table_id=143&token=fc38c53b4c412aad', 'fc38c53b4c412aad', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('209', '144', '/qr/menu?table_id=144&token=7a1bcb991914757a', '7a1bcb991914757a', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('210', '145', '/qr/menu?table_id=145&token=4cbb75dbb4fcbf09', '4cbb75dbb4fcbf09', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('211', '146', '/qr/menu?table_id=146&token=c7d6d2391254259c', 'c7d6d2391254259c', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('212', '147', '/qr/menu?table_id=147&token=4a84f03a016fe02d', '4a84f03a016fe02d', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('213', '148', '/qr/menu?table_id=148&token=09d06f72cd8bd631', '09d06f72cd8bd631', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('214', '149', '/qr/menu?table_id=149&token=36d48dbe9b2888a9', '36d48dbe9b2888a9', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('215', '150', '/qr/menu?table_id=150&token=c9c3520512677d78', 'c9c3520512677d78', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('216', '151', '/qr/menu?table_id=151&token=46d4fd06efb382d8', '46d4fd06efb382d8', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('217', '152', '/qr/menu?table_id=152&token=047cf64bdd192fca', '047cf64bdd192fca', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('218', '153', '/qr/menu?table_id=153&token=1ab3d2b7971a925e', '1ab3d2b7971a925e', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('219', '154', '/qr/menu?table_id=154&token=9ba8818156b831f5', '9ba8818156b831f5', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');
INSERT INTO `qr_tables` VALUES ('220', '155', '/qr/menu?table_id=155&token=b0a92130b038239b', 'b0a92130b038239b', '2026-03-21 18:15:33', '2026-03-21 18:15:33', '1', '0', NULL, '0');

DROP TABLE IF EXISTS `realtime_notifications`;
CREATE TABLE `realtime_notifications` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `channel` varchar(50) NOT NULL COMMENT 'Kênh: waiter_1, admin, table_5, all',
  `event_type` varchar(50) NOT NULL COMMENT 'Loại event: new_order, order_confirmed, table_occupied',
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL COMMENT 'Dữ liệu notification dạng JSON' CHECK (json_valid(`payload`)),
  `is_delivered` tinyint(1) NOT NULL DEFAULT 0,
  `delivered_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `expires_at` timestamp NULL DEFAULT NULL COMMENT 'Hết hạn sau 24h',
  PRIMARY KEY (`id`),
  KEY `idx_channel` (`channel`),
  KEY `idx_event_type` (`event_type`),
  KEY `idx_delivered` (`is_delivered`),
  KEY `idx_created` (`created_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Real-time push notifications';


DROP TABLE IF EXISTS `shifts`;
CREATE TABLE `shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL COMMENT 'Tên ca: Sáng, Chiều, Tối...',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `shifts` VALUES ('3', 'Ca Sáng', '06:00:00', '14:00:00', '2026-04-07 09:07:03');
INSERT INTO `shifts` VALUES ('4', 'Ca Chiều', '14:00:00', '22:00:00', '2026-04-07 09:07:03');

DROP TABLE IF EXISTS `support_requests`;
CREATE TABLE `support_requests` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `type` enum('support','payment','scan_qr','new_order') NOT NULL DEFAULT 'support' COMMENT 'Loại yêu cầu: support=hỗ trợ, payment=thanh toán, scan_qr=quét QR, new_order=order mới',
  `status` enum('pending','completed') NOT NULL DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_support_table` (`table_id`),
  CONSTRAINT `fk_support_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `table_status_history`;
CREATE TABLE `table_status_history` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `table_id` int(10) unsigned NOT NULL,
  `previous_status` enum('available','occupied') NOT NULL,
  `current_status` enum('available','occupied') NOT NULL,
  `changed_by` int(10) unsigned DEFAULT NULL COMMENT 'User ID hoặc NULL nếu từ customer',
  `change_reason` varchar(100) DEFAULT NULL COMMENT 'Lý do: scan_qr, waiter_open, manual_close, auto_close',
  `order_id` int(10) unsigned DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `idx_table_history` (`table_id`),
  KEY `idx_table_status_time` (`created_at`),
  KEY `idx_table_change_reason` (`change_reason`),
  CONSTRAINT `fk_history_table` FOREIGN KEY (`table_id`) REFERENCES `tables` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='Lịch sử thay đổi trạng thái bàn';


DROP TABLE IF EXISTS `tables`;
CREATE TABLE `tables` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(10) unsigned DEFAULT NULL,
  `type` enum('table','room') NOT NULL DEFAULT 'table',
  `name` varchar(50) NOT NULL COMMENT 'Tên bàn: Bàn 01, VIP 1...',
  `area` varchar(50) DEFAULT NULL COMMENT 'Khu vực: Trong, Ngoài, VIP...',
  `capacity` tinyint(3) unsigned NOT NULL DEFAULT 4 COMMENT 'Sức chứa (số ghế)',
  `status` enum('available','occupied') NOT NULL DEFAULT 'available',
  `position_x` smallint(5) unsigned DEFAULT 0 COMMENT 'Toạ độ X trên sơ đồ',
  `position_y` smallint(5) unsigned DEFAULT 0 COMMENT 'Toạ độ Y trên sơ đồ',
  `sort_order` smallint(5) unsigned DEFAULT 0,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_tables_parent` (`parent_id`),
  KEY `idx_parent_id` (`parent_id`),
  CONSTRAINT `fk_tables_parent` FOREIGN KEY (`parent_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL,
  CONSTRAINT `fk_tables_parent_new` FOREIGN KEY (`parent_id`) REFERENCES `tables` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=157 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `tables` VALUES ('1', NULL, 'table', 'A.01', 'A1', '4', 'available', '0', '0', '1', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('2', NULL, 'table', 'A.02', 'A1', '4', 'available', '0', '0', '2', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('3', NULL, 'table', 'A.03', 'A1', '4', 'available', '0', '0', '3', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('4', NULL, 'table', 'A.04', 'A1', '4', 'available', '0', '0', '4', '1', '2026-03-07 18:20:45', '2026-03-31 14:50:43');
INSERT INTO `tables` VALUES ('5', NULL, 'table', 'A.05', 'A1', '4', 'available', '0', '0', '5', '1', '2026-03-07 18:20:45', '2026-03-31 14:50:43');
INSERT INTO `tables` VALUES ('6', NULL, 'table', 'A.06', 'A1', '4', 'available', '0', '0', '6', '1', '2026-03-07 18:20:45', '2026-04-01 14:21:57');
INSERT INTO `tables` VALUES ('7', NULL, 'table', 'B.01', 'B1', '4', 'available', '0', '0', '7', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('8', NULL, 'table', 'B.02', 'B1', '4', 'available', '0', '0', '8', '1', '2026-03-07 18:20:45', '2026-04-08 13:23:33');
INSERT INTO `tables` VALUES ('9', NULL, 'table', 'B.03', 'B1', '4', 'available', '0', '0', '9', '1', '2026-03-07 18:20:45', '2026-03-30 21:39:48');
INSERT INTO `tables` VALUES ('10', NULL, 'table', 'B.04', 'B1', '4', 'available', '0', '0', '10', '1', '2026-03-07 18:20:45', '2026-03-30 22:00:27');
INSERT INTO `tables` VALUES ('11', NULL, 'table', 'B.05', 'B1', '4', 'available', '0', '0', '11', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:08');
INSERT INTO `tables` VALUES ('12', NULL, 'table', 'B.06', 'B1', '4', 'available', '0', '0', '12', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:08');
INSERT INTO `tables` VALUES ('13', NULL, 'table', 'C.01', 'C1', '4', 'available', '0', '0', '13', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:18');
INSERT INTO `tables` VALUES ('14', NULL, 'table', 'C.02', 'C1', '4', 'available', '0', '0', '14', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('15', NULL, 'table', 'C.03', 'C1', '4', 'available', '0', '0', '15', '1', '2026-03-07 18:20:45', '2026-03-30 19:46:34');
INSERT INTO `tables` VALUES ('16', NULL, 'table', 'C.04', 'C1', '4', 'available', '0', '0', '16', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('17', NULL, 'table', 'C.05', 'C1', '4', 'available', '0', '0', '17', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('18', NULL, 'table', 'C.06', 'C1', '4', 'available', '0', '0', '18', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('19', NULL, 'table', 'VIP 1.1', 'VIP 1', '8', 'available', '0', '0', '19', '1', '2026-03-07 18:20:45', '2026-04-01 13:59:31');
INSERT INTO `tables` VALUES ('20', NULL, 'table', 'VIP 1.2', 'VIP 1', '8', 'available', '0', '0', '20', '1', '2026-03-07 18:20:45', '2026-03-30 22:01:31');
INSERT INTO `tables` VALUES ('21', NULL, 'table', 'VIP 2.1', 'VIP 2', '8', 'available', '0', '0', '21', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('22', NULL, 'table', 'VIP 2.2', 'VIP 2', '8', 'available', '0', '0', '22', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('23', NULL, 'table', 'VIP 3.1', 'VIP 3', '8', 'available', '0', '0', '23', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('24', NULL, 'table', 'VIP 3.2', 'VIP 3', '8', 'available', '0', '0', '24', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('25', NULL, 'table', 'VIP 4.1', 'VIP 4', '8', 'available', '0', '0', '25', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('26', NULL, 'table', 'VIP 4.2', 'VIP 4', '8', 'available', '0', '0', '26', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('27', NULL, 'table', 'Âu 01', 'Âu', '4', 'available', '0', '0', '27', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('28', NULL, 'table', 'Âu 02', 'Âu', '4', 'available', '0', '0', '28', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('29', NULL, 'table', 'Âu 03', 'Âu', '4', 'available', '0', '0', '29', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('30', NULL, 'table', 'Âu 04', 'Âu', '4', 'available', '0', '0', '30', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('31', NULL, 'table', 'Âu 05', 'Âu', '4', 'available', '0', '0', '31', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('32', NULL, 'table', 'Âu 06', 'Âu', '4', 'available', '0', '0', '32', '1', '2026-03-07 18:20:45', '2026-03-26 09:51:30');
INSERT INTO `tables` VALUES ('34', NULL, 'room', '701', 'Tầng 7', '3', 'available', '0', '0', '701', '1', '2026-03-21 18:15:32', '2026-04-07 16:30:00');
INSERT INTO `tables` VALUES ('35', NULL, 'room', '702', 'Tầng 7', '3', 'available', '0', '0', '702', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('36', NULL, 'room', '703', 'Tầng 7', '3', 'available', '0', '0', '703', '1', '2026-03-21 18:15:32', '2026-04-07 19:25:37');
INSERT INTO `tables` VALUES ('37', NULL, 'room', '704', 'Tầng 7', '3', 'available', '0', '0', '704', '1', '2026-03-21 18:15:32', '2026-04-07 17:00:19');
INSERT INTO `tables` VALUES ('38', NULL, 'room', '705', 'Tầng 7', '3', 'available', '0', '0', '705', '1', '2026-03-21 18:15:32', '2026-03-21 19:00:33');
INSERT INTO `tables` VALUES ('39', NULL, 'room', '706', 'Tầng 7', '3', 'available', '0', '0', '706', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('40', NULL, 'room', '707', 'Tầng 7', '3', 'available', '0', '0', '707', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('41', NULL, 'room', '708', 'Tầng 7', '3', 'available', '0', '0', '708', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('42', NULL, 'room', '709', 'Tầng 7', '3', 'available', '0', '0', '709', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('43', NULL, 'room', '710', 'Tầng 7', '3', 'available', '0', '0', '710', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('44', NULL, 'room', '711', 'Tầng 7', '3', 'available', '0', '0', '711', '1', '2026-03-21 18:15:32', '2026-03-21 18:15:32');
INSERT INTO `tables` VALUES ('45', NULL, 'room', '712', 'Tầng 7', '3', 'available', '0', '0', '712', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('46', NULL, 'room', '714', 'Tầng 7', '3', 'available', '0', '0', '714', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('47', NULL, 'room', '715', 'Tầng 7', '3', 'available', '0', '0', '715', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('48', NULL, 'room', '716', 'Tầng 7', '3', 'available', '0', '0', '716', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('49', NULL, 'room', '717', 'Tầng 7', '3', 'available', '0', '0', '717', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('50', NULL, 'room', '718', 'Tầng 7', '3', 'available', '0', '0', '718', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('51', NULL, 'room', '719', 'Tầng 7', '3', 'available', '0', '0', '719', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('52', NULL, 'room', '720', 'Tầng 7', '3', 'available', '0', '0', '720', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('53', NULL, 'room', '801', 'Tầng 8', '3', 'available', '0', '0', '801', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('54', NULL, 'room', '802', 'Tầng 8', '3', 'available', '0', '0', '802', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('55', NULL, 'room', '803', 'Tầng 8', '3', 'available', '0', '0', '803', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('56', NULL, 'room', '804', 'Tầng 8', '3', 'available', '0', '0', '804', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('57', NULL, 'room', '805', 'Tầng 8', '3', 'available', '0', '0', '805', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('58', NULL, 'room', '806', 'Tầng 8', '3', 'available', '0', '0', '806', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('59', NULL, 'room', '807', 'Tầng 8', '3', 'available', '0', '0', '807', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('60', NULL, 'room', '808', 'Tầng 8', '3', 'available', '0', '0', '808', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('61', NULL, 'room', '809', 'Tầng 8', '3', 'available', '0', '0', '809', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('62', NULL, 'room', '810', 'Tầng 8', '3', 'available', '0', '0', '810', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('63', NULL, 'room', '811', 'Tầng 8', '3', 'available', '0', '0', '811', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('64', NULL, 'room', '812', 'Tầng 8', '3', 'available', '0', '0', '812', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('65', NULL, 'room', '814', 'Tầng 8', '3', 'available', '0', '0', '814', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('66', NULL, 'room', '815', 'Tầng 8', '3', 'available', '0', '0', '815', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('67', NULL, 'room', '816', 'Tầng 8', '3', 'available', '0', '0', '816', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('68', NULL, 'room', '817', 'Tầng 8', '3', 'available', '0', '0', '817', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('69', NULL, 'room', '818', 'Tầng 8', '3', 'available', '0', '0', '818', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('70', NULL, 'room', '819', 'Tầng 8', '3', 'available', '0', '0', '819', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('71', NULL, 'room', '901', 'Tầng 9', '3', 'available', '0', '0', '901', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('72', NULL, 'room', '902', 'Tầng 9', '3', 'available', '0', '0', '902', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('73', NULL, 'room', '903', 'Tầng 9', '3', 'available', '0', '0', '903', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('74', NULL, 'room', '904', 'Tầng 9', '3', 'available', '0', '0', '904', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('75', NULL, 'room', '905', 'Tầng 9', '3', 'available', '0', '0', '905', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('76', NULL, 'room', '906', 'Tầng 9', '3', 'available', '0', '0', '906', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('77', NULL, 'room', '907', 'Tầng 9', '3', 'available', '0', '0', '907', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('78', NULL, 'room', '908', 'Tầng 9', '3', 'available', '0', '0', '908', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('79', NULL, 'room', '909', 'Tầng 9', '3', 'available', '0', '0', '909', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('80', NULL, 'room', '910', 'Tầng 9', '3', 'available', '0', '0', '910', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('81', NULL, 'room', '911', 'Tầng 9', '3', 'available', '0', '0', '911', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('82', NULL, 'room', '912', 'Tầng 9', '3', 'available', '0', '0', '912', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('83', NULL, 'room', '914', 'Tầng 9', '3', 'available', '0', '0', '914', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('84', NULL, 'room', '915', 'Tầng 9', '3', 'available', '0', '0', '915', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('85', NULL, 'room', '916', 'Tầng 9', '3', 'available', '0', '0', '916', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('86', NULL, 'room', '917', 'Tầng 9', '3', 'available', '0', '0', '917', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('87', NULL, 'room', '918', 'Tầng 9', '3', 'available', '0', '0', '918', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('88', NULL, 'room', '919', 'Tầng 9', '3', 'available', '0', '0', '919', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('89', NULL, 'room', '920', 'Tầng 9', '3', 'available', '0', '0', '920', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('90', NULL, 'room', '921', 'Tầng 9', '3', 'available', '0', '0', '921', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('91', NULL, 'room', '922', 'Tầng 9', '3', 'available', '0', '0', '922', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('92', NULL, 'room', '923', 'Tầng 9', '3', 'available', '0', '0', '923', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('93', NULL, 'room', '1001', 'Tầng 10', '3', 'available', '0', '0', '1001', '1', '2026-03-21 18:15:33', '2026-04-01 14:12:05');
INSERT INTO `tables` VALUES ('94', NULL, 'room', '1002', 'Tầng 10', '3', 'available', '0', '0', '1002', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('95', NULL, 'room', '1003', 'Tầng 10', '3', 'available', '0', '0', '1003', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('96', NULL, 'room', '1004', 'Tầng 10', '3', 'available', '0', '0', '1004', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('97', NULL, 'room', '1005', 'Tầng 10', '3', 'available', '0', '0', '1005', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('98', NULL, 'room', '1006', 'Tầng 10', '3', 'available', '0', '0', '1006', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('99', NULL, 'room', '1007', 'Tầng 10', '3', 'available', '0', '0', '1007', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('100', NULL, 'room', '1008', 'Tầng 10', '3', 'available', '0', '0', '1008', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('101', NULL, 'room', '1009', 'Tầng 10', '3', 'available', '0', '0', '1009', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('102', NULL, 'room', '1010', 'Tầng 10', '3', 'available', '0', '0', '1010', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('103', NULL, 'room', '1011', 'Tầng 10', '3', 'available', '0', '0', '1011', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('104', NULL, 'room', '1012', 'Tầng 10', '3', 'available', '0', '0', '1012', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('105', NULL, 'room', '1014', 'Tầng 10', '3', 'available', '0', '0', '1014', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('106', NULL, 'room', '1015', 'Tầng 10', '3', 'available', '0', '0', '1015', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('107', NULL, 'room', '1016', 'Tầng 10', '3', 'available', '0', '0', '1016', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('108', NULL, 'room', '1017', 'Tầng 10', '3', 'available', '0', '0', '1017', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('109', NULL, 'room', '1018', 'Tầng 10', '3', 'available', '0', '0', '1018', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('110', NULL, 'room', '1019', 'Tầng 10', '3', 'available', '0', '0', '1019', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('111', NULL, 'room', '1020', 'Tầng 10', '3', 'available', '0', '0', '1020', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('112', NULL, 'room', '1021', 'Tầng 10', '3', 'available', '0', '0', '1021', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('113', NULL, 'room', '1022', 'Tầng 10', '3', 'available', '0', '0', '1022', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('114', NULL, 'room', '1023', 'Tầng 10', '3', 'available', '0', '0', '1023', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('115', NULL, 'room', '1101', 'Tầng 11', '3', 'available', '0', '0', '1101', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('116', NULL, 'room', '1102', 'Tầng 11', '3', 'available', '0', '0', '1102', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('117', NULL, 'room', '1103', 'Tầng 11', '3', 'available', '0', '0', '1103', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('118', NULL, 'room', '1104', 'Tầng 11', '3', 'available', '0', '0', '1104', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('119', NULL, 'room', '1105', 'Tầng 11', '3', 'available', '0', '0', '1105', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('120', NULL, 'room', '1106', 'Tầng 11', '3', 'available', '0', '0', '1106', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('121', NULL, 'room', '1107', 'Tầng 11', '3', 'available', '0', '0', '1107', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('122', NULL, 'room', '1108', 'Tầng 11', '3', 'available', '0', '0', '1108', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('123', NULL, 'room', '1109', 'Tầng 11', '3', 'available', '0', '0', '1109', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('124', NULL, 'room', '1110', 'Tầng 11', '3', 'available', '0', '0', '1110', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('125', NULL, 'room', '1111', 'Tầng 11', '3', 'available', '0', '0', '1111', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('126', NULL, 'room', '1112', 'Tầng 11', '3', 'available', '0', '0', '1112', '1', '2026-03-21 18:15:33', '2026-03-26 10:14:55');
INSERT INTO `tables` VALUES ('127', NULL, 'room', '1114', 'Tầng 11', '3', 'available', '0', '0', '1114', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('128', NULL, 'room', '1115', 'Tầng 11', '3', 'available', '0', '0', '1115', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('129', NULL, 'room', '1116', 'Tầng 11', '3', 'available', '0', '0', '1116', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('130', NULL, 'room', '1117', 'Tầng 11', '3', 'available', '0', '0', '1117', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('131', NULL, 'room', '1118', 'Tầng 11', '3', 'available', '0', '0', '1118', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('132', NULL, 'room', '1119', 'Tầng 11', '3', 'available', '0', '0', '1119', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('133', NULL, 'room', '1120', 'Tầng 11', '3', 'available', '0', '0', '1120', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('134', NULL, 'room', '1121', 'Tầng 11', '3', 'available', '0', '0', '1121', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('135', NULL, 'room', '1122', 'Tầng 11', '3', 'available', '0', '0', '1122', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('136', NULL, 'room', '1123', 'Tầng 11', '3', 'available', '0', '0', '1123', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('137', NULL, 'room', '1201', 'Tầng 12', '3', 'available', '0', '0', '1201', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('138', NULL, 'room', '1202', 'Tầng 12', '3', 'available', '0', '0', '1202', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('139', NULL, 'room', '1203', 'Tầng 12', '3', 'available', '0', '0', '1203', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('140', NULL, 'room', '1204', 'Tầng 12', '3', 'available', '0', '0', '1204', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('141', NULL, 'room', '1205', 'Tầng 12', '3', 'available', '0', '0', '1205', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('142', NULL, 'room', '1206', 'Tầng 12', '3', 'available', '0', '0', '1206', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('143', NULL, 'room', '1207', 'Tầng 12', '3', 'available', '0', '0', '1207', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('144', NULL, 'room', '1208', 'Tầng 12', '3', 'available', '0', '0', '1208', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('145', NULL, 'room', '1209', 'Tầng 12', '3', 'available', '0', '0', '1209', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('146', NULL, 'room', '1210', 'Tầng 12', '3', 'available', '0', '0', '1210', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('147', NULL, 'room', '1211', 'Tầng 12', '3', 'available', '0', '0', '1211', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('148', NULL, 'room', '1212', 'Tầng 12', '3', 'available', '0', '0', '1212', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('149', NULL, 'room', '1214', 'Tầng 12', '3', 'available', '0', '0', '1214', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('150', NULL, 'room', '1215', 'Tầng 12', '3', 'available', '0', '0', '1215', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('151', NULL, 'room', '1216', 'Tầng 12', '3', 'available', '0', '0', '1216', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('152', NULL, 'room', '1217', 'Tầng 12', '3', 'available', '0', '0', '1217', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('153', NULL, 'room', '1218', 'Tầng 12', '3', 'available', '0', '0', '1218', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('154', NULL, 'room', '1219', 'Tầng 12', '3', 'available', '0', '0', '1219', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');
INSERT INTO `tables` VALUES ('155', NULL, 'room', '1220', 'Tầng 12', '3', 'available', '0', '0', '1220', '1', '2026-03-21 18:15:33', '2026-03-21 18:15:33');

DROP TABLE IF EXISTS `user_shifts`;
CREATE TABLE `user_shifts` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(10) unsigned NOT NULL,
  `shift_id` int(10) unsigned NOT NULL,
  `work_date` date NOT NULL COMMENT 'Ngày làm việc',
  `note` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `fk_user_shifts_user` (`user_id`),
  KEY `fk_user_shifts_shift` (`shift_id`),
  CONSTRAINT `fk_user_shifts_shift` FOREIGN KEY (`shift_id`) REFERENCES `shifts` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_user_shifts_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL COMMENT 'Tên nhân viên',
  `username` varchar(50) NOT NULL COMMENT 'Tên đăng nhập',
  `pin` char(4) NOT NULL COMMENT 'PIN 4 số đăng nhập iPad',
  `role` enum('waiter','admin','it') NOT NULL DEFAULT 'waiter',
  `avatar` varchar(255) DEFAULT NULL COMMENT 'URL ảnh đại diện',
  `is_active` tinyint(1) NOT NULL DEFAULT 1 COMMENT '1=hoạt động, 0=vô hiệu',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `users` VALUES ('1', 'Admin Nhà Hàng', 'admin', '0000', 'admin', NULL, '1', '2026-03-07 18:08:27', '2026-03-26 09:00:50');
INSERT INTO `users` VALUES ('2', 'IT System', 'it', '9999', 'it', NULL, '1', '2026-03-07 18:08:27', '2026-03-07 18:08:27');
INSERT INTO `users` VALUES ('3', 'Nhân Viên Nhà Hàng', 'waiter01', '1111', 'waiter', NULL, '1', '2026-03-07 18:08:27', '2026-03-26 09:00:09');
INSERT INTO `users` VALUES ('4', 'Nhân Viên Nhà Hàng', 'waiter02', '1111', 'waiter', NULL, '1', '2026-03-07 18:08:27', '2026-03-26 09:00:36');

DROP TABLE IF EXISTS `v_activity_by_date`;
;

INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'create', 'menu_category', 'info', '13');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'login', 'user', 'info', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'delete', 'menu_clear', 'warning', '2');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'delete', 'menu_category', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'error', 'user', 'warning', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'update', 'menu_type', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-08', 'logout', 'user', 'info', '1');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'login', 'user', 'info', '9');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'create', 'order_item', 'info', '7');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'logout', 'user', 'info', '5');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'error', 'user', 'warning', '3');
INSERT INTO `v_activity_by_date` VALUES ('2026-04-07', 'create', 'order', 'info', '2');

DROP TABLE IF EXISTS `v_activity_stats_today`;
;

INSERT INTO `v_activity_stats_today` VALUES ('22', '0', '3', '19', '2');

DROP TABLE IF EXISTS `vw_location_limit`;
;

INSERT INTO `vw_location_limit` VALUES ('1', 'Giới hạn QR Restaurant', '10.95770000', '106.84480000', '500', '1', '2026-03-08 16:36:35', '2026-03-08 16:36:35');

SET FOREIGN_KEY_CHECKS = 1;
