<?php
require_once __DIR__ . '/config.php';

function base_url($path = '') {
  global $APP_URL;
  if ($APP_URL) return rtrim($APP_URL, '/') . '/' . ltrim($path, '/');
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  $base = rtrim($scheme . '://' . $host, '/');
  return $base . '/' . ltrim($path, '/');
}

function render($view, $data = []) {
  extract($data);
  $viewFile = __DIR__ . '/../views/' . $view . '.php';
  if (!file_exists($viewFile)) {
    http_response_code(500);
    echo 'View not found: ' . htmlspecialchars($view);
    return;
  }
  ob_start();
  include $viewFile;
  $content = ob_get_clean();
  include __DIR__ . '/../views/layout.php';
}

function redirect($path) {
  header('Location: ' . base_url($path));
  exit;
}

function set_flash($key, $value) {
  $_SESSION['flash'][$key] = $value;
}

function get_flash($key) {
  if (!empty($_SESSION['flash'][$key])) {
    $v = $_SESSION['flash'][$key];
    unset($_SESSION['flash'][$key]);
    return $v;
  }
  return null;
}

function is_post() { return $_SERVER['REQUEST_METHOD'] === 'POST'; }

function h($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function pagination_params() {
  $page = max(1, (int)($_GET['page'] ?? 1));
  $pageSize = max(1, min(50, (int)($_GET['pageSize'] ?? 10)));
  $offset = ($page - 1) * $pageSize;
  return [$page, $pageSize, $offset];
} 