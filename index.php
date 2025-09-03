<?php
session_start();
require_once __DIR__ . '/app/env.php';
require_once __DIR__ . '/app/db.php';
require_once __DIR__ . '/app/helpers.php';
require_once __DIR__ . '/app/auth.php';

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

// Routes
$routes = [
  ['GET','/','home'],
  ['GET','/batch','batch'],
  ['GET','/batch/view','batch_view'],
  ['GET','/auth/login','auth_login_get'],
  ['POST','/auth/login','auth_login_post'],
  ['POST','/auth/logout','auth_logout_post'],
  ['GET','/admin','admin_dashboard', true],
  ['GET','/admin/batches','admin_batches_get', true],
  ['POST','/admin/batches','admin_batches_post', true],
  ['POST','/admin/batches/delete','admin_batches_delete_post', true],
  ['GET','/admin/batches/schools','admin_batch_schools_get', true],
  ['POST','/admin/batches/schools','admin_batch_schools_post', true],
  ['POST','/admin/batches/schools/delete','admin_batch_schools_delete_post', true],
];

foreach ($routes as $r) {
  [$m,$p,$h,$pr] = [$r[0],$r[1],$r[2],$r[3] ?? false];
  if ($method === $m && $path === $p) {
    if ($pr) require_admin();
    require __DIR__ . "/app/routes/{$h}.php";
    exit;
  }
}

http_response_code(404);
render('errors/404', ['title' => 'Haipatikani']); 