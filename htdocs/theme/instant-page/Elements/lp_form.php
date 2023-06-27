<?php
/**
 * [PUBLISH] LP用 メールフォーム
 */
// ブラウザのヒストリーバック（戻るボタン）対応
$this->Mail->token();
$this->BcBaser->js('mail/form-submit', true, ['defer'])
?>

<?php if (!$freezed) : ?>
	<?php echo $this->Mailform->create('MailMessage', ['url' => $this->BcBaser->getContentsUrl(null, false, null, false) . 'confirm', 'type' => 'file']) ?>
<?php else : ?>
	<?php echo $this->Mailform->create('MailMessage', ['url' => $this->BcBaser->getContentsUrl(null, false, null, false)  . 'submit']) ?>
<?php endif; ?>

<?php $this->Mailform->unlockField('MailMessage.mode') ?>
<?php echo $this->Mailform->hidden('MailMessage.mode') ?>

<table class="mod-table-form contact-form-table">
	<?php $this->BcBaser->element('mail_input', ['blockStart' => 1]) ?>
</table>

<?php if ($mailContent['MailContent']['auth_captcha']) : ?>
	<?php if (!$freezed) : ?>
		<div class="bs-mail-form-auth-captcha">
			<div><?php echo $this->Mailform->authCaptcha('MailMessage.auth_captcha') ?></div>
			<div><?php echo __('画像の文字を入力してください') ?></div>
			<?php echo $this->Mailform->error('MailMessage.auth_captcha', __('入力された文字が間違っています。入力をやり直してください。')) ?>
		</div>
	<?php else : ?>
		<?php echo $this->Mailform->hidden('MailMessage.auth_captcha') ?>
		<?php echo $this->Mailform->hidden('MailMessage.captcha_id') ?>
	<?php endif ?>
<?php endif ?>

<div class="contact-form-noticeWrap">
	<p class="contact-form-notice">
		<?php
			$sendBtnTitle = "内容を確認する";
			if ($freezed) {
				$sendBtnTitle = "送信する";
			}
		?>
		<a href="/privacy">プライバシーポリシー</a>をご一読いただき、同意された方は「<?php echo $sendBtnTitle; ?>」ボタンを押してください。
	</p>
</div>

<div class="submit mod-btnContainer contact-form-submit">
	<?php if ($freezed) : ?>
		<div class="mod-btn-02 contact-form-submit-send">
			<span class="btnInner">戻る</span>
			<?php echo $this->Mailform->submit('　' . __('戻る') . '　', ['div' => false, 'class' => 'form-submit', 'id' => 'BtnMessageBack']) ?>
		</div>
		<div class="mod-btn-01 contact-form-submit-send">
			<span class="btnInner">送信する</span>
			<?php echo $this->Mailform->submit('　' . __('送信する') . '　', ['div' => false, 'class' => 'form-submit', 'id' => 'BtnMessageSubmit']) ?>
		</div>
	<?php else : ?>
		<?php
		/*
		<div class="mod-btn-02 contact-form-submit-send">
			<span class="btnInner">取り消す</span>
			<input name="resetdata" value="取り消す" type="reset">
		</div>
		*/ ?>
		<div class="mod-btn-01 contact-form-submit-send">
			<span class="btnInner">内容を確認する</span>
			<?php echo $this->Mailform->submit('　' . __('内容を確認する') . '　', ['div' => false, 'class' => 'form-submit', 'id' => 'BtnMessageConfirm']) ?>
		</div>
	<?php endif; ?>
</div>

<?php echo $this->Mailform->end() ?>