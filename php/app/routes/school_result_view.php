<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$id = (int)($_GET['id'] ?? 0);
$row = db_get(
  'SELECT sr.*, s.name as school_name, s.code as school_code, b.title as batch_title FROM school_results sr JOIN schools s ON s.id = sr.school_id JOIN results_batches b ON b.id = sr.batch_id WHERE sr.id = ?',
  [$id]
);
if (!$row) {
  http_response_code(404);
  render('404', ['title' => 'Haipatikani']);
  return;
}

$pdfUrl = '/' . ltrim($row['pdf_path'], '/');
render('public/pdf_viewer', [
  'title' => 'Matokeo - ' . $row['school_name'],
  'pdfUrl' => $pdfUrl,
  'backUrl' => '/batch/' . $row['batch_id'],
  'downloadUrl' => $pdfUrl,
]); 