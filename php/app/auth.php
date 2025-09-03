<?php
require_once __DIR__ . '/db.php';
require_once __DIR__ . '/helpers.php';

function current_user() {
  return $_SESSION['auth_user'] ?? null;
}

function require_admin() {
  if (!current_user()) {
    redirect('/auth/login');
  }
}

function attempt_login($email, $password) {
  $user = db_get('SELECT * FROM users WHERE email = ?', [$email]);
  if (!$user) return false;
  if (!password_verify($password, $user['password_hash'])) return false;
  $_SESSION['auth_user'] = [
    'id' => $user['id'],
    'email' => $user['email'],
    'role' => $user['role']
  ];
  return true;
}

function logout_user() {
  unset($_SESSION['auth_user']);
  session_regenerate_id(true);
} 