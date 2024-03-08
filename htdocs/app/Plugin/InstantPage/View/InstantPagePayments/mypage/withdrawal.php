<?php
$user = $this->Session->read('Auth');
$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
?>
<div role="main" class="registrationInfo">
	<h1 class="mod-hl-pageTitle">退会処理</h1>
	<div class="l-container l-contentsContainer registrationInfoInner">
		<section class="registrationInfo-form">
			<h2 class="mod-hl-01 registrationInfo-form-hl">退会確認画面</h2>

			<?php echo $this->BcForm->create() ?>
			<div class="js-scrollable myPage-siteTableWrap">
				<p>
					本当に退会してよろしいですか？
				</p>

			</div>
			<input type="hidden" name="confirm" value="1">
			<button type="submit" class="mod-btn-01" style="margin-top: 40px;">退会する</button>
			<?php echo $this->BcForm->end() ?>
		</section>
	</div>
</div>


