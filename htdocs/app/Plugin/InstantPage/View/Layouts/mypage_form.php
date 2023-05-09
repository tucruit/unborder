<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<?php //$this->BcBaser->element('google_tag_manger') ?>
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="format-detection" content="telephone=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<?php //$this->BcBaser->element('meta') ?>
	<link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/img/favicons/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/img/favicons/favicon-16x16.png">
	<link rel="shortcut icon" href="/img/favicons/favicon.ico">
	<?php
	$this->BcBaser->css([
		'/js/admin/vendors/bootstrap-4.1.3/bootstrap.min',
		'admin/jquery-ui/jquery-ui.min',
		'/js/admin/vendors/jquery.jstree-3.3.8/themes/proton/style.min',
		'/js/admin/vendors/jquery-contextMenu-2.2.0/jquery.contextMenu.min',
		'admin/colorbox/colorbox-1.6.1',
		'style.css',
		'common',
		'import'
	]);
	?>

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
//			'admin/libs/adjust_scroll',
		'admin/libs/jquery.bcUtil',
		'admin/libs/jquery.bcToken',
		'admin/sidebar',
		'admin/startup',
		'admin/favorite',
		'admin/permission'])
		?>
		<script>
			$.bcUtil.init({
				baseUrl: '<?php echo $this->request->base ?>',
				adminPrefix: '<?php echo BcUtil::getAdminPrefix() ?>',
				frontFullUrl: '<?php echo (!empty($publishLink))? $publishLink : '' ?>'
			});
		</script>
		<?php $this->BcBaser->scripts() ?>
		<style>
		body {
			font-size: 16px!important;
		}
	</style>
	<link rel="stylesheet" href="/assets/css/main.css">
	<?php $this->BcBaser->scripts() ?>
	<?php $this->BcBaser->css('admin/admin_style') ?>
</head>

<body>
	<?php //$this->BcBaser->element('google_tag_manger_body') ?>
	<?php $this->BcBaser->header() ?>
	<!-- <div id="Wrap" class="bca-container"> -->
		<?php
		// ログインしている場合はサイドバーを表示
		$InstantPageUser = $this->Session->read('Auth.InstantPageUser');
		p($InstantPageUser);
		if (strpos($this->request->url, 'login') === false){
			$this->BcBaser->element('sidebar');
		}
		?>
		<main id="Contents">
			<div class="c-mv c-mv--lower c-mv--account"><span class="c-mv__bg"></span><span class="c-mv__img"></span>
				<div class="c-mv__inr">
					<h2 class="c-mv__ttl"><?php $this->BcBaser->contentsTitle() ?></h2>
				</div>
			</div>
			<div class="l-contents l-contents--lower"><span class="l-contents__bg"></span>
				<div class="l-contents__inr">
					<div class="c-section">
						<div class="c-box c-box--a c-box--bg p-account">
							<?php echo $this->BcLayout->dispatchContentsHeader() ?>
							<?php $this->BcBaser->content() ?>
							<?php echo $this->BcLayout->dispatchContentsFooter() ?>
						</div>
					</div>
				</div>
			</div>
			<?php $this->BcBaser->element('contact') ?>
		</main>
		<?php $bcUtilLoginUser = BcUtil::loginUser(); ?>
		<?php if (!empty($bcUtilLoginUser)): ?>
			<?php $this->BcBaser->footer([], ['cache' => ['key' => '_admin_footer']]) ?>
		<?php else: ?>
			<?php $this->BcBaser->footer() ?>
		<?php endif ?>
		<script src="/js/main.js"></script>
		<script src="/assets/js/func.js"></script>
	<!-- </div> -->
	<?php $this->BcBaser->func() ?>
</body>

</html>
