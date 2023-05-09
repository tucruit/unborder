<?php
/**
 * メールフォーム送信完了ページ
 */
if (Configure::read('debug') == 0 && $mailContent['MailContent']['redirect_url']) {
	$this->Html->meta(array('http-equiv' => 'Refresh'), null, array('content' => '5;url=' . $mailContent['MailContent']['redirect_url'], 'inline' => false));
}
?>
<!-- PAGE CONTENTS -->
<div class="signup signupIndex">
	<div class="l-subContentsContainer sub-container signupInner">
		<h2 class="mod-hl-01 signup-hl"><?php $this->BcBaser->contentsTitle(); ?> 仮登録完了</h2>
		<br>
		<h3 >※【まだ会員登録完了していません！】</h3>
		<p class="signup-lead">仮登録ありがとうございました。</p>
		<p class="signup-lead">ご登録頂いたメールアドレスに『【インスタントページ】新規登録申請通知』メールを送信しました。</p>
		<p class="signup-lead">メールに記載されたURLアドレスにアクセスしていただくとご登録の完了となります。</p>
	</div>
</div>
<!-- /PAGE CONTENTS -->
