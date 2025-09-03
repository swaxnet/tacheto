<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';
$batchId = (int)($_GET['id'] ?? 0);
$schoolId = isset($_GET['school']) ? (int)$_GET['school'] : null;
$batch = q_one('SELECT * FROM results_batches WHERE id = ?',[$batchId]);
if(!$batch){ http_response_code(404); render('errors/404',['title'=>'Haipatikani']); return; }
if($schoolId){
  $row = q_one('SELECT sr.*, s.name as school_name FROM school_results sr JOIN schools s ON s.id=sr.school_id WHERE sr.id = ? AND sr.batch_id = ?',[$schoolId,$batchId]);
  if(!$row){ http_response_code(404); render('errors/404',['title'=>'Haipatikani']); return; }
  $title = 'Matokeo - '.$row['school_name'];
  $pdfUrl = base_url($row['pdf_path']);
}else{
  if(empty($batch['summary_pdf_path'])){ http_response_code(404); render('errors/404',['title'=>'Haipatikani']); return; }
  $title = 'Muhtasari wa Matokeo - '.$batch['title'];
  $pdfUrl = base_url($batch['summary_pdf_path']);
}
render('public/pdf_viewer',['title'=>$title,'pdfUrl'=>$pdfUrl,'backUrl'=>base_url('batch?id='.$batchId),'downloadUrl'=>$pdfUrl]); 