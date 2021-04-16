<div class="card">
  <div class="card-content">
    <div class="content">
      管理者用の設定画面</br>
      ホントは色々設定できるといいんだけど、たいへんなので実装しない。
    </div>
  </div>
</div>

<br/>

<?= helper('form'); ?>
<?= form_open("/" . $board['id'] . "/admin", ['name' => 'form']); ?>
  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">twitter id</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <?= form_input('screen_name', $user['screen_name'], ['class' => 'input is-static', 'readonly' => 'readonly']); ?>
        </p>
      </div>
    </div>
  </div>

  <br/>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">掲示板のタイトル</label>
      <span class="tag is-danger">必須</span>
      <span class="tag is-info">最大128文字</span>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <?= form_input('title', $board['title'], ['class' => 'input', 'autofocus' => 'autofocus', 'required' => 'required']); ?>
        </p>
      </div>
    </div>
  </div>

  <br/>
  
  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">名前未入力時の名前</label>
      <span class="tag is-info">最大128文字</span>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <?= form_input('name', $board['name'] ?? '', ['class' => 'input', 'placeholder' => DEFAULT_NAME, 'maxlength' => '128']); ?>
        </p>
      </div>
    </div>
  </div>

  <br/>

  <div class="field is-horizontal">
    <div class="field-label is-normal">
      <label class="label">掲示板の説明</label>
    </div>
    <div class="field-body">
      <div class="field">
        <p class="control">
          <?= form_textarea('description', $board['description'] ?? '', ['class' => 'textarea']); ?>
        </p>
        <p></p>
      </div>
    </div>
  </div>

  <section class="section">
    <div class="buttons">
      <?= form_submit('submit', '設定', ['class' => 'button is-info']); ?>
    </div>
  </section>
<?= form_close(); ?>
