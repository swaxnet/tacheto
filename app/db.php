<?php
require_once __DIR__ . '/env.php';

function db() {
  static $pdo = null;
  global $MYSQL_HOST,$MYSQL_PORT,$MYSQL_USER,$MYSQL_PASSWORD,$MYSQL_DATABASE,$MYSQL_CHARSET;
  if ($pdo === null) {
    $dsn = "mysql:host={$MYSQL_HOST};port={$MYSQL_PORT};dbname={$MYSQL_DATABASE};charset={$MYSQL_CHARSET}";
    $pdo = new PDO($dsn, $MYSQL_USER, $MYSQL_PASSWORD, [
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
      PDO::ATTR_EMULATE_PREPARES => false,
    ]);
  }
  return $pdo;
}

function q_one($sql,$params=[]) { $st = db()->prepare($sql); $st->execute($params); $r=$st->fetch(); return $r?:null; }
function q_all($sql,$params=[]) { $st = db()->prepare($sql); $st->execute($params); return $st->fetchAll(); }
function q_run($sql,$params=[]) { $st = db()->prepare($sql); return $st->execute($params); } 