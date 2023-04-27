<?php
/**
 * [MYPAGE] ログイン
 */
$this->layout = 'mypage_login';
$userModel = Configure::read('BcAuthPrefix.' . $currentPrefix . '.userModel');
if (!$userModel) {
	$userModel = 'User';
}
list(, $userModel) = pluginSplit($userModel);
$userController = Inflector::tableize($userModel);
?>
<div id="UserModel" hidden><?php echo $userModel ?></div>
<div id="Login" class="bca-login">
	<div id="LoginInner">
		<?php $this->BcBaser->flash() ?>

		<h1 class="bca-login__title"><?php echo $this->BcBaser->getImg('admin/logo_large.png', ['alt' => $this->BcBaser->getContentsTitle(), 'class' => 'bca-login__logo']) ?></h1>
		<div id="AlertMessage" class="message" hidden></div>

		<?php echo $this->BcForm->create($userModel, ['url' => ['action' => 'login']]) ?>
			<div class="login-input bca-login-form-item">
				<?php echo $this->BcForm->label($userModel . '.name', __d('baser', 'アカウント名')) ?>
				<?php echo $this->BcForm->input($userModel . '.name', ['type' => 'text', 'tabindex' => 1, 'autofocus' => true]) ?>
			</div>
			<div class="login-input bca-login-form-item">
				<?php echo $this->BcForm->label($userModel . '.password', __d('baser', 'パスワード')) ?>
				<?php echo $this->BcForm->input($userModel . '.password', ['type' => 'password', 'tabindex' => 2]) ?>
			</div>
			<div class="submit bca-login-form-btn-group">
				<?php echo $this->BcForm->button(__d('baser', 'ログイン'), ['type' => 'submit', 'div' => false, 'class' => 'bca-btn--login bca-btn', 'data-bca-btn-type' => 'login', 'id' => 'BtnLogin', 'tabindex' => 4]) ?>
			</div>
			<div class="clear login-etc bca-login-form-ctrl">
				<div class="bca-login-forgot-pass">
					<p><?php
					if ($currentPrefix == 'front'){
						$this->BcBaser->link(__d('baser', 'パスワードを忘れた場合はこちら'), ['action' => 'reset_password'], ['class' => 'c-link-underline']);
					} else {
						$this->BcBaser->link(__d('baser', 'パスワードを忘れた場合はこちら'), ['action' => 'reset_password', $this->request->params['prefix'] => true], ['class' => 'c-link-underline']);
					}
					?></p>
					<p>
					<?php $this->BcBaser->link(__d('baser', '新規ユーザー登録はこちら'), '/register/') ?></p>
				</div>
			</div>
		<?php echo $this->BcForm->end() ?>
	</div>

</div>
