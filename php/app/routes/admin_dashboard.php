<?php
require_once __DIR__ . '/../db.php';
require_once __DIR__ . '/../helpers.php';

$schools = (int)(db_get('SELECT COUNT(*) as c FROM schools')['c'] ?? 0);
$batches = (int)(db_get('SELECT COUNT(*) as c FROM results_batches')['c'] ?? 0);
$results = (int)(db_get('SELECT COUNT(*) as c FROM school_results')['c'] ?? 0);

render('admin/dashboard', [
  'title' => 'Dashibodi',
  'metrics' => [
    'schools' => $schools,
    'batches' => $batches,
    'results' => $results,
  ],
]); 