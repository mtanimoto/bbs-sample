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

<?= form_open("/signup", ['class' => 'box']); ?>
    <div>
        <label class="is-size-5">新規登録</label>
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
                        'pattern' => '^[a-zA-Z0-9_]+$',
                        'autofocus' => 'autofocus',
                    ]
                );
            ?>
        </div>
    </div>
    <section class="section">
        <ul style="list-style: initial;">
            <li>掲示板管理のためにTwitterIDによるログインを必須としています。</li>
            <li>BBSIDには半角英数字とアンダースコア(_)が使用できます。</li>
        </ul>
    </section>
    <?= form_submit('siguup', 'TwitterIDで掲示板を作成', ['class' => 'button is-info']); ?>
<?= form_close(); ?>