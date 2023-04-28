<?php $this->BcBaser->docType('html5') ?>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="robots" content="noindex,nofollow" />
	<?php $this->BcBaser->title() ?>
	<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no">
 <!-- FAVICON -->
  <link rel="icon" href="favicon.ico">
  <link rel="shortcut icon" href="img/common/favicon_180.png">
  <link rel="apple-touch-icon" href="img/common/favicon_180.png">
  <!-- /FAVICON -->
  <!-- FONTS -->
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
  <!-- /FONTS -->
	<?php
	$this->BcBaser->css([
		'../js/admin/vendors/bootstrap-4.1.3/bootstrap.min',
		'admin/style.css',
		'admin/jquery-ui/jquery-ui.min',
		'../js/admin/vendors/jquery.jstree-3.3.8/themes/proton/style.min',
		'../js/admin/vendors/jquery-contextMenu-2.2.0/jquery.contextMenu.min',
		'admin/colorbox/colorbox-1.6.1',
		'admin/jquery.timepicker',
		'common',
		'import'
	])
	?>
	<!--[if IE]><?php //$this->BcBaser->js(['admin/vendors/excanvas']) ?><![endif]-->
	<?php
	echo $this->BcBaser->declarationI18n();
	echo $this->BcBaser->i18nScript([
		'commonCancel'                  => __d('baser', 'キャンセル'),
		'commonSave'                    => __d('baser', '保存'),
		'commonExecCompletedMessage'    => __d('baser', '処理が完了しました。'),
		'commonSaveFailedMessage'       => __d('baser', '保存に失敗しました。'),
		'commonExecFailedMessage'       => __d('baser', '処理に失敗しました。'),
		'commonBatchExecFailedMessage'  => __d('baser', '一括処理に失敗しました。'),
		'commonGetDataFailedMessage'    => __d('baser', 'データ取得に失敗しました。'),
		'commonSortSaveFailedMessage'   => __d('baser', '並び替えの保存に失敗しました。'),
		'commonSortSaveConfirmMessage'	=> __d('baser', 'コンテンツを移動します。よろしいですか？'),
		'commonNotFoundProgramMessage'  => __d('baser', '送信先のプログラムが見つかりません。'),
		'commonSelectDataFailedMessage' => __d('baser', 'データが選択されていません。'),
		'commonConfirmDeleteMessage'    => __d('baser', '本当に削除してもよろしいですか？'),
		'commonConfirmHardDeleteMessage'=> __d('baser', "このデータを本当に削除してもよろしいですか？\n※ 削除したデータは元に戻すことができません。"),
		'commonPublishFailedMessage'    => __d('baser', '公開処理に失敗しました。'),
		'commonChangePublishFailedMessage'=> __d('baser', '公開状態の変更に失敗しました。'),
		'commonUnpublishFailedMessage'  => __d('baser', '非公開処理に失敗しました。'),
		'commonCopyFailedMessage'       => __d('baser', 'コピーに失敗しました。'),
		'commonDeleteFailedMessage'     => __d('baser', '削除に失敗しました。'),
		'batchListConfirmDeleteMessage' => __d('baser', "選択したデータを全て削除します。よろしいですか？\n※ 削除したデータは元に戻すことができません。"),
		'batchListConfirmPublishMessage'=> __d('baser', '選択したデータを全て公開状態に変更します。よろしいですか？'),
		'batchListConfirmUnpublishMessage'=> __d('baser', '選択したデータを全て非公開状態に変更します。よろしいですか？'),
		'bcConfirmTitle1'               => __d('baser', 'ダイアログ'),
		'bcConfirmAlertMessage1'        => __d('baser', 'メッセージを指定してください。'),
		'bcConfirmAlertMessage2'        => __d('baser', 'コールバック処理が登録されていません。'),
		'favoriteTitle1'                => __d('baser', 'よく使う項目登録'),
		'favoriteTitle2'                => __d('baser', 'よく使う項目編集'),
		'favoriteAlertMessage1'         => __d('baser', '並び替えの保存に失敗しました。'),
		'favoriteAlertMessage2'         => __d('baser', 'よく使う項目の追加に失敗しました。'),
	], ['inline' => true]);
	?>
	<?php
	$this->BcBaser->js([
		'admin/vue.min',
		'admin/vendors/jquery-2.1.4.min',
		'admin/vendors/jquery-ui-1.11.4.min',
		'admin/vendors/i18n/ui.datepicker-ja',
		'admin/vendors/jquery.bt.min',
		'admin/vendors/jquery-contextMenu-2.2.0/jquery.contextMenu.min',
		'admin/vendors/jquery.form-2.94',
		'admin/vendors/jquery.validate.min',
		'admin/vendors/jquery.colorbox-1.6.1.min',
		'admin/vendors/bootstrap-4.1.3/bootstrap.bundle.min',
		'admin/libs/jquery.baseUrl',
		'admin/libs/jquery.bcConfirm',
		'admin/libs/credit',
		'admin/vendors/validate_messages_ja',
		'admin/functions',
		'admin/libs/jquery.bcUtil',
		'admin/libs/jquery.bcToken',
		'admin/sidebar',
		'admin/startup',
		'admin/favorite',
		'admin/permission',
		'admin/vendors/jquery.timepicker',
		'common_navigation',
		'common'
	])
	?>
	<script>
		$.bcUtil.init({
			baseUrl: '<?php echo $this->request->base ?>',
			adminPrefix: '<?php echo BcUtil::getAdminPrefix() ?>',
			frontFullUrl: '<?php echo (!empty($publishLink))? $publishLink : '' ?>'
		});
	</script>
	<!-- SHARE -->
	<meta name="twitter:card" content="summary">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="ja_JP">
	<meta property="og:image" content="/img/common/ogp_logo.png">
	<meta property="og:url" content="">
	<meta property="og:title" content="ランディングページ制作支援ツール インスタントページ">
	<meta property="og:description" content="[ページディスクリプション]">
	<meta property="og:site_name" content="ランディングページ制作支援ツール インスタントページ">
	<!-- /SHARE -->
	<?php $this->BcBaser->scripts() ?>
</head>

<body>
	<?php $this->BcBaser->header() ?>
		<main id="Contents">

			<div role="main">
				<!-- BREAD CRUMBS -->
				<div class="sub-breadcrumbs">
					<div class="l-subContentsContainer sub-breadcrumbsInner">
						<ol class="sub-breadcrumbs-list">
							<li><a href="/">トップページ</a></li>
							<li><?php h($this->BcBaser->contentsTitle()) ?></li>
						</ol>
					</div>
				</div>
				<!-- /BREAD CRUMBS -->
				<!-- SUB H1 -->
				<div class="sub-h1">
					<div class="l-subContentsContainer sub-h1Inner">
						<h1 class="sub-h1-hl"><?php h($this->BcBaser->contentsTitle()) ?></h1>
					</div>
				</div>
				<!-- /SUB H1 -->

				<!-- PAGE CONTENTS -->
				<div class="users usersLogin">
					<?php $this->BcBaser->flash() ?>
					<div id="BcMessageBox"><div id="BcSystemMessage" class="notice-message"></div></div>
					<?php echo $this->BcLayout->dispatchContentsHeader() ?>
					<?php $this->BcBaser->content() ?>
					<?php echo $this->BcLayout->dispatchContentsFooter() ?>
				</div>
				<!-- /PAGE CONTENTS -->
			</div>

			<?php $this->BcBaser->element('contact') ?>
		</main>
	<?php $bcUtilLoginUser = BcUtil::loginUser(); ?>
	<?php if (!empty($bcUtilLoginUser)): ?>
		<?php $this->BcBaser->footer([], ['cache' => ['key' => '_admin_footer']]) ?>
	<?php else: ?>
		<?php $this->BcBaser->footer() ?>
	<?php endif ?>
	<?php $this->BcBaser->func() ?>
</body>

</html>
