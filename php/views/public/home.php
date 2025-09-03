<div class="card-header">
	<h1>Matokeo</h1>
	<form class="search" method="get" action="/">
		<input type="text" name="q" value="<?= h($search) ?>" placeholder="Tafuta (mfano: Kidato, mwaka, kichwa)">
		<button class="btn" type="submit">Tafuta</button>
	</form>
</div>
<div class="table-responsive">
	<table>
		<thead>
			<tr>
				<th>Kidato</th>
				<th>Mwaka</th>
				<th>Kichwa</th>
				<th>Hatua</th>
			</tr>
		</thead>
		<tbody>
			<?php if (!$batches || count($batches) === 0): ?>
				<tr><td colspan="4">Hakuna matokeo kwa sasa.</td></tr>
			<?php endif; ?>
			<?php foreach ($batches as $b): ?>
				<tr>
					<td><?= h($b['form_level']) ?></td>
					<td><?= h($b['year']) ?></td>
					<td><?= h($b['title']) ?></td>
					<td><a class="btn btn-sm" href="/batch/<?= (int)$b['id'] ?>">Tazama</a></td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div> 