<?php $this->BcBaser->docType('html5') ?>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<?php $this->BcBaser->title() ?>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<!-- FAVICON -->
	<link rel="icon" href="favicon.ico">
	<link rel="shortcut icon" href="/img/common/favicon_180.png">
	<link rel="apple-touch-icon" href="/img/common/favicon_180.png">
	<!-- /FAVICON -->
	<!-- FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
	<!-- /FONTS -->
	<!-- CSS -->
	<link rel="stylesheet" href="/css/common.css">
	<link rel="stylesheet" href="/css/import.css">
	<!-- /CSS -->
	<!-- JS LIBRARY -->
	<script src="/js/lib/jquery-3.6.0.min.js"></script>
	<!-- /JS LIBRARY -->
	<!-- SHARE -->
	<meta name="twitter:card" content="summary">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="ja_JP">
	<meta property="og:image" content="img/common/ogp_logo.png">
	<meta property="og:url" content="[ページURL]">
	<meta property="og:title" content="[ページ名]｜ランディングページ制作支援ツール インスタントページ">
	<meta property="og:description" content="[ページディスクリプション]">
	<meta property="og:site_name" content="ランディングページ制作支援ツール インスタントページ">
	<!-- /SHARE -->
	<?php $this->BcBaser->scripts(); ?>
</head>

<body>

	<?php $this->BcBaser->header();?>

	<!-- MAIN -->
	<main>
		<div role="main" class="top">
			<?php $this->BcBaser->crumbsList(); ?>
			<!-- SUB H1 -->
			<div class="sub-h1">
				<div class="l-subContentsContainer sub-h1Inner">
					<h1 class="sub-h1-hl"><?php $this->BcBaser->contentsTitle(); ?></h1>
				</div>
			</div>
			<!-- /SUB H1 -->
			<!-- //コンテンツ -->
			<?php $this->BcBaser->flash() ?>
			<?php $this->BcBaser->content() ?>
		</div>
		<?php $this->BcBaser->element('contact') ?>
	</main>
	<!-- /MAIN -->

	<?php $this->BcBaser->footer();?>
	<!-- JS -->
	<script src="/js/common_navigation.js"></script>
	<script src="/js/common.js"></script>
	<!-- /JS -->
	<?php $this->BcBaser->func(); ?>
</body>

</html>
