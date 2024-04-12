<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<?php $this->BcBaser->title(); ?>
	<!-- keywords -->
	<?php if (!empty($this->BcBaser->getKeywords())) : ?>
	<meta name="keywords" content="<?php $this->BcBaser->contentsTitle() ?>,<?php echo $this->BcBaser->getKeywords() ?>">
	<?php else : ?>
		<meta name="keywords" content="">
	<?php endif ?>
	<!-- /keywords -->
	<!-- description -->
	<?php $descriptionTxt = $this->BcBaser->getContentsTitle() . '｜' . $this->BcBaserCustom->getDescription_new(); ?>
	<meta name="description" content="<?php echo $descriptionTxt ?>">
	<!-- /description -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<!-- FAVICON -->
	<link rel="icon" href="<?php $this->BcBaser->themeUrl(); ?>favicon.ico">
	<link rel="shortcut icon" href="<?php $this->BcBaser->themeUrl(); ?>img/common/favicon_180.png">
	<link rel="apple-touch-icon" href="<?php $this->BcBaser->themeUrl(); ?>img/common/favicon_180.png">
	<!-- /FAVICON -->
	<!-- FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
	<!-- /FONTS -->
	<!-- CSS -->
	<link rel="stylesheet" href="<?php $this->BcBaser->themeUrl(); ?>css/common.css">
	<link rel="stylesheet" href="<?php $this->BcBaser->themeUrl(); ?>css/import.css">
	<!-- /CSS -->
	<!-- JS LIBRARY -->
	<script src="<?php $this->BcBaser->themeUrl(); ?>js/lib/jquery-3.6.0.min.js"></script>
	<!-- /JS LIBRARY -->
	<!-- SHARE -->
	<meta name="twitter:card" content="summary">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="ja_JP">
	<?php
	$themaUrl = $this->BcBaser->getThemeUrl();
	$themaFullUrl = $this->BcBaser->getUri($themaUrl, false);
	?>
	<meta property="og:image" content="<?php echo $themaFullUrl; ?>img/common/ogp_logo.png">
	<meta property="og:url" content="<?php $this->BcBaser->url(null, true, false); ?>">
	<meta property="og:title" content="<?php echo $this->BcBaser->getTitle(); ?>">
	<meta property="og:description" content="<?php echo $descriptionTxt; ?>">
	<meta property="og:site_name" content="<?php echo $this->BcBaser->getSiteName(); ?>">
	<!-- /SHARE -->
	<?php $this->BcBaser->scripts() ?>

	<?php
	$this->BcBaser->css(['InstantPage.origin'], ['inline' => true]);
	// テンプレートリスト取得
	if (isset($data['InstantPage']['template']) && $data['InstantPage']['template']) {
		$InstantpageTemplateModel = ClassRegistry::init('InstantPage.InstantpageTemplate');
		$template = $InstantpageTemplateModel->find('list',['fields' => ['id', 'name']]);
		if ( array_key_exists($data['InstantPage']['template'], $template) ) {
			$this->BcBaser->css([
				'/theme/'. $template[$data['InstantPage']['template']]. '/css/bge_style'
			], ['inline' => true]);
		}
	}
	?>
	<?php $this->BcBaser->googleAnalytics() ?>
</head>

<body>
	<?php $this->BcBaser->flash() ?>
	<?php $this->BcBaser->content() ?>
	<?php $this->BcBaser->func() ?>
</body>
<style>
	/* 管理画面側で非表示になっている独自タグをフロント側で表示させる */
	.instantTag{
		display: block !important
	}
</style>
</html>
