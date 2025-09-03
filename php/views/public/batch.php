<div class="card-header">
	<h1><?= h($batch['title']) ?> - <?= h($batch['form_level']) ?> (<?= h($batch['year']) ?>)</h1>
	<div class="actions">
		<?php if (!empty($batch['summary_pdf_path'])): ?>
			<a class="btn" href="/batch/<?= (int)$batch['id'] ?>/summary">Fungua Matokeo ya Jumla (Ndani ya Tovuti)</a>
			<a class="btn btn-secondary" download href="/<?= h($batch['summary_pdf_path']) ?>">Pakua Matokeo ya Jumla (PDF)</a>
		<?php else: ?>
			<span class="muted">Hakuna PDF ya matokeo ya jumla iliyopakiwa.</span>
		<?php endif; ?>
	</div>
</div>

<h2 class="mt">Matokeo kwa Shule</h2>
<form class="search" method="get" action="">
	<input type="text" name="q" value="<?= h($search) ?>" placeholder="Tafuta shule...">
	<button class="btn" type="submit">Tafuta</button>
</form>
<div class="table-responsive">
	<table>
		<thead>
			<tr>
				<th>Shule</th>
				<th>Kodi</th>
				<th>Hatua</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!$schools || count($schools) === 0): ?>
				<tr><td colspan="3">Hakuna matokeo ya shule.</td></tr>
			<?php endif; ?>
			<?php foreach ($schools as $r): ?>
				<tr>
					<td><?= h($r['school_name']) ?></td>
					<td><?= h($r['school_code']) ?></td>
					<td><div class="actions">
						<a class="btn btn-sm" href="/school-result/<?= (int)$r['id'] ?>/view">Fungua (Ndani)</a>
						<a class="btn btn-sm btn-secondary" download href="/<?= h($r['pdf_path']) ?>">Pakua</a>
					</div></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div> 