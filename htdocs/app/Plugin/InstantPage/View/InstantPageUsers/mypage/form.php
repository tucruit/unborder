<?php
/**
 * [InstantPage] ユーザー 設定 追加／編集
 */
$this->layout = 'mypage_form';
$this->BcBaser->i18nScript([
	'alertMessage1' => __d('baser', '処理に失敗しました。'),
	'alertMessage2' => __d('baser', '送信先のプログラムが見つかりません。'),
	'confirmMessage1' => __d('baser', '更新内容をログイン情報に反映する為、一旦ログアウトします。よろしいですか？'),
	'confirmMessage2' => __d('baser', '登録されている「よく使う項目」を、このユーザーが所属するユーザーグループの初期設定として登録します。よろしいですか？'),
	'infoMessage1' => __d('baser', '登録されている「よく使う項目」を所属するユーザーグループの初期値として設定しました。'),
]);
?>
<div id="SelfUpdate" style="display: none"><?php echo $selfUpdate ?></div>
<div id="AlertMessage" style="display: none"></div>
<div id="UserGroupSetDefaultFavoritesUrl" style="display:none"><?php $this->BcBaser->url(['plugin' => null, 'controller' => 'user_groups', 'action' => 'set_default_favorites', @$this->request->data['UserGroup']['id']]) ?></div>
<p class="u-text-left u-text u-text--fz16 u-text-attention u-mb-5">ご契約時の情報（担当者等）が変更になった場合はこちらから変更を行ってください。</p>
<?php echo $this->BcForm->create('InstantPageUser') ?>
<?php echo $this->BcFormTable->dispatchBefore() ?>
<?php echo $this->BcForm->hidden('InstantPageUser.id') ?>
<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table bca-form-table p-account__table">
		<?php if ($this->request->action == 'admin_edit'): ?>
			<tr hiden>
				<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.id', 'No') ?></th>
				<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->value('InstantPageUser.id') ?>
					<?php echo $this->BcForm->input('InstantPageUser.id', ['type' => 'hidden']) ?>
					<?php echo $this->BcForm->input('InstantPageUser.name', ['type' => 'hidden']) ?>
				</td>
			</tr>
		<?php endif ?>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.name', __d('baser', 'ログインID')) ?></th>
			<td class="col-input bca-form-table__input confilm">
					<?php echo h($this->request->data['InstantPageUser']['name']) ?>
					<?php echo $this->BcForm->input('InstantPageUser.name', ['type' => 'hidden']) ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label p-account__required"><?php echo $this->BcForm->label('InstantPageUser.real_name_1', 'お名前') ?></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.real_name_1', ['type' => 'text', 'size' => 20, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '例：インスタント太郎', 'class' => 'c-input']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.real_name_1') ?>
			</td>
		</tr>
		<?php echo $this->BcForm->input('InstantPageUser.real_name_2', ['type' => 'hidden', 'size' => 40, 'maxlength' => 255]) ?>
		<?php echo $this->BcForm->input('InstantPageUser.nickname', ['type' => 'hidden', 'size' => 40, 'maxlength' => 255]) ?>
		<?php echo $this->BcForm->input('InstantPageUser.user_group_id', ['type' => 'hidden', 'options' => $userGroups, 'value' => 4]) ?>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.partner_id',  '企業名') ?></th>
			<td class="col-input bca-form-table__input confilm">
					<p><?php echo $this->BcText->arrayValue($this->request->data['InstantPageUser']['partner_id'], $partners) ?></p>
					<?php echo $this->BcForm->input('InstantPageUser.partner_id', ['type' => 'hidden']) ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.email', __d('baser', 'メールアドレス')) ?></th>
			<td class="col-input bca-form-table__input confilm">
					<?php echo h($this->request->data['InstantPageUser']['email']) ?>
					<?php echo $this->BcForm->input('InstantPageUser.email', ['type' => 'hidden']) ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label p-account__required"><?php echo $this->BcForm->label('InstantPageUser.tel', '電話番号') ?></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.tel', ['type' => 'text', 'size' => 20, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '03-5326-0360', 'class' => 'c-input']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.tel') ?>
			</td>
		</tr>
			<tr hidden>
				<th class="col-head bca-form-table__label">
					<?php echo $this->BcForm->label('InstantPageUser.password_1', __d('baser', 'パスワード')) ?>
				</th>
				<?php echo $this->BcForm->input('InstantPageUser.password_1', ['type' => 'hidden', 'size' => 20, 'maxlength' => 255]) ?>
				<?php echo $this->BcForm->input('InstantPageUser.password_2', ['type' => 'hidden', 'size' => 20, 'maxlength' => 255]) ?>
			</tr>
		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>


	<p class="u-text-left u-text u-mt-2 u-text-caption">※企業名の変更の場合は、弊社担当営業までご連絡ください。</p>
	<div class="c-btn c-btn--input c-btn--sm2 u-ml-auto u-mr-auto u-mt-6 u-mt-md-9 u-mb-2">
		<?php echo $this->BcForm->submit(__d('baser', '内容を保存する'), ['div' => false, 'class' => 'button bca-btn bca-actions__item', 'data-bca-btn-type' => 'save', 'data-bca-btn-size' => 'lg', 'data-bca-btn-width' => 'lg','id' => 'BtnSave']) ?><i class="c-icon c-icon--arrow"></i>
	</div>
<?php echo $this->BcForm->end() ?>
<p class="u-text-center u-text u-text--fz16 u-mt-2">パスワードを変更されたい方は<a class="c-link-underline" href="/mypage/instant_page/instant_page_users/edit_password/<?php echo $user['id']?>">こちら</a></p>
