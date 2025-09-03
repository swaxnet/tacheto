<?php
// Basic env
$APP_URL = getenv('APP_URL') ?: '';
$MYSQL_HOST = getenv('MYSQL_HOST') ?: '127.0.0.1';
$MYSQL_PORT = (int)(getenv('MYSQL_PORT') ?: 3306);
$MYSQL_USER = getenv('MYSQL_USER') ?: 'root';
$MYSQL_PASSWORD = getenv('MYSQL_PASSWORD') ?: '';
$MYSQL_DATABASE = getenv('MYSQL_DATABASE') ?: 'tacheto';
$MYSQL_CHARSET = 'utf8mb4';
$UPLOAD_DIR = __DIR__ . '/../public/uploads';
if (!is_dir($UPLOAD_DIR)) @mkdir($UPLOAD_DIR, 0775, true); 