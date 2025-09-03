<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$search = trim($_GET['q'] ?? '');
$params=[];$where='';
if($search!==''){ $where='WHERE form_level LIKE ? OR CAST(year AS CHAR) LIKE ? OR title LIKE ?'; $like="%$search%"; $params=[$like,$like,$like]; }
$batches = q_all("SELECT * FROM results_batches $where ORDER BY year DESC, created_at DESC LIMIT 25", $params);
render('public/home',[ 'title'=>'Matokeo - Tacheto','batches'=>$batches,'search'=>$search ]); 