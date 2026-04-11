<?php
// ============================================================
// Entry Point — Aurora Restaurant
// ============================================================

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

// Chống cache toàn cục (đặc biệt quan trọng cho Safari iPad trên Prod)
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Cache-Control: post-check=0, pre-check=0', false);
header('Pragma: no-cache');

require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/helpers/functions.php';

Auth::start();

$router = new Router();

// ── Auth ──────────────────────────────────────────────────
$router->get('/test/auth/login', 'AuthController', 'showLogin');
$router->post('/test/auth/login', 'AuthController', 'handleLogin');
$router->get('/test/auth/logout', 'AuthController', 'logout');

// ── Home: landing page for iOS Shortcut ──────────────────
$router->get('/test/home', 'AuthController', 'landing');
$router->get('/test/', 'AuthController', 'home');

// ── Waiter: Tables ────────────────────────────────────────
$router->get('/test/tables', 'TableController', 'index');
$router->get('/test/tables/getMergedChildren', 'TableController', 'getMergedChildren');
$router->post('/test/tables/open', 'TableController', 'open');
$router->post('/test/tables/close', 'TableController', 'close');
$router->post('/test/tables/merge', 'TableController', 'merge');
$router->post('/test/tables/unmerge', 'TableController', 'unmerge');
$router->post('/test/tables/transfer', 'TableController', 'transfer');
// Split/Merge advanced
$router->post('/test/tables/split', 'TableController', 'split');
$router->post('/test/tables/transfer-item', 'TableController', 'transfer_item');
$router->get('/test/tables/get-items-by-table', 'TableController', 'get_items_by_table');
$router->post('/test/tables/merge_areas', 'TableController', 'merge_areas');

$router->post('/test/tables/unmerge_areas', 'TableController', 'unmerge_areas');

// ── Waiter: Menu ─────────────────────────────────────────
$router->get('/test/menu', 'MenuController', 'index');

// ── Waiter: Orders ───────────────────────────────────────
$router->get('/test/orders', 'OrderController', 'index');
$router->post('/test/orders/add', 'OrderController', 'addItem');
$router->post('/test/orders/add-set', 'OrderController', 'addSet');
$router->post('/test/orders/update', 'OrderController', 'updateItem');
$router->post('/test/orders/update-guest-count', 'OrderController', 'updateGuestCount');
$router->post('/test/orders/remove', 'OrderController', 'removeItem');
$router->post('/test/orders/update-note', 'OrderController', 'updateItemNote');
$router->post('/test/orders/confirm', 'OrderController', 'confirmOrder');
$router->get('/test/orders/history', 'OrderController', 'history');
$router->get('/test/orders/get-detail', 'OrderController', 'getOrderDetail');
$router->get('/test/orders/print', 'OrderController', 'print');

// ── Customer & Waiter: Support & Payment Requests ───────
$router->post('/test/support/request', 'SupportController', 'makeRequest');
$router->get('/test/support/pending', 'SupportController', 'getPending');
$router->post('/test/support/resolve', 'SupportController', 'resolve');

// ── Admin: Real-time Monitoring ──────────────────────────
$router->get('/test/admin/realtime', 'AdminRealtimeController', 'index');
$router->get('/test/admin/realtime/data', 'AdminRealtimeController', 'data');
$router->post('/test/admin/realtime/dismiss', 'AdminRealtimeController', 'dismiss');
$router->get('/test/admin/realtime/qr-sessions', 'AdminRealtimeController', 'qrSessions');

// ── Admin: Shift Management ───────────────────────────────
$router->get('/test/admin/shifts', 'AdminShiftController', 'index');
$router->post('/test/admin/shifts/store', 'AdminShiftController', 'store');
$router->post('/test/admin/shifts/delete', 'AdminShiftController', 'delete');
$router->post('/test/admin/shifts/assign', 'AdminShiftController', 'assign');
$router->post('/test/admin/shifts/remove_assign', 'AdminShiftController', 'removeAssign');
$router->get('/test/admin/menu', 'AdminMenuController', 'index');
$router->get('/test/admin/menu/create', 'AdminMenuController', 'create');
$router->post('/test/admin/menu/store', 'AdminMenuController', 'store');
$router->get('/test/admin/menu/edit', 'AdminMenuController', 'edit');
$router->post('/test/admin/menu/update', 'AdminMenuController', 'update');
$router->post('/test/admin/menu/delete', 'AdminMenuController', 'delete');
$router->post('/test/admin/menu/toggle', 'AdminMenuController', 'toggle');
// Clear menu data (IT only)
$router->get('/test/admin/menu/clear', 'AdminMenuController', 'clearPage');
$router->post('/test/admin/menu/clear', 'AdminMenuController', 'clear');

// ── Admin: Menu Types (Phân loại menu) ────────────────────
$router->get('/test/admin/menu-types', 'AdminMenuTypeController', 'index');
$router->post('/test/admin/menu-types/store', 'AdminMenuTypeController', 'store');
$router->get('/test/admin/menu-types/edit', 'AdminMenuTypeController', 'edit');
$router->post('/test/admin/menu-types/update', 'AdminMenuTypeController', 'update');
$router->post('/test/admin/menu-types/delete', 'AdminMenuTypeController', 'delete');
$router->post('/test/admin/menu-types/toggle', 'AdminMenuTypeController', 'toggle');

// ── Admin: Menu Sets (À la carte) ─────────────────────────
$router->get('/test/admin/menu/sets', 'AdminMenuSetController', 'index');
$router->post('/test/admin/menu/sets/store', 'AdminMenuSetController', 'store');
$router->post('/test/admin/menu/sets/update', 'AdminMenuSetController', 'update');
$router->post('/test/admin/menu/sets/delete', 'AdminMenuSetController', 'delete');
$router->post('/test/admin/menu/sets/toggle', 'AdminMenuSetController', 'toggle');

// ── Admin: Categories ─────────────────────────────────────
$router->get('/test/admin/categories', 'AdminCategoryController', 'index');
$router->get('/test/admin/categories/edit', 'AdminCategoryController', 'edit');
$router->post('/test/admin/categories/store', 'AdminCategoryController', 'store');
$router->post('/test/admin/categories/update', 'AdminCategoryController', 'update');
$router->post('/test/admin/categories/delete', 'AdminCategoryController', 'delete');

// ── Admin: Tables Management ──────────────────────────────
$router->get('/test/admin/tables', 'AdminTableController', 'index');
$router->get('/test/admin/tables/edit', 'AdminTableController', 'edit');
$router->post('/test/admin/tables/store', 'AdminTableController', 'store');
$router->post('/test/admin/tables/update', 'AdminTableController', 'update');
$router->post('/test/admin/tables/delete', 'AdminTableController', 'delete');

// ── Admin: Reports ────────────────────────────────────────
$router->get('/test/admin/reports', 'ReportController', 'index');

// ── IT: User Management ───────────────────────────────────
$router->get('/test/it/users', 'SettingController', 'users');
$router->get('/test/it/users/edit', 'SettingController', 'editUser');
$router->post('/test/it/users/store', 'SettingController', 'storeUser');
$router->post('/test/it/users/update', 'SettingController', 'updateUser');
$router->post('/test/it/users/delete', 'SettingController', 'deleteUser');

// ── IT: Database Backup ───────────────────────────────────
$router->get('/test/it/database', 'SettingController', 'database');
$router->get('/test/it/database/backup', 'SettingController', 'backup');
$router->get('/test/it/database/download', 'SettingController', 'downloadBackup');
$router->post('/test/it/database/delete', 'SettingController', 'deleteBackup');

// ── IT: Database Cleanup ──────────────────────────────────
$router->post('/test/it/database/cleanup/all', 'SettingController', 'cleanupAll');
$router->post('/test/it/database/cleanup/orders', 'SettingController', 'cleanupOrders');
$router->post('/test/it/database/cleanup/table', 'SettingController', 'cleanupTable');

// ── IT: Settings Management ───────────────────────────────
$router->get('/test/it/settings', 'SettingController', 'settings');
$router->post('/test/it/settings/update', 'SettingController', 'updateSetting');
$router->post('/test/it/settings/reset', 'SettingController', 'resetSetting');

// ── Admin: Activity Logs ─────────────────────────────────
$router->get('/test/admin/activity', 'AdminActivityController', 'index');
$router->get('/test/admin/activity/data', 'AdminActivityController', 'data');
$router->get('/test/admin/activity/entityLogs', 'AdminActivityController', 'entityLogs');
$router->post('/test/admin/activity/cleanup', 'AdminActivityController', 'cleanup');
$router->get('/test/admin/activity/export', 'AdminActivityController', 'export');

// ── QR Ordering: Customer ──────────────────────────────────
$router->get('/test/q', 'QrMenuController', 'shortLink');
$router->get('/test/qr/landing', 'QrMenuController', 'landing');
$router->get('/test/qr/menu', 'QrMenuController', 'index');
$router->post('/test/qr/menu/location', 'QrMenuController', 'saveLocation');
$router->post('/test/qr/cart/add', 'QrMenuController', 'addToCart');
$router->post('/test/qr/cart/update', 'QrMenuController', 'updateCart');
$router->post('/test/qr/cart/remove', 'QrMenuController', 'removeFromCart');
$router->post('/test/qr/order/submit', 'QrOrderController', 'submit');
$router->post('/test/qr/session/clear', 'QrOrderController', 'clearSession');
$router->get('/test/qr/order/status', 'QrOrderController', 'status');
$router->get('/test/qr/order/poll-status', 'QrOrderController', 'pollStatus');
$router->get('/test/qr/order/history', 'QrOrderController', 'history');
$router->get('/test/qr/order/customer-history', 'QrOrderController', 'customerHistory');
$router->get('/test/qr/sessions', 'QrMenuController', 'sessions');
$router->get('/test/qr/thank-you', 'QrOrderController', 'thankYou');
$router->post('/test/qr/support/call-waiter', 'QrSupportController', 'callWaiter');
$router->post('/test/qr/support/request-bill', 'QrSupportController', 'requestBill');

// ── QR Ordering: Admin ─────────────────────────────────────
$router->get('/test/admin/qr-codes', 'AdminQrController', 'index');
$router->post('/test/admin/qr-codes/generate', 'AdminQrController', 'generate');
$router->get('/test/admin/qr-codes/download', 'AdminQrController', 'download');
$router->post('/test/admin/qr-codes/delete', 'AdminQrController', 'delete');

// ── Notifications: Real-time Polling ───────────────────────
$router->get('/test/notifications', 'NotificationController', 'waiterIndex');
$router->get('/test/api/notifications/poll', 'NotificationController', 'poll');
$router->post('/test/api/notifications/mark-read', 'NotificationController', 'markRead');
$router->post('/test/api/notifications/resolve-support', 'NotificationController', 'resolveSupport');

$router->dispatch();
