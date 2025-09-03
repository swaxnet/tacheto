<!doctype html>
<html lang="sw">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title><?= isset($title) ? h($title) : 'Tacheto' ?></title>
	<link rel="stylesheet" href="/css/styles.css">
</head>
<body>
	<div class="container">
		<header class="header">
			<div><a href="/">Tacheto</a></div>
			<nav class="nav">
				<a href="/">Nyumbani</a>
				<?php if (current_user()) : ?>
					<a href="/admin">Admin</a>
					<form action="/auth/logout" method="post" style="display:inline"><button class="btn btn-sm" type="submit">Toka</button></form>
				<?php else : ?>
					<a href="/auth/login">Ingia</a>
				<?php endif; ?>
			</nav>
		</header>

		<?php if ($m = get_flash('error')): ?><div class="alert alert-error"><?= h($m) ?></div><?php endif; ?>
		<?php if ($m = get_flash('success')): ?><div class="alert alert-success"><?= h($m) ?></div><?php endif; ?>

		<main class="card">
			<?= $content ?>
		</main>

		<footer class="footer">© <?= date('Y') ?> Tacheto</footer>
	</div>
</body>
</html> 