<h1>Makundi ya Matokeo</h1>
<form method="post" action="/admin/batches" enctype="multipart/form-data" class="card">
	<label>Kidato</label>
	<input type="text" name="form_level" placeholder="Mfano: Kidato cha Nne" required>
	<label>Mwaka</label>
	<input type="text" name="year" placeholder="2024" required>
	<label>Kichwa</label>
	<input type="text" name="title" placeholder="Mfano: Matokeo ya Mkoa X" required>
	<label>Muhtasari (PDF - hiari)</label>
	<input type="file" name="summaryPdf" accept="application/pdf">
	<button class="btn mt" type="submit">Hifadhi</button>
</form>

<div class="table-responsive">
	<table>
		<thead>
			<tr><th>Kidato</th><th>Mwaka</th><th>Kichwa</th><th>Hatua</th></tr>
		</thead>
		<tbody>
			<?php if (!$batches || count($batches) === 0): ?>
				<tr><td colspan="4">Hakuna makundi.</td></tr>
			<?php endif; ?>
			<?php foreach ($batches as $b): ?>
				<tr>
					<td><?= h($b['form_level']) ?></td>
					<td><?= h($b['year']) ?></td>
					<td><?= h($b['title']) ?></td>
					<td>
						<a class="btn btn-sm" href="/admin/batches/<?= (int)$b['id'] ?>/schools">Shule</a>
						<form method="post" action="/admin/batches/<?= (int)$b['id'] ?>/delete" style="display:inline" onsubmit="return confirm('Futa?')">
							<button class="btn btn-sm btn-secondary" type="submit">Futa</button>
						</form>
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div> 