<?php
$user = $this->Session->read('Auth');
$isMemberGroup = Configure::read('InstantPage.enableGroup');
if (!empty($user['Admin']
	&& in_array($user['Admin']['user_group_id'], $isMemberGroup)
	&& $this->request->here == '/cmsadmin')) {
		header('Location: '. $this->BcBaser->getUri('/cmsadmin/instant_page/instant_pages/'));
	exit;
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="robots" content="noindex,nofollow" />
	<?php $this->BcBaser->title() ?>
	<meta name="description" content="">
	<meta name="keywords" content="">
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<!-- FAVICON -->
	<link rel="icon" href="/favicon.ico">
	<link rel="shortcut icon" href="/img/common/favicon_180.png">
	<link rel="apple-touch-icon" href="/img/common/favicon_180.png">
	<!-- /FAVICON -->
	<!-- FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
	<!-- /FONTS -->
	<!-- CSS -->
	<?php
	$this->BcBaser->css([
		'../js/admin/vendors/bootstrap-4.1.3/bootstrap.min',
		'admin/style.css',
		'admin/jquery-ui/jquery-ui.min',
		'../js/admin/vendors/jquery.jstree-3.3.8/themes/proton/style.min',
		'../js/admin/vendors/jquery-contextMenu-2.2.0/jquery.contextMenu.min',
		'admin/colorbox/colorbox-1.6.1',
		'admin/jquery.timepicker',
		'InstantPage.admin/mypage'
	])
	?>
	<link rel="stylesheet" href="/my_page/js/lib/scroll-hint/css/scroll-hint.css">
	<link rel="stylesheet" href="/my_page/css/common.css">
	<link rel="stylesheet" href="/my_page/css/import.css">
	<!-- /CSS -->
	<!--[if IE]><?php $this->BcBaser->js(['admin/vendors/excanvas']) ?><![endif]-->
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
//		'admin/libs/adjust_scroll',
		'admin/libs/jquery.bcUtil',
		'admin/libs/jquery.bcToken',
		'admin/sidebar',
		'admin/startup',
		'admin/favorite',
		'admin/permission',
		'admin/vendors/jquery.timepicker'
	]);?>
	<script>
		$.bcUtil.init({
			baseUrl: '<?php echo $this->request->base ?>',
			adminPrefix: '<?php echo BcUtil::getAdminPrefix() ?>',
			frontFullUrl: '<?php echo (!empty($publishLink))? $publishLink : '' ?>'
		});
	</script>
	<?php $this->BcBaser->scripts() ?>
	<?php
	if (isset($this->request->data['InstantPage']['template'])) {
		$this->BcBaser->css(['InstantPage.origin'], ['inline' => true]);
	}
	?>
	<?php $this->BcBaser->googleAnalytics() ?>
</head>

<body id="<?php $this->BcBaser->contentsName(true) ?>" class="normal">
	<div class="bca-data">
		<div id="BaseUrl" style="display: none"><?php echo $this->request->base ?></div>
		<div id="SaveFavoriteBoxUrl" style="display:none"><?php $this->BcBaser->url(['plugin' => '', 'controller' => 'dashboard', 'action' => 'ajax_save_favorite_box']) ?></div>
		<div id="SaveSearchBoxUrl" style="display:none"><?php $this->BcBaser->url(['plugin' => '', 'controller' => 'dashboard', 'action' => 'ajax_save_search_box', $this->BcBaser->getContentsName(true)]) ?></div>
		<div id="SearchBoxOpened" style="display:none"><?php echo $this->Session->read('Baser.searchBoxOpened.' . $this->BcBaser->getContentsName(true)) ?></div>
		<div id="CurrentPageName" style="display: none"><?php echo h($this->BcBaser->getContentsTitle()) ?></div>
		<div id="CurrentPageUrl" style="display: none"><?php echo ($this->request->url == Configure::read('Routing.prefixes.0')) ? '/' . BcUtil::getAdminPrefix() . '/dashboard/index' : '/' . h($this->request->url); ?></div>
		<!-- Waiting -->
		<div id="Waiting" class="waiting-box bca-waiting-box" hidden>
			<div class="corner10">
				<?php echo $this->Html->image('admin/ajax-loader.gif') ?>
			</div>
		</div>
	</div>
<div id="Page" class="bca-app">
	<?php $this->BcBaser->header() ?>

	<!-- MAIN -->
	<main>
		<?php $this->BcBaser->flash() ?>
		<div id="BcMessageBox"><div id="BcSystemMessage" class="notice-message"></div></div>
		<?php echo $this->BcLayout->dispatchContentsHeader() ?>
		<?php $this->BcBaser->content() ?>
		<?php echo $this->BcLayout->dispatchContentsFooter() ?>
	</main>
	<!-- /MAIN -->

	<?php $this->BcBaser->footer() ?>
</div>


	<!-- JS -->
<?php if( $this->request->controller !== 'instant_pages' && $this->request->controller !== 'edit') :?>
	<!-- JS LIBRARY -->
	<script src="/my_page/js/lib/jquery-3.6.0.min.js"></script>
	<!-- /JS LIBRARY -->
<?php endif;?>
	<script src="/my_page/js/lib/scroll-hint/js/scroll-hint.min.js"></script>
	<script src="/my_page/js/common_navigation.js"></script>
	<script src="/my_page/js/common.js"></script>
	<!-- /JS -->
	<?php $this->BcBaser->func() ?>
</body>

</html>
