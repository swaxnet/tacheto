<?php
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../auth.php';
verify_csrf();
logout();
header('Location: '.base_url('/')); 