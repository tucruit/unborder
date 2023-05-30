<?php
/**
 * メールフォーム送信完了ページ
 */
if (Configure::read('debug') == 0 && $mailContent['MailContent']['redirect_url']) {
	$this->Html->meta(array('http-equiv' => 'Refresh'), null, array('content' => '5;url=' . $mailContent['MailContent']['redirect_url'], 'inline' => false));
}
?>
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader'); ?>
<!-- /SUB H1 -->
<!-- PAGE CONTENTS -->
<div class="contact isSubmit">
	<div class="l-subContentsContainer sub-container contactInner">
		<h2 class="mod-hl-01 contact-hl">><?php $this->BcBaser->contentsTitle(); ?> 仮登録完了</h2>
		<h3 >※【まだ会員登録完了していません！】</h3>
		<div class="contact-form">
			<p class="contact-form-thanksMsg">仮登録ありがとうございました。</p>
			<p class="contact-form-msg">ご登録頂いたメールアドレスに『【インスタントページ】新規登録申請通知』メールを送信しました。</p>
			<p class="contact-form-msg">メールに記載されたURLアドレスにアクセスしていただくとご登録の完了となります。</p>
		</div>
	</div>
</div>
<!-- /PAGE CONTENTS -->
