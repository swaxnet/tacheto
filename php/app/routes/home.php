<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

list($page, $pageSize, $offset) = pagination_params();
$search = trim($_GET['q'] ?? '');
$where = [];
$params = [];
if ($search !== '') {
  $where[] = '(form_level LIKE ? OR CAST(year AS CHAR) LIKE ? OR title LIKE ?)';
  $like = '%' . $search . '%';
  $params[] = $like; $params[] = $like; $params[] = $like;
}
$whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

$totalRow = db_get("SELECT COUNT(*) AS c FROM results_batches $whereSql", $params);
$total = (int)($totalRow['c'] ?? 0);
$batches = db_all(
  "SELECT * FROM results_batches $whereSql ORDER BY year DESC, created_at DESC LIMIT ? OFFSET ?",
  array_merge($params, [$pageSize, $offset])
);

render('public/home', [
  'title' => 'Matokeo - Tacheto',
  'batches' => $batches,
  'search' => $search,
  'page' => $page,
  'pageSize' => $pageSize,
  'total' => $total,
]); 