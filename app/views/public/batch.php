<div class="d-flex justify-content-between align-items-center mb-3">
	<h1 class="h4 m-0"><?= h($batch['title']) ?> - <?= h($batch['form_level']) ?> (<?= h($batch['year']) ?>)</h1>
	<div>
		<?php if(!empty($batch['summary_pdf_path'])): ?>
		<a class="btn btn-sm btn-primary" href="<?= h(base_url('batch/view?id='.(int)$batch['id'])) ?>">Fungua Muhtasari (Ndani)</a>
		<a class="btn btn-sm btn-outline-light" download href="<?= h(base_url($batch['summary_pdf_path'])) ?>">Pakua PDF</a>
		<?php endif; ?>
	</div>
</div>
<div class="card p-0">
	<div class="table-responsive">
		<table class="table align-middle mb-0">
			<thead><tr><th>Shule</th><th>Kodi</th><th></th></tr></thead>
			<tbody>
			<?php if(!$schools): ?><tr><td colspan="3" class="text-muted">Hakuna matokeo ya shule.</td></tr><?php endif; ?>
			<?php foreach($schools as $r): ?>
			<tr>
				<td><?= h($r['school_name']) ?></td>
				<td><?= h($r['school_code']) ?></td>
				<td class="text-end">
					<a class="btn btn-sm btn-primary" href="<?= h(base_url('batch/view?id='.(int)$batch['id'].'&school='.(int)$r['id'])) ?>">Fungua</a>
					<a class="btn btn-sm btn-outline-light" download href="<?= h(base_url($r['pdf_path'])) ?>">Pakua</a>
				</td>
			</tr>
			<?php endforeach; ?>
			</tbody>
		</table>
	</div>
</div> 