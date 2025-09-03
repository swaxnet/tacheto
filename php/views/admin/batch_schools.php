<h1><?= h($title) ?></h1>
<form method="post" action="/admin/batches/<?= (int)$batch['id'] ?>/schools" enctype="multipart/form-data" class="card" style="max-width:520px">
	<label>Shule</label>
	<select name="school_id" required>
		<?php foreach ($schools as $s): ?>
			<option value="<?= (int)$s['id'] ?>"><?= h($s['name']) ?></option>
		<?php endforeach; ?>
	</select>
	<label>PDF ya Shule</label>
	<input type="file" name="schoolPdf" accept="application/pdf" required>
	<button class="btn mt" type="submit">Pakia</button>
</form>

<div class="table-responsive">
	<table>
		<thead>
			<tr><th>Shule</th><th>Hatua</th></tr>
		</thead>
		<tbody>
			<?php if (!$uploads || count($uploads) === 0): ?>
				<tr><td colspan="2">Hakuna PDFs za shule.</td></tr>
			<?php endif; ?>
			<?php foreach ($uploads as $u): ?>
				<tr>
					<td><?= h($u['school_name']) ?></td>
					<td>
						<a class="btn btn-sm" href="/school-result/<?= (int)$u['id'] ?>/view" target="_blank">Fungua</a>
						<form method="post" action="/admin/batches/<?= (int)$batch['id'] ?>/schools/<?= (int)$u['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Futa?')">
							<button class="btn btn-sm btn-secondary" type="submit">Futa</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div> 