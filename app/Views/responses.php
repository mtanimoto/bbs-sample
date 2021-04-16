<h1 class="title"><?= $threadTitle; ?></h1>

<a href="<?= "/{$boardId}" ?>">スレット一覧に戻る</a>

<hr/>

<dl>
	<?php foreach ($rows as $row): ?>
	<dt class="flex">
		<div class="flex-start">
			<label><?= $row['id']; ?></label>
		</div>
		<div class="flex-end is-size-7">
			<?php if ($row['email']): ?>
				<a href="mailto:<?= $row['email']; ?>"><?= $row['name']; ?></a>
			<?php else: ?>
				<label><?= $row['name']; ?></label>
			<?php endif; ?>
			<label><?= $row['posted_at']; ?></label>
		</div>
	</dt>
	<dd class="content">
		<?php if (!empty($row['blob_path'])): ?>
		<section>
			<img src="<?= $row['blob_path'] ?>" style="max-width: 200px; height: auto;"/>
		</section>
		<?php endif; ?>
		<section>
			 <?= str_replace("\n", "<br/>", esc($row['comment'])); ?>
		</section>
	</dd>
	<hr/>
	<?php endforeach; ?>
	
</dl>

<?= helper('form'); ?>
<?= form_button('onModal', '書き込み', ['class' => 'button is-info mb-2', 'onclick' => 'modal.open();']); ?>

<div id="modal" class="modal">
  <div class="modal-background" onclick="modal.close()"></div>
  <div class="modal-card">
    <header class="modal-card-head">
      <p class="modal-card-title">書き込み</p>
    </header>
	<?= form_open("/bbs/write.cgi", ['name' => 'form', 'enctype' => "multipart/form-data"]); ?>
		<section class="modal-card-body">
			<dl>
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
					<?= form_textarea('comment', '', ['class' => 'textarea onFocus', 'required' => 'required']); ?>
				</dd>
				<dt>添付画像</dt>
				<dd>
					<div id="file-js-example" class="file has-name">
						<label class="file-label">
							<input class="file-input" type="file" name="resume" accept="image/*">
							<span class="file-cta">
							<span class="file-icon">
								<i class="fas fa-upload"></i>
							</span>
							<span class="file-label">
								ファイル選択
							</span>
							</span>
							<span class="file-name">
								No file uploaded
							</span>
						</label>
					</div>
				</dd>
				<?= form_hidden('bbs', $boardId); ?>
				<?= form_hidden('key', $threadId); ?>
				<label class="checkbox">
					<?= form_checkbox('remember'); ?>
					名前とメールアドレスを記憶する
				</label>
			</dl>
		</section>
		<footer class="modal-card-foot">
			<?= form_submit('write', '書き込む', ['class' => 'button is-info']); ?>
			<?= form_button('cancel', '閉じる', ['class' => 'button', 'onclick' => 'modal.close()']); ?>
		</footer>
	<?= form_close(); ?>
	</div>
</div>

<script>
	document.addEventListener("submit", function() {
		let remember = document.getElementsByName('remember')[0];
		let boardId = document.getElementsByName('bbs')[0].value;
		if (remember.checked) {
			localStorage.setItem(boardId + '-name', document.getElementsByName('name')[0].value);
			localStorage.setItem(boardId + '-email', document.getElementsByName('email')[0].value);
		} else {
			localStorage.removeItem(boardId + '-name');
			localStorage.removeItem(boardId + '-email');
		}
	});

	document.addEventListener('DOMContentLoaded', function() {
		let boardId = document.getElementsByName('bbs')[0].value;
		if (localStorage.getItem(boardId + '-name') || localStorage.getItem(boardId + '-email')) {
			document.getElementsByName('remember')[0].checked = true;
			document.getElementsByName('name')[0].value = localStorage.getItem(boardId + '-name');
			document.getElementsByName('email')[0].value = localStorage.getItem(boardId + '-email');
		} else {
			document.getElementsByName('remember')[0].checked = false;
		}
	});

	const fileInput = document.querySelector('#file-js-example input[type=file]');
	fileInput.onchange = () => {
		if (fileInput.files.length > 0) {
		const fileName = document.querySelector('#file-js-example .file-name');
		fileName.textContent = fileInput.files[0].name;
		}
	}
</script>
