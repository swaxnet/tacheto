<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$batchId = (int)($_GET['id'] ?? 0);
$batch = db_get('SELECT * FROM results_batches WHERE id = ?', [$batchId]);
if (!$batch) {
  http_response_code(404);
  render('404', ['title' => 'Haipatikani']);
  return;
}

list($page, $pageSize, $offset) = pagination_params();
$search = trim($_GET['q'] ?? '');
$where = ['sr.batch_id = ?'];
$params = [$batchId];
if ($search !== '') {
  $where[] = 's.name LIKE ?';
  $params[] = '%' . $search . '%';
}
$whereSql = 'WHERE ' . implode(' AND ', $where);

$totalRow = db_get("SELECT COUNT(*) as c FROM school_results sr JOIN schools s ON s.id = sr.school_id $whereSql", $params);
$total = (int)($totalRow['c'] ?? 0);

$schools = db_all(
  "SELECT sr.*, s.name as school_name, s.code as school_code
   FROM school_results sr
   JOIN schools s ON s.id = sr.school_id
   $whereSql
   ORDER BY s.name ASC
   LIMIT ? OFFSET ?",
  array_merge($params, [$pageSize, $offset])
);

render('public/batch', [
  'title' => $batch['title'],
  'batch' => $batch,
  'schools' => $schools,
  'search' => $search,
  'page' => $page,
  'pageSize' => $pageSize,
  'total' => $total,
]); 