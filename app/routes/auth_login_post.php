<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../auth.php';
if($_SERVER['REQUEST_METHOD']!=='POST'){ header('Location: '.base_url('auth/login')); exit; }
verify_csrf();
$email=trim($_POST['email']??'');$password=(string)($_POST['password']??'');
if(!$email||!$password){ $_SESSION['error']='Weka barua pepe na neno siri.'; header('Location: '.base_url('auth/login')); exit; }
if(!login($email,$password)){ $_SESSION['error']='Email au neno siri sio sahihi.'; header('Location: '.base_url('auth/login')); exit; }
header('Location: '.base_url('admin')); 