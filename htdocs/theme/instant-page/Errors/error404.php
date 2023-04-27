<?php
/**
 * [PUBLISH] 404エラー
 *
 */
//if (strpos($message, '.html') !== false)
//  $message = str_replace('pages/', '', $message);
$messageText = $this->response->statusCode() . ' ' . $message;
?>
<div class="bge-contents">
	<h2 class="top-section-hl top-lead-hl">
		<span class="isSentence">ページが見つかりませんでした。</span>
		<span class="isSentence"><?php echo $messageText; ?></span>
	</h2>
	<div class="error-block">
		<p>URLが正しく入力されているか、再度ご確認の上、ブラウザの再読み込みを行ってください。<br>
			正しくURLを入力してもページが表示されない場合は、<br>
			ページが移動、または掲載が終了し削除されたものと思われます。</p>
		<div class="link">
			<a href="/">トップページへ戻る</a>
		</div>
	</div>

	<?php if (Configure::read('debug') > 0): ?>
		<?php echo $this->element('exception_stack_trace'); ?>
	<?php endif; ?>
</div>
