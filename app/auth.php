<?php
require_once __DIR__ . '/db.php';

function current_user(){ return $_SESSION['user'] ?? null; }
function require_admin(){ if(!current_user()){ header('Location: '.base_url('auth/login')); exit; } }

function login($email,$password){
  $u = q_one('SELECT * FROM users WHERE email = ?',[$email]);
  if(!$u) return false;
  if(!password_verify($password,$u['password_hash'])) return false;
  $_SESSION['user']=['id'=>$u['id'],'email'=>$u['email'],'role'=>$u['role']];
  return true;
}
function logout(){ unset($_SESSION['user']); session_regenerate_id(true); } 