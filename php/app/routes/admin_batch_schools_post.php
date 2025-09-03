<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../config.php';

$batchId = (int)($_POST['batch_id'] ?? 0);
$school_id = (int)($_POST['school_id'] ?? 0);

if (empty($_FILES['schoolPdf']['name'])) { redirect('/admin/batches/' . $batchId . '/schools'); }
$ext = strtolower(pathinfo($_FILES['schoolPdf']['name'], PATHINFO_EXTENSION));
if ($ext !== 'pdf') { set_flash('error', 'PDF tu inaruhusiwa'); redirect('/admin/batches/' . $batchId . '/schools'); }

$name = time() . '-' . bin2hex(random_bytes(4)) . '.pdf';
$dest = $UPLOAD_DIR . '/' . $name;
if (!move_uploaded_file($_FILES['schoolPdf']['tmp_name'], $dest)) {
  set_flash('error', 'Imeshindwa kupakia PDF');
  redirect('/admin/batches/' . $batchId . '/schools');
}
$pdf_path = 'uploads/' . $name;

db_run('INSERT INTO school_results (batch_id, school_id, pdf_path) VALUES (?, ?, ?)', [$batchId, $school_id, $pdf_path]);
set_flash('success', 'PDF ya shule imepakiwa.');
redirect('/admin/batches/' . $batchId . '/schools'); 