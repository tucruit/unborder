<?php
/**
 * [PUBLISH] 404エラー
 *
 */
//if (strpos($message, '.html') !== false)
//  $message = str_replace('pages/', '', $message);
$messageText = $this->response->statusCode() . ' ' . $message;
?>
<!-- SUB H1 -->
<div class="sub-h1">
	<div class="l-subContentsContainer sub-h1Inner">
		<h1 class="sub-h1-hl">404 Not Found</h1>
	</div>
</div>
<!-- /SUB H1 -->
<div class="l-smallContainer">
	<!-- PAGE CONTENTS -->
	<div class="contact isSubmit">
		<div class="l-subContentsContainer sub-container contactInner">
			<h2 class="mod-hl-01 contact-hl">ページが見つかりませんでした。<br><span class="isSentence"><?php echo $messageText; ?></span></h2>
			<div class="contact-form">
				<p class="contact-form-thanksMsg">
					URLが正しく入力されているか、再度ご確認の上、ブラウザの再読み込みを行ってください。<br>
					正しくURLを入力してもページが表示されない場合は、<br>
					ページが移動、または掲載が終了し削除されたものと思われます。
				</p>
				<p class="contact-form-msg">
					<a href="/">トップページへ戻る</a>
				</p>
			</div>
		</div>
	</div>
	<!-- /PAGE CONTENTS -->
</div>
