<?php foreach ($rows as $row): ?>
<div class="card mb-3 is-clickable .boards" onclick="window.location = '/' + '<?= $row['id']; ?>'">
	<div class="card-content">
		<p class="is-size-3 mb-3 has-text-link">
			<?= esc($row['title']); ?>
		</p>
		<p class="board_description">
			<?= esc($row['description']); ?>
		</p>
	</div>
</div>
<?php endforeach; ?>

<style>
.boards:hover {
	opacity: 0.8;
}
</style>