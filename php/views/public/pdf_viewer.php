<div class="card-header">
	<h1><?= h($title) ?></h1>
	<div class="actions">
		<a class="btn" href="<?= h($backUrl) ?>">Rudi</a>
		<a class="btn btn-secondary" download href="<?= h($downloadUrl) ?>">Pakua PDF</a>
	</div>
</div>
<div class="pdf-container">
	<iframe src="<?= h($pdfUrl) ?>#toolbar=1&navpanes=0&scrollbar=1" title="PDF" width="100%" height="100%" style="border:0"></iframe>
</div> 