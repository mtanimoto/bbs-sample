<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<title><?= $title; ?></title>
	<meta name="description" content="2021年上期の個人目標のために作った掲示板">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="robots" content="noindex,nofollow">
	<link rel="stylesheet" href="/styles/common.css">
	<link rel="stylesheet" href="/styles/bulma.min.css">
	<link rel="stylesheet" href="/styles/fontawesome.all.min.css">
	<script src="/scripts/common.js" defer></script>
	<script src="/scripts/fontawesome.all.js" defer></script>
</head>
<body>
	<?= $this->include('nav'); ?>
	<section class="section">
		<div class="container">
			<?= $this->include($section); ?>
		</div>
	</section>
    <?= $this->include('footer'); ?>
</body>
</html>
