<?php
// Front controller
session_start();

require_once __DIR__ . '/../app/config.php';
require_once __DIR__ . '/../app/db.php';
require_once __DIR__ . '/../app/auth.php';
require_once __DIR__ . '/../app/helpers.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Static assets under /css and /uploads served by web server

// Routes
if ($path === '/' && $method === 'GET') {
  require __DIR__ . '/../app/routes/home.php';
  exit;
}

if (preg_match('#^/batch/(\d+)$#', $path, $m) && $method === 'GET') {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../app/routes/batch.php';
  exit;
}

if (preg_match('#^/batch/(\d+)/summary$#', $path, $m) && $method === 'GET') {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../app/routes/batch_summary.php';
  exit;
}

if (preg_match('#^/school-result/(\d+)/view$#', $path, $m) && $method === 'GET') {
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../app/routes/school_result_view.php';
  exit;
}

// Auth
if ($path === '/auth/login' && $method === 'GET') {
  require __DIR__ . '/../app/routes/auth_login_get.php';
  exit;
}
if ($path === '/auth/login' && $method === 'POST') {
  require __DIR__ . '/../app/routes/auth_login_post.php';
  exit;
}
if ($path === '/auth/logout' && $method === 'POST') {
  require __DIR__ . '/../app/routes/auth_logout_post.php';
  exit;
}

// Admin
if ($path === '/admin' && $method === 'GET') {
  require_admin();
  require __DIR__ . '/../app/routes/admin_dashboard.php';
  exit;
}
if ($path === '/admin/batches' && $method === 'GET') {
  require_admin();
  require __DIR__ . '/../app/routes/admin_batches_get.php';
  exit;
}
if ($path === '/admin/batches' && $method === 'POST') {
  require_admin();
  require __DIR__ . '/../app/routes/admin_batches_post.php';
  exit;
}
if (preg_match('#^/admin/batches/(\d+)/delete$#', $path, $m) && $method === 'POST') {
  require_admin();
  $_POST['id'] = (int)$m[1];
  require __DIR__ . '/../app/routes/admin_batches_delete_post.php';
  exit;
}
if (preg_match('#^/admin/batches/(\d+)/schools$#', $path, $m) && $method === 'GET') {
  require_admin();
  $_GET['id'] = (int)$m[1];
  require __DIR__ . '/../app/routes/admin_batch_schools_get.php';
  exit;
}
if (preg_match('#^/admin/batches/(\d+)/schools$#', $path, $m) && $method === 'POST') {
  require_admin();
  $_POST['batch_id'] = (int)$m[1];
  require __DIR__ . '/../app/routes/admin_batch_schools_post.php';
  exit;
}
if (preg_match('#^/admin/batches/(\d+)/schools/(\d+)/delete$#', $path, $m) && $method === 'POST') {
  require_admin();
  $_POST['batch_id'] = (int)$m[1];
  $_POST['id'] = (int)$m[2];
  require __DIR__ . '/../app/routes/admin_batch_schools_delete_post.php';
  exit;
}

http_response_code(404);
render('404', ['title' => 'Haipatikani']); 