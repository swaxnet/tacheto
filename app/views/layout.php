<!doctype html>
<html lang="sw">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= isset($title)?h($title):'Tacheto' ?></title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="<?= h(base_url('assets/css/app.css')) ?>">
</head>
<body>
	<nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm">
		<div class="container">
			<a class="navbar-brand fw-bold" href="<?= h(base_url('/')) ?>">Tacheto</a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav" aria-controls="nav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="nav">
				<ul class="navbar-nav ms-auto">
					<li class="nav-item"><a class="nav-link" href="<?= h(base_url('/')) ?>">Nyumbani</a></li>
					<?php if(current_user()): ?>
					<li class="nav-item"><a class="nav-link" href="<?= h(base_url('admin')) ?>">Admin</a></li>
					<li class="nav-item">
						<form method="post" action="<?= h(base_url('auth/logout')) ?>">
							<?= csrf_field() ?>
							<button class="btn btn-sm btn-light ms-2" type="submit">Toka</button>
						</form>
					</li>
					<?php else: ?>
					<li class="nav-item"><a class="nav-link" href="<?= h(base_url('auth/login')) ?>">Ingia</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
	<main class="container py-4 fade-in">
		<?= $content ?>
	</main>
	<footer class="py-4 text-center text-muted small">© <?= date('Y') ?> Tacheto</footer>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html> 