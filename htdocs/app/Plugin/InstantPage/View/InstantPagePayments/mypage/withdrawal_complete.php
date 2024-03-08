<?php
$user = $this->Session->read('Auth');
$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
?>
<div role="main" class="registrationInfo">
	<h1 class="mod-hl-pageTitle">退会処理</h1>
	<div class="l-container l-contentsContainer registrationInfoInner">
		<section class="registrationInfo-form">
			<h2 class="mod-hl-01 registrationInfo-form-hl">退会完了</h2>
			<p>
				退会処理が完了しました。ご利用ありがとうございました。
			</p>
			<p class="marginTop50">
				<a href="/">TOPへ戻る</a>
			</p>

		</section>
	</div>
</div>


