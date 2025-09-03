<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$batchId = (int)($_GET['id'] ?? 0);
$batch = db_get('SELECT * FROM results_batches WHERE id = ?', [$batchId]);
if (!$batch) { redirect('/admin/batches'); }

$schools = db_all('SELECT * FROM schools ORDER BY name ASC');
$uploads = db_all(
  'SELECT sr.*, s.name as school_name FROM school_results sr JOIN schools s ON s.id = sr.school_id WHERE sr.batch_id = ? ORDER BY s.name ASC',
  [$batchId]
);

render('admin/batch_schools', [
  'title' => 'Matokeo ya Shule - ' . $batch['title'],
  'batch' => $batch,
  'schools' => $schools,
  'uploads' => $uploads,
]); 