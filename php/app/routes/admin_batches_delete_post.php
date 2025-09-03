<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$id = (int)($_POST['id'] ?? 0);
$batch = db_get('SELECT summary_pdf_path FROM results_batches WHERE id = ?', [$id]);
if ($batch && !empty($batch['summary_pdf_path'])) {
  $full = __DIR__ . '/../../' . $batch['summary_pdf_path'];
  if (is_file($full)) {@unlink($full);} 
}
db_run('DELETE FROM results_batches WHERE id = ?', [$id]);
set_flash('success', 'Batch imefutwa.');
redirect('/admin/batches'); 