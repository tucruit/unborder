<?php
/**
 * メールフォーム
 * LP：メールフォーム
 *
 * @var BcAppView $this
 */
if ($this->request->is('Ajax') == false) { // パーツ呼び出し以外ではこのフォームは使わない
	header('Location: '. $this->BcBaser->getUri('/'));
	exit;
}
// ブラウザのヒストリーバック（戻るボタン）対応
$this->Mail->token();
$this->BcBaser->js('InstantPage.form-submit', true, ['defer']);

$this->request->data['MailMessage']['author'] = $this->request->query['author'];
$this->request->data['MailMessage']['url'] = $this->request->query['url'];
$InstantPageUserModel = ClassRegistry::init('InstantPage.InstantPageUser');
$author = $InstantPageUserModel->findById($this->request->query['author']);
if (!empty($author)) {
	$this->request->data['MailMessage']['author_name'] = $author['InstantPageUser']['company'] ? $author['InstantPageUser']['company'] : '';
	$this->request->data['MailMessage']['author_email'] = $author['User']['email'] ? $author['User']['email'] : '';
	$this->request->data['MailMessage']['author_tel'] = $author['InstantPageUser']['tel'] ? $author['InstantPageUser']['tel'] : '';
}
?>
<!-- PAGE CONTENTS -->
<div class="contact contactIndex">
	<div class="l-subContentsContainer sub-container contactInner">
		<div class="contact-form">
			<?php $this->BcBaser->flash() ?>
			<?php echo $this->Mailform->create('MailMessage', ['url' => $this->BcBaser->getContentsUrl(null, false, null, false)  . 'submit']) ?>
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
						<a href="/privacy">プライバシーポリシー</a>をご一読いただき、同意された方は「送信する」ボタンを押してください。
					</p>
				</div>

				<div class="submit mod-btnContainer contact-form-submit">

					<div class="mod-btn-02 contact-form-submit-send">
						<span class="btnInner">取り消す</span>
						<input name="resetdata" value="取り消す" type="reset">
					</div>

					<div class="mod-btn-01 contact-form-submit-send">
						<span class="btnInner">送信する</span>
						<?php echo $this->Mailform->button('　' . __('送信する') . '　', ['type' => 'submit', 'div' => false, 'class' => 'form-submit', 'id' => 'BtnMessageSubmit']) ?>
					</div>

				</div>

				<?php echo $this->Mailform->end() ?>
			</div>
		</div>
	</div>
	<!-- /PAGE CONTENTS -->
