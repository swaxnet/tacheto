<?php
// App configuration via environment or defaults
$APP_URL = getenv('APP_URL') ?: '';
$UPLOAD_DIR = getenv('UPLOAD_DIR') ?: __DIR__ . '/../public/uploads';

$MYSQL_HOST = getenv('MYSQL_HOST') ?: '127.0.0.1';
$MYSQL_PORT = (int)(getenv('MYSQL_PORT') ?: 3306);
$MYSQL_USER = getenv('MYSQL_USER') ?: 'root';
$MYSQL_PASSWORD = getenv('MYSQL_PASSWORD') ?: '';
$MYSQL_DATABASE = getenv('MYSQL_DATABASE') ?: 'tacheto';
$MYSQL_CHARSET = 'utf8mb4';

$SESSION_SECRET = getenv('SESSION_SECRET') ?: 'change_this_secret';

if (!is_dir($UPLOAD_DIR)) {
  @mkdir($UPLOAD_DIR, 0775, true);
} 