<?php
require_once __DIR__ . '/config.php';

function db_pdo() {
  static $pdo = null;
  global $MYSQL_HOST, $MYSQL_PORT, $MYSQL_USER, $MYSQL_PASSWORD, $MYSQL_DATABASE, $MYSQL_CHARSET;
  if ($pdo === null) {
    $dsn = "mysql:host={$MYSQL_HOST};port={$MYSQL_PORT};dbname={$MYSQL_DATABASE};charset={$MYSQL_CHARSET}";
    $options = [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ];
    $pdo = new PDO($dsn, $MYSQL_USER, $MYSQL_PASSWORD, $options);
  }
  return $pdo;
}

function db_get($sql, $params = []) {
  $stmt = db_pdo()->prepare($sql);
  $stmt->execute($params);
  $row = $stmt->fetch();
  return $row ?: null;
}

function db_all($sql, $params = []) {
  $stmt = db_pdo()->prepare($sql);
  $stmt->execute($params);
  return $stmt->fetchAll();
}

function db_run($sql, $params = []) {
  $stmt = db_pdo()->prepare($sql);
  return $stmt->execute($params);
} 