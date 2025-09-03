<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$batchId = (int)($_POST['batch_id'] ?? 0);
$id = (int)($_POST['id'] ?? 0);
$row = db_get('SELECT pdf_path FROM school_results WHERE id = ?', [$id]);
if ($row && !empty($row['pdf_path'])) {
  $full = __DIR__ . '/../../public/' . $row['pdf_path'];
  if (is_file($full)) {@unlink($full);} 
}
db_run('DELETE FROM school_results WHERE id = ?', [$id]);
set_flash('success', 'PDF ya shule imefutwa.');
redirect('/admin/batches/' . $batchId . '/schools'); 