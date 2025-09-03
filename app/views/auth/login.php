<div class="row justify-content-center">
	<div class="col-md-5">
		<div class="card p-4">
			<h1 class="h4 mb-3">Ingia</h1>
			<?php if(!empty($_SESSION['error'])): ?><div class="alert alert-danger py-2"><?= h($_SESSION['error']); unset($_SESSION['error']); ?></div><?php endif; ?>
			<form method="post" action="<?= h(base_url('auth/login')) ?>" class="vstack gap-3">
				<?= csrf_field() ?>
				<div>
					<label class="form-label">Barua pepe</label>
					<input class="form-control" type="email" name="email" required>
				</div>
				<div>
					<label class="form-label">Neno siri</label>
					<input class="form-control" type="password" name="password" required>
				</div>
				<button class="btn btn-primary" type="submit">Ingia</button>
			</form>
		</div>
	</div>
</div> 