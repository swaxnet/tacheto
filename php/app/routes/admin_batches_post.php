<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';
require_once __DIR__ . '/../config.php';

$form_level = trim($_POST['form_level'] ?? '');
$year = (int)($_POST['year'] ?? 0);
$title = trim($_POST['title'] ?? '');

$summary_pdf_path = null;
if (!empty($_FILES['summaryPdf']['name'])) {
  $ext = strtolower(pathinfo($_FILES['summaryPdf']['name'], PATHINFO_EXTENSION));
  if ($ext !== 'pdf') {
    set_flash('error', 'PDF tu inaruhusiwa');
    redirect('/admin/batches');
  }
  $name = time() . '-' . bin2hex(random_bytes(4)) . '.pdf';
  $dest = $UPLOAD_DIR . '/' . $name;
  if (!move_uploaded_file($_FILES['summaryPdf']['tmp_name'], $dest)) {
    set_flash('error', 'Imeshindwa kupakia PDF');
    redirect('/admin/batches');
  }
  $summary_pdf_path = 'uploads/' . $name;
}

db_run('INSERT INTO results_batches (form_level, year, title, summary_pdf_path) VALUES (?, ?, ?, ?)', [
  $form_level, $year, $title, $summary_pdf_path
]);

set_flash('success', 'Batch imeundwa.');
redirect('/admin/batches'); 