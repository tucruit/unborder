<?php
/**
 * メールフォーム送信完了ページ
 */
if (Configure::read('debug') == 0 && $mailContent['MailContent']['redirect_url']) {
	$this->Html->meta(array('http-equiv' => 'Refresh'), null, array('content' => '5;url=' . $mailContent['MailContent']['redirect_url'], 'inline' => false));
}
?>
<section>
	<div class="c-page-sub__content-detail">
		<div class="c-content-main">
			<div class="cc-form-description">
				<p>お問い合わせありがとうございました。担当よりご連絡いたしますので、しばらくお待ちくださいませ。<br>
			</div>
		</div>
	</div>
</section>
