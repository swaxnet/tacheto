<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';
$id = (int)($_GET['id'] ?? 0);
$batch = q_one('SELECT * FROM results_batches WHERE id = ?',[$id]);
if(!$batch){ http_response_code(404); render('errors/404',['title'=>'Haipatikani']); return; }
$schools = q_all('SELECT sr.*, s.name as school_name, s.code as school_code FROM school_results sr JOIN schools s ON s.id=s.school_id WHERE sr.batch_id = ? ORDER BY s.name ASC',[$id]);
render('public/batch',['title'=>$batch['title'], 'batch'=>$batch, 'schools'=>$schools]); 