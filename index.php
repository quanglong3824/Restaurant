<?php
// ============================================================
// Entry Point — Aurora Restaurant
// ============================================================

require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Auth.php';
require_once __DIR__ . '/core/Controller.php';
require_once __DIR__ . '/core/Model.php';
require_once __DIR__ . '/core/Router.php';
require_once __DIR__ . '/helpers/functions.php';

Auth::start();

$router = new Router();

// ── Auth ──────────────────────────────────────────────────
$router->get('/auth/login', 'AuthController', 'showLogin');
$router->post('/auth/login', 'AuthController', 'handleLogin');
$router->get('/auth/logout', 'AuthController', 'logout');

// ── Home: redirect by role ────────────────────────────────
$router->get('/', 'AuthController', 'home');

// ── Waiter: Tables ────────────────────────────────────────
$router->get('/tables', 'TableController', 'index');
$router->post('/tables/open', 'TableController', 'open');
$router->post('/tables/close', 'TableController', 'close');

// ── Waiter: Menu ─────────────────────────────────────────
$router->get('/menu', 'MenuController', 'index');

// ── Waiter: Orders ───────────────────────────────────────
$router->get('/orders', 'OrderController', 'index');
$router->post('/orders/add', 'OrderController', 'addItem');
$router->post('/orders/update', 'OrderController', 'updateItem');
$router->post('/orders/remove', 'OrderController', 'removeItem');
$router->post('/orders/confirm', 'OrderController', 'confirmOrder');

// ── Admin: Menu Management ────────────────────────────────
$router->get('/admin/menu', 'AdminMenuController', 'index');
$router->get('/admin/menu/create', 'AdminMenuController', 'create');
$router->post('/admin/menu/store', 'AdminMenuController', 'store');
$router->get('/admin/menu/edit', 'AdminMenuController', 'edit');
$router->post('/admin/menu/update', 'AdminMenuController', 'update');
$router->post('/admin/menu/delete', 'AdminMenuController', 'delete');
$router->post('/admin/menu/toggle', 'AdminMenuController', 'toggle');

// ── Admin: Categories ─────────────────────────────────────
$router->get('/admin/categories', 'AdminCategoryController', 'index');
$router->get('/admin/categories/edit', 'AdminCategoryController', 'edit');
$router->post('/admin/categories/store', 'AdminCategoryController', 'store');
$router->post('/admin/categories/update', 'AdminCategoryController', 'update');
$router->post('/admin/categories/delete', 'AdminCategoryController', 'delete');

// ── Admin: Tables Management ──────────────────────────────
$router->get('/admin/tables', 'AdminTableController', 'index');
$router->get('/admin/tables/edit', 'AdminTableController', 'edit');
$router->post('/admin/tables/store', 'AdminTableController', 'store');
$router->post('/admin/tables/update', 'AdminTableController', 'update');
$router->post('/admin/tables/delete', 'AdminTableController', 'delete');

// ── Admin: Reports ────────────────────────────────────────
$router->get('/admin/reports', 'ReportController', 'index');

// ── IT: User Management ───────────────────────────────────
$router->get('/it/users', 'SettingController', 'users');
$router->get('/it/users/edit', 'SettingController', 'editUser');
$router->post('/it/users/store', 'SettingController', 'storeUser');
$router->post('/it/users/update', 'SettingController', 'updateUser');
$router->post('/it/users/delete', 'SettingController', 'deleteUser');

// ── IT: Database Backup ───────────────────────────────────
$router->get('/it/database', 'SettingController', 'database');
$router->get('/it/database/backup', 'SettingController', 'backup');

$router->dispatch();
