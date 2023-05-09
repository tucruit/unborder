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

<div id="SelfUpdate" style="display: none"><?php echo $selfUpdate ?></div>
<div id="AlertMessage" style="display: none"></div>
<div id="UserGroupSetDefaultFavoritesUrl" style="display:none"><?php $this->BcBaser->url(['plugin' => null, 'controller' => 'user_groups', 'action' => 'set_default_favorites', @$this->request->data['UserGroup']['id']]) ?></div>


<?php echo $this->BcForm->create('InstantPageUser') ?>

<?php echo $this->BcFormTable->dispatchBefore() ?>

<?php echo $this->BcForm->hidden('InstantPageUser.id') ?>
<div class="l-contents__inr section">
	<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table bca-form-table">
		<?php if ($this->request->action == 'admin_edit'): ?>
			<tr>
				<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.id', 'No') ?></th>
				<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->value('InstantPageUser.id') ?>
					<?php echo $this->BcForm->input('InstantPageUser.id', ['type' => 'hidden']) ?>
					<?php echo $this->BcForm->input('InstantPageUser.name', ['type' => 'hidden']) ?>
				</td>
			</tr>
		<?php endif ?>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.name', __d('baser', 'ログインID')) ?>
				&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php if ($this->request->action == 'admin_add' && $editable): ?>
				<?php echo $this->BcForm->input('InstantPageUser.name', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'class' => 'bca-textbox__input nameCheck']) ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<!-- <input type="button" class="check_button" id="nameCheck" name="" required="" value="アカウント重複チェック"> -->
				<?php echo $this->BcForm->error('InstantPageUser.name') ?>
				<div id="helptextName"
					 class="helptext"><?php echo __d('baser', '半角英数字とハイフン、アンダースコアのみで入力してください。') ?></div>
				<?php else: ?>
					<?php echo h($this->request->data['InstantPageUser']['name']) ?>
					<?php echo $this->BcForm->input('InstantPageUser.name', ['type' => 'hidden']) ?>
				<?php endif ?>
			</td>
		</tr>

		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.company', '会社名') ?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.company', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '例：インスタント株式会社']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.company') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.real_name_1', 'お名前') ?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.real_name_1', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '姓']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.real_name_1') ?>
				<?php echo $this->BcForm->input('InstantPageUser.real_name_2', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '名']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.real_name_2') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.kana_1', 'フリガナ') ?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.kana_1', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => 'セイ']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.kana_1') ?>
				<?php echo $this->BcForm->input('InstantPageUser.kana_2', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => 'メイ']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.kana_2') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.email', __d('baser', 'メールアドレス')) ?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span></th>
			<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPageUser.email', ['type' => 'text', 'size' => 40, 'maxlength' => 255, 'class' => 'bca-textbox__input mailCheck']) ?>
					<i class="bca-icon--question-circle btn help bca-help"></i>
					<?php echo $this->BcForm->error('InstantPageUser.email') ?>
					<div id="helptextEmail" class="helptext">
						<?php echo __d('baser', '連絡用メールアドレスを入力します。') ?>
						<br><small>※ <?php echo __d('baser', 'パスワードを忘れた場合の新パスワードの通知先等') ?></small>
					</div>
				<?php echo $this->BcForm->error('InstantPageUser.email', '必須入力です') ?>
			</td>
		</tr>
		<?php echo $this->BcForm->input('InstantPageUser.nickname', ['type' => 'hidden', 'size' => 40, 'maxlength' => 255]) ?>
 		<?php echo $this->BcForm->input('InstantPageUser.user_group_id', ['type' => 'hidden', 'options' => $userGroups, 'value' => 4]) ?>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('InstantPageUser.zip_code', '郵便番号') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<script>
					function onkeyup(event) {
				  AjaxZip3.zip2addr(this, '', 'data[InstantPageUser][prefecture_id]', 'data[InstantPageUser][address]')
				}
				</script>
				<?php echo $this->BcForm->input('InstantPageUser.zip_code', [
					'type' => 'text',
					'size' => 20,
					'maxlength' => 15,
					'onkeyup' => "AjaxZip3.zip2addr(this,'','data[InstantPageUser][prefecture_id]','data[InstantPageUser][address]')"
				]) ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<div id="helptextPrefectureId" class="helptext">
					<ul>
						<li>〒郵便番号をハイフン抜きで入力してください</li>
					</ul>
				</div>
				<?php echo $this->BcForm->error('InstantPageUser.zip_code') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('InstantPageUser.prefecture_id', '都道府県') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.prefecture_id', [
					'type' => 'select',
					'options' => $this->BcText->prefList(),
					'escape' => true,
				]) ?>
				<?php echo $this->BcForm->error('InstantPageUser.prefecture_id') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('InstantPageUser.address', '住所') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.address', ['type' => 'text', 'size' => 80, 'maxlength' => 255, 'counter' => true]) ?>
				<?php echo $this->BcForm->error('InstantPageUser.address') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('InstantPageUser.building', '建物名') ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.building', ['type' => 'text', 'size' => 80, 'maxlength' => 255, 'counter' => true]) ?>
				<?php echo $this->BcForm->error('InstantPageUser.building') ?>
			</td>
		</tr>

		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.tel', '電話番号') ?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.tel', ['type' => 'text', 'size' => 30, 'maxlength' => 20, 'autofocus' => true, 'placeholder' => 'ハイフン抜きで入力してください']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.tel') ?>
			</td>
		</tr>

		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('InstantPageUser.password_1', __d('baser', 'パスワード')) ?>
				<?php if ($this->request->action == 'admin_add'): ?>
					<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>&nbsp;
				<?php endif; ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php if ($this->request->action == "admin_edit"): ?><small>[<?php echo __d('baser', 'パスワードは変更する場合のみ入力してください') ?>]</small><br /><?php endif ?>
				<!-- ↓↓↓自動入力を防止する為のダミーフィールド↓↓↓ -->
				<input type="password" name="dummypass" style="top:-100px;left:-100px;position:fixed;" />
				<?php echo $this->BcForm->input('InstantPageUser.password_1', ['type' => 'password', 'size' => 20, 'maxlength' => 255]) ?>
				<?php echo $this->BcForm->input('InstantPageUser.password_2', ['type' => 'password', 'size' => 20, 'maxlength' => 255]) ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<?php echo $this->BcForm->error('InstantPageUser.password') ?>
				<div id="helptextPassword" class="helptext">
					<ul>
						<li>
							<?php if ($this->request->action == "admin_edit"): ?>
								<?php echo __d('baser', 'パスワードの変更をする場合は、') ?>
							<?php endif; ?>
							<?php echo __d('baser', '確認の為２回入力してください。') ?></li>
						<li><?php echo __d('baser', '半角英数字(英字は大文字小文字を区別)とスペース、記号(._-:/()#,@[]+=&;{}!$*)のみで入力してください') ?></li>
						<li><?php echo __d('baser', '最低６文字以上で入力してください') ?></li>
					</ul>
				</div>
			</td>
		</tr>
		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>
</div>

<?php echo $this->BcFormTable->dispatchAfter() ?>
<div class="submit section bca-actions">
	<div class="bca-actions__main">
		<button class="button bca-btn" data-bca-btn-size="sm" data-bca-btn-width="sm"  onclick="location.href='/cmsadmin/instant_page/instant_page_users/';return false">一覧に戻る</button>
	</div>
	<div class="bca-actions__main">
		<?php echo $this->BcForm->button(__d('baser', '保存'), ['div' => false, 'class' => 'button bca-btn bca-actions__item', 'data-bca-btn-type' => 'save', 'data-bca-btn-size' => 'lg', 'data-bca-btn-width' => 'lg','id' => 'BtnSave']) ?>
	</div>
	<?php if ($editable): ?>
		<div class="bca-actions__sub">
			<?php if ($this->request->action == 'admin_edit' && $deletable): ?>
				<?php $this->BcBaser->link(__d('baser', '削除'), ['action' => 'delete', $this->BcForm->value('InstantPageUser.id')], ['class' => 'submit-token button bca-btn bca-actions__item', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'sm'], sprintf(__d('baser', '%s を本当に削除してもいいですか？'), $this->BcForm->value('InstantPageUser.real_name_1')), false); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>

<?php echo $this->BcForm->end() ?>
<?php if ($this->request->action == 'admin_edit' && $deletable): ?>
<?php endif; ?>
