<?= helper('form'); ?>
<?php if ($errorMessage): ?>
<article class="message is-danger">
  <div class="message-header">
    <p>エラー</p>
  </div>
  <div class="message-body"><?= $errorMessage; ?> </div>
</article>
<?php endif; ?>

<?php
    $class = 'input';
    $class .= $errorMessage ? ' is-danger' : '';
?>

<?= form_open("/login", ['class' => 'box']); ?>
    <div>
        <label class="is-size-5">掲示板管理画面にログイン</label>
    </div>

    <hr/>

    <div class="field">
        <label class="label">BBSID / 掲示板ID</label>
        <div class="control">
            <?=
                form_input(
                    'board_id',
                    '',
                    [
                        'placeholder' => 'foobar',
                        'maxlength' => 128,
                        'class' => $class,
                        'required' => 'required',
                        'pattern' => '^[a-zA-Z0-9_].+$',
                        'autofocus' => 'autofocus'
                    ]
                );
            ?>
        </div>
    </div>
    <?= form_submit('login', 'TwitterIDでログイン', ['class' => 'button is-info']); ?>
<?= form_close(); ?>