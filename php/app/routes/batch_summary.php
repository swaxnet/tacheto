<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$batchId = (int)($_GET['id'] ?? 0);
$batch = db_get('SELECT * FROM results_batches WHERE id = ?', [$batchId]);
if (!$batch || empty($batch['summary_pdf_path'])) {
  http_response_code(404);
  render('404', ['title' => 'Haipatikani']);
  return;
}

$pdfUrl = '/' . ltrim($batch['summary_pdf_path'], '/');
render('public/pdf_viewer', [
  'title' => 'Muhtasari wa Matokeo - ' . $batch['title'],
  'pdfUrl' => $pdfUrl,
  'backUrl' => '/batch/' . $batchId,
  'downloadUrl' => $pdfUrl,
]); 