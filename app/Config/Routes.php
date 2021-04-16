<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php'))
{
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */
// We get a performance increase by specifying the default
// route since we don't have to scan directories.
// トップページ
$routes->get('/', 'HomeController::index');

// ログイン
$routes->get('/login', 'LoginController::index');
$routes->post("/login", "LoginController::authentication");
$routes->get("/login/twitter", "LoginController::callback");

// ログアウト
$routes->get('/logout', 'LogoutController::index');

// ユーザー登録
$routes->get('/signup', 'UserController::index');
$routes->post('/signup', 'UserController::register');
$routes->get('/signup/twitter', 'UserController::callback');

// 板一覧
$routes->get('/boards', 'BoardsController::index');

// スレッド一覧
$routes->get('/(:segment)', 'ThreadsController::index/$1');
$routes->post('/bbs/write.cgi/new', 'ThreadsController::write');

// スレッド
$routes->get('/bbs/read.cgi/(:segment)/(:num)', 'ResponsesController::read/$1/$2');
$routes->get('/bbs/read.cgi/(:segment)/(:num)/(:num)', 'ResponsesController::read/$1/$2/$3');
$routes->post('/bbs/write.cgi', 'ResponsesController::write');

// 管理画面
$routes->get('/(:segment)/admin', 'AdminController::index/$1');
$routes->post('/(:segment)/admin', 'AdminController::update/$1');

// PhpInfo
// $routes->get('/phpinfo', 'PhpInfoController::index');

/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php'))
{
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}
