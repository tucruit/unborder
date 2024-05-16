<?php
/**
 * [MYPAGE] ログイン
 */
$this->layout = 'InstantPage.mypage_login';
$currentPrefix = 'admin';
$userModel = Configure::read('BcAuthPrefix.admin.userModel');
if (!$userModel) {
	$userModel = 'User';
}
list(, $userModel) = pluginSplit($userModel);
$userController = Inflector::tableize($userModel);
$this->append('script', <<< CSS_END
<style type="text/css">
#CreditScroller,#CreditScroller a{
	color:#333!important;
}
#Credit {
	text-align: right;
}
#CreditScrollerInner {
	margin-right:0;
}
html {
	margin-top:0;
}
.bca-container {
	height: auto !important;
	background: #F4F5F1;
}
.bca-crumb,
.bca-main-body-header {
	display: none;
}
</style>
CSS_END
);
?>
<div id="UserModel" hidden><?php echo $userModel ?></div>
<div class="l-subContentsContainer sub-container usersInner">
	<?php $this->BcBaser->flash() ?>
	<?php
	if ($this->request->here == '/cmsadmin/users/login') {
		$url = ['action' => 'login'];
	} else {
		$url = '/cmsadmin/users/login';
	}
	echo $this->BcForm->create($userModel, ['url' => $url, 'class' => 'users-form']);
	?>
		<h2 class="users-sectionHl users-form-hl">
			<span class="noWrap">アカウント名とパスワードを</span><span class="noWrap">入力して、ログインしてください。</span>
		</h2>
		<div class="users-form-inputBlock">
			<div class="inputBlock-inputGroup">
				<?php echo $this->BcForm->label('User.name', __d('baser', 'アカウント名'), ['class' => 'inputBlock-inputGroup-label']) ?>
				<?php echo $this->BcForm->input('User.name', ['type' => 'text', 'div' => ['tag' => false], 'required' => 'required', 'tabindex' => 1, 'autofocus' => true, 'class' => 'mod-form-input-text inputBlock-inputGroup-txtBox']) ?>
			</div>
			<div class="inputBlock-inputGroup">
				<?php echo $this->BcForm->label('User.password', __d('baser', 'パスワード'), ['class' => 'inputBlock-inputGroup-label']) ?>
				<?php echo $this->BcForm->input('User.password', ['type' => 'password', 'div' => ['tag' => false], 'tabindex' => 2, 'class' => 'mod-form-input-text inputBlock-inputGroup-txtBox']) ?>
			</div>
			<?php
			if ($this->request->here == '/instant_page/instant_page_users/login'){
				$this->BcBaser->link(__d('baser', 'パスワードをお忘れの方はこちら＞'), ['plugin' => 'instant_page', 'controller' => 'instant_page_users', 'action' => 'send_activate_url'], ['class' => 'inputBlock-linkForgotPass']);
			} else {
				$this->BcBaser->link(__d('baser', 'パスワードをお忘れの方はこちら＞'), ['plugin' => null, 'action' => 'send_activate_url', $this->request->params['prefix'] => true], ['class' => 'inputBlock-linkForgotPass']);
			}
			?>
			<?php echo $this->BcForm->input($userModel . '.saved', [
					'type' => 'checkbox',
					'label' => __d('baser', 'ログイン状態を保存する'),
					'class' => 'bca-checkbox__input bca-login-form-checkbox ',
					'tabindex' => 3,
				]); ?>
			<div class="mod-btn-01 inputBlock-loginBtn">
				<span class="btnInner">ログイン</span>
				<?php echo $this->BcForm->button(__d('baser', 'ログイン'), ['type' => 'submit', 'div' => false, 'class' => 'bca-btn--login bca-btn', 'data-bca-btn-type' => 'login', 'id' => 'BtnLogin', 'tabindex' => 4]) ?>

			</div>
		</div>
	<?php echo $this->BcForm->end() ?>
	<div class="usersLogin-signup">
		<h2 class="users-sectionHl usersLogin-signup-hl">まだご登録がお済みでない方</h2>
		<?php $this->BcBaser->link('<span class="btnInner">新規登録</span>', '/signup/', ['class' => 'mod-btn-03 usersLogin-signup-btn']) ?>
	</div>
</div>
