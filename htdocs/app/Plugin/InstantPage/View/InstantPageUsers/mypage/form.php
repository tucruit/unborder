<?php
/**
 * [ADMIN] インスタントページユーザー 設定 追加／編集
 */
$this->BcBaser->i18nScript([
	'alertMessage1' => __d('baser', '処理に失敗しました。'),
	'alertMessage2' => __d('baser', '送信先のプログラムが見つかりません。'),
	'confirmMessage1' => __d('baser', '更新内容をログイン情報に反映する為、一旦ログアウトします。よろしいですか？'),
	'confirmMessage2' => __d('baser', '登録されている「よく使う項目」を、このユーザーが所属するユーザーグループの初期設定として登録します。よろしいですか？'),
	'infoMessage1' => __d('baser', '登録されている「よく使う項目」を所属するユーザーグループの初期値として設定しました。'),
]);
$this->BcBaser->js(array('admin/vendors/ajaxzip3', 'InstantPage.instant_page_users'), false);
?>
<script>
	$(function(){
		$('.bca-textbox').children().unwrap();
	});
</script>
<div role="main" class="registrationInfo">
	<h1 class="mod-hl-pageTitle">登録情報の変更</h1>
	<div class="l-container l-contentsContainer registrationInfoInner">
		<section class="registrationInfo-form">
			<h2 class="mod-hl-01 registrationInfo-form-hl">登録情報を変更する</h2>
			<!-- <p class="registrationInfo-form-lead">
				こちらにテキストが入ります。この文章はダミーです。こちらにテキストが入ります。この文章はダミーです。こちらにテキストが入ります。この文章はダミーです。こちらにテキストが入ります。この文章はダミーです。
			</p> -->
			<div id="SelfUpdate" style="display: none"><?php echo $selfUpdate ?></div>
			<div id="AlertMessage" style="display: none"></div>
			<div id="UserGroupSetDefaultFavoritesUrl" style="display:none">
				<?php
				$this->BcBaser->url(['plugin' => null, 'controller' => 'user_groups', 'action' => 'set_default_favorites', @$this->request->data['UserGroup']['id']]);
				?>
			</div>
			<?php echo $this->BcForm->create('InstantPageUser', ['class' => 'changeForm']) ?>

			<?php echo $this->BcFormTable->dispatchBefore() ?>

			<?php
			echo $this->BcForm->hidden('InstantPageUser.id');
			echo $this->BcForm->hidden('InstantPageUser.user_id');
			echo $this->BcForm->hidden('User.id');
			?>
			<table class="mod-table-form changeForm-table">
				<tbody>
					<tr>
						<th><?php echo $this->BcForm->label('User.name', __d('baser', 'アカウント名')) ?><span class="mod-form-hissuTag">必須</span></th>
						<td>
							<?php echo $this->BcForm->input('User.name', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'class' => 'mod-form-input-text', 'readonly' => 'readonly', 'div' => false]) ?>
							<?php echo $this->BcForm->hidden('User.name') ?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->BcForm->label('InstantPageUser.company', '会社名') ?><span class="mod-form-hissuTag">必須</span></th>
						<td>
							<?php echo $this->BcForm->input('InstantPageUser.company', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'class' => 'mod-form-input-text', 'div' => false]) ?>
							<?php echo $this->BcForm->error('InstantPageUser.company') ?>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->BcForm->label('User.real_name_1', 'お名前') ?><span class="mod-form-hissuTag">必須</span></th>
						<td>
							<div class="inputWrap__name">
								<?php
								echo $this->BcForm->input('User.real_name_1', ['type' => 'text', 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '姓', 'class' => 'mod-form-input-text--name', 'div' => false]);
								echo $this->BcForm->error('User.real_name_1');
								echo $this->BcForm->input('User.real_name_2', ['type' => 'text', 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '名', 'class' => 'mod-form-input-text--name', 'div' => false]);
								echo $this->BcForm->error('User.real_name_2');
								?>
							</div>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->BcForm->label('InstantPageUser.kana_1', 'フリガナ') ?><span class="mod-form-hissuTag">必須</span></th>
						<td>
							<div class="inputWrap__name">
							<?php
							echo $this->BcForm->input('InstantPageUser.kana_1', ['type' => 'text', 'class' => 'mod-form-input-text--name', 'maxlength' => 255, 'autofocus' => true, 'placeholder' => 'セイ', 'div' => false]);
							echo $this->BcForm->error('InstantPageUser.kana_1');
							echo $this->BcForm->input('InstantPageUser.kana_2', ['type' => 'text', 'class' => 'mod-form-input-text--name', 'maxlength' => 255, 'autofocus' => true, 'placeholder' => 'メイ', 'div' => false]);
							echo $this->BcForm->error('InstantPageUser.kana_2');
							?>
							</div>
						</td>
					</tr>
					<?php
					echo $this->BcForm->hidden('User.nickname');
					echo $this->BcForm->input('User.user_group_id', ['type' => 'hidden', 'options' => $userGroups, 'value' => 4]);
					 ?>
					<tr>
						<th><?php echo $this->BcForm->label('User.email', __d('baser', 'メールアドレス')) ?><span class="mod-form-hissuTag">必須</span></th>
						<td>
							<?php
							echo $this->BcForm->input('User.email', ['type' => 'text', 'class' => '', 'maxlength' => 255, 'class' => 'mod-form-input-text mailCheck', 'div' => false]);
							echo $this->BcForm->error('User.email') ?>
							<!-- <input type="text" name="" class="mod-form-input-text" placeholder="メールアドレス（確認用）"> -->
						</td>
					</tr>

					<tr>
						<th>住所<span class="mod-form-hissuTag">必須</span></th>
						<td>
							<div class="inputWrap__address">
								<span style="display: none;"><?php echo $this->BcForm->label('InstantPageUser.zip_code', '郵便番号') ?></span>
								<script>
									function onkeyup(event) {
										AjaxZip3.zip2addr(this, '', 'data[InstantPageUser][prefecture_id]', 'data[InstantPageUser][address]')
									}
								</script>
								<?php
								echo $this->BcForm->input('InstantPageUser.zip_code', [
									'type' => 'text',
									'class' => 'mod-form-input-text',
									'maxlength' => 15,
									'placeholder' => '〒郵便番号をハイフン抜きで入力してください',
									'onkeyup' => "AjaxZip3.zip2addr(this,'','data[InstantPageUser][prefecture_id]','data[InstantPageUser][address]')",
									'div' => false
								]);
								echo $this->BcForm->error('InstantPageUser.zip_code');
								echo $this->BcForm->input('InstantPageUser.prefecture_id', [
								 	'type' => 'select',
								 	'options' => $this->BcText->prefList(),
								 	'escape' => true,
								 	'class' => 'mod-form-select',
								 	'div' => false
								 ]);
								 echo $this->BcForm->error('InstantPageUser.prefecture_id');
								 echo $this->BcForm->input('InstantPageUser.address', [
								 	'type' => 'text',
								 	'class' => 'mod-form-input-text',
								 	'maxlength' => 255,
								 	'placeholder' => '市区町村・番地',
								 	'div' => false
								 ]);
								 echo $this->BcForm->error('InstantPageUser.address');
								 echo $this->BcForm->input('InstantPageUser.building', [
								 	'type' => 'text',
								 	'class' => 'mod-form-input-text',
								 	'maxlength' => 255,
								 	'placeholder' => '建物名',
								 	'div' => false
								 ]);
								 echo $this->BcForm->error('InstantPageUser.building');
								 ?>
							</div>
						</td>
					</tr>
					<tr>
						<th><?php echo $this->BcForm->label('InstantPageUser.tel', '電話番号') ?><span class="mod-form-hissuTag">必須</span></th>
						<td>
							<?php
							echo $this->BcForm->input('InstantPageUser.tel', ['type' => 'text', 'class' => 'mod-form-input-text', 'maxlength' => 20, 'autofocus' => true, 'placeholder' => 'ハイフン抜きで入力してください', 'div' => false]);
							echo $this->BcForm->error('InstantPageUser.tel') ?>
						</td>
					</tr>

					<tr>
						<th class="col-head bca-form-table__label">
							<?php echo $this->BcForm->label('User.password_1', __d('baser', 'パスワード')) ?>
							<?php if ($this->request->action == 'admin_add'): ?>
								<span class="mod-form-hissuTag">必須</span>
							<?php endif; ?>
						</th>
						<td class="col-input bca-form-table__input">
							<?php if ($this->request->action == "admin_edit"): ?><small>[<?php echo __d('baser', 'パスワードは変更する場合のみ入力してください') ?>]</small><br /><?php endif ?>
							<!-- ↓↓↓自動入力を防止する為のダミーフィールド↓↓↓ -->
							<input type="password" name="dummypass" style="top:-100px;left:-100px;position:fixed;" />
							<?php
							echo $this->BcForm->input('User.password_1', [
								'type' => 'password', 'class' => 'mod-form-input-text', 'maxlength' => 255, 'div' => false
							]);
							echo $this->BcForm->input('User.password_2', [
								'type' => 'password', 'class' => 'mod-form-input-text', 'maxlength' => 255, 'div' => false
							]);

							$erros = $this->validationErrors['InstantPageUser'];
							if (!empty($erros)) {
								foreach ($erros as $key => $error) {
									if (strpos($key, 'password_') !== false) {
										$errorMassage = implode(' ', $error);
										// validationErrorsでは、グループチェックフィールドは必須入力がバグっているため、除外
										if (strpos($errorMassage, '1 1') === false ){
											echo '<div class="error-message">'. $errorMassage. '</div>';
										}
									}
								}
							}
							echo $this->BcForm->error('User.password')
							?>
						</td>
					</tr>
					<?php echo $this->BcForm->dispatchAfterForm() ?>
				</tbody>
			</table>
			<?php echo $this->BcFormTable->dispatchAfter() ?>
			<div class="mod-btnContainer changeForm-submit">
				<div class="mod-btn-01 changeForm-submit-send">
					<span class="btnInner">保存</span>
					<?php echo $this->BcForm->button(__d('baser', '保存'), ['div' => false, 'class' => 'button bca-btn bca-actions__item', 'data-bca-btn-type' => 'save', 'data-bca-btn-size' => 'lg', 'data-bca-btn-width' => 'lg','id' => 'BtnSave']) ?>
				</div>
			</div>
			<?php echo $this->BcForm->end() ?>
		</section>
	</div>
</div>
