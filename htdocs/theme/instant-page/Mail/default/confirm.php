<?php
/**
 * メールフォーム確認ページ
 */
$this->BcBaser->css('admin/jquery-ui/jquery-ui.min', array('inline' => true));
$this->BcBaser->js(array('admin/vendors/jquery-ui-1.11.4.min', 'admin/vendors/i18n/ui.datepicker-ja'), false);
if ($freezed) {
	$this->Mailform->freeze();
}
?>

<section>
	<div class="c-page-sub__content-detail">
		<div class="c-content-main">
			<div class="cc-form-description">
				<?php if ($freezed): ?>
				<p><?php echo __('入力した内容に間違いがなければ「送信する」ボタンをクリックしてください。') ?></p>
				<?php else: ?>
				<?php $this->Mail->description() ?>
				<?php endif; ?>
			</div>

			<?php $this->BcBaser->flash() ?>
			<?php $this->BcBaser->element('mail_form') ?>

		</div>
	</div>
</section>