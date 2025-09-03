<?php
require_once __DIR__ . '/env.php';

function base_url($path=''){
  global $APP_URL;
  if ($APP_URL) return rtrim($APP_URL,'/').'/'.ltrim($path,'/');
  $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS']!=='off')?'https':'http';
  $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
  return rtrim("$scheme://$host",'/').'/'.ltrim($path,'/');
}

function render($view,$data=[]){
  extract($data);
  $viewFile = __DIR__ . "/views/$view.php";
  ob_start(); include $viewFile; $content = ob_get_clean();
  include __DIR__ . '/views/layout.php';
}

function csrf_token(){ if(empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field(){ echo '<input type="hidden" name="_csrf" value="'.htmlspecialchars(csrf_token(),ENT_QUOTES).'">'; }
function verify_csrf(){ if(($_POST['_csrf']??'')!==($_SESSION['csrf']??'')) { http_response_code(419); exit('CSRF token invalid'); } }

function h($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); } 