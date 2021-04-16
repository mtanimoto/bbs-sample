<h1 class="title"><?= esc($board['title']); ?></h1>
<p class="subtitle"><?= str_replace("\n", "<br/>", esc($board['description'])); ?></p>

<a href="/boards">板一覧に戻る</a>

<hr/>

<?= helper('form'); ?>
<?= form_button('onModal', '新規スレッド作成', ['class' => 'button is-info mb-2', 'onclick' => 'modal.open();']); ?>

<?php $boardId = $board['id']; ?>
<?php foreach ($rows as $row): ?>
<div class="card mb-2 is-clickable thread" onclick="window.location = '<?= "/bbs/read.cgi/{$boardId}/{$row['id']}"; ?>'">
	<div class="card-content has-text-link">
		<dl>
			<dt class="flex">
				<div class="flex-start">
					<?= esc($row['title']); ?>
				</div>
				<div class="tag is-rounded is-primary flex-end">
					<?= $row['response_id']; ?>
				</div>
			</dt>
		</dl>
	</div>
</div>
<?php endforeach; ?>

<div id="modal" class="modal">
  <div class="modal-background" onclick="modal.close()"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">新規スレッド作成</p>
    </header>
	<?= form_open("/bbs/write.cgi/new", ['name' => 'form']); ?>
		<section class="modal-card-body">
			<dl>
				<dt>タイトル</dt>
				<dd>
					<?= form_input('title', '', ['class' => 'input onFocus', 'required' => 'required']); ?>
				</dd>
				<dt>名前</dt>
				<dd>
					<?= form_input('name', empty($board['name']) ? DEFAULT_NAME : $board['name'], ['class' => 'input']); ?>
				</dd>
				<dt>メールアドレス</dt>
				<dd>
					<?= form_input('email', 'sage', ['class' => 'input']); ?>
				</dd>
				<dt>コメント</dt>
				<dd>
					<?= form_textarea('comment', '', ['class' => 'textarea', 'required' => 'required']); ?>
				</dd>
				<?= form_hidden('bbs', $boardId); ?>
			</dl>
		</section>
		<footer class="modal-card-foot">
			<?= form_submit('write', '書き込む', ['class' => 'button is-info']); ?>
			<?= form_button('cancel', '閉じる', ['class' => 'button', 'onclick' => 'modal.close()']); ?>
		</footer>
	<?= form_close(); ?>
  </div>
</div>

<style>
.thread:hover {
	opacity: 0.8;
}
</style>