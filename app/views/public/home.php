<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h3 m-0">Matokeo</h1>
	<form class="d-flex gap-2" method="get" action="<?= h(base_url('/')) ?>">
		<input class="form-control" style="min-width:260px" type="text" name="q" value="<?= h($search) ?>" placeholder="Tafuta (Kidato, mwaka, kichwa)">
		<button class="btn btn-primary" type="submit">Tafuta</button>
	</form>
</div>
<div class="card p-0">
	<div class="table-responsive">
		<table class="table align-middle mb-0">
			<thead><tr><th>Kidato</th><th>Mwaka</th><th>Kichwa</th><th></th></tr></thead>
			<tbody>
				<?php if(!$batches): ?>
				<tr><td colspan="4" class="text-muted">Hakuna matokeo kwa sasa.</td></tr>
				<?php endif; ?>
				<?php foreach($batches as $b): ?>
				<tr>
					<td><?= h($b['form_level']) ?></td>
					<td><?= h($b['year']) ?></td>
					<td><?= h($b['title']) ?></td>
					<td class="text-end"><a class="btn btn-sm btn-primary" href="<?= h(base_url('batch? id='.(int)$b['id'])) ?>">Tazama</a></td>
				</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div> 