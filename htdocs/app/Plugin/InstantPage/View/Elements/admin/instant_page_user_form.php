<?php
/**
 * [ADMIN] ユーザー編集時 インスタントページユーザー 設定 追加／編集
 */
$this->BcBaser->js(array('admin/vendors/ajaxzip3', 'InstantPage.instant_page_users'), false);
$planIds =  Configure::read('InstantPage.plan_id');
?>
<script>
$(function(){
	// 有効グループチェック
	var enableGroup = [<?php echo implode(",", Configure::read('InstantPage.enableGroup')) ?>];
	var $inputUserGroup = $("#UserUserGroupId");
	var $InstantPageUserUserArea = $("#InstantPageUserFormTableSection");
	var $InstantPageUserFormTableSectionAlert = $('#InstantPageUserFormTableSectionAlert');

	$(window).on('load',function(){
		if ($.inArray(parseInt($inputUserGroup.val()), enableGroup) !== -1) {
			$InstantPageUserFormTableSectionAlert.hide();
			$InstantPageUserUserArea.show();
		} else {
			$InstantPageUserFormTableSectionAlert.show();
			$InstantPageUserUserArea.hide();
		}
	});
	$inputUserGroup.on('change', function(){
		if ($.inArray(parseInt($inputUserGroup.val()), enableGroup) !== -1) {
			$InstantPageUserUserArea.slideDown();
			$InstantPageUserFormTableSectionAlert.slideUp();
		} else {
			$InstantPageUserFormTableSectionAlert.slideDown();
			$InstantPageUserUserArea.slideUp();
		}
	});

	<?php if (!BcUtil::isAdminUser()): ?>
	<?php //システム管理者以外は「登録されている「よく使う項目」」欄を非表示とするため ?>
	$(window).on('load',function(){
		$('.panel-box').hide();
	});
	<?php endif; ?>
});
</script>
<div class="section" id="InstantPageUserFormTableSectionAlert" style="display: none;">
	<div id="MessageBox">
		<div id="flashMessage" class="message alert-notice">
			インスタントページユーザーを追加する際は、グループに「インスタントページユーザー」を指定してください。
		</div>
	</div>
</div>

<div class="section" id="InstantPageUserFormTableSection" style="display: none;">
	<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table bca-form-table InstantPageUserTable">
		<?php if ($this->request->action == 'admin_edit'): ?>
			<tr>
				<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.id', 'No') ?></th>
				<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->value('InstantPageUser.id') ?>
					<?php echo $this->BcForm->input('InstantPageUser.id', ['type' => 'hidden']) ?>
					<?php echo $this->BcForm->input('InstantPageUser.user_id', ['type' => 'hidden']) ?>
				</td>
			</tr>
		<?php endif ?>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.company', '会社名') ?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.company', ['type' => 'text', 'size' => 30, 'maxlength' => 255, 'autofocus' => true, 'placeholder' => '例：インスタント株式会社']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.company') ?>
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
		<?php //echo $this->BcForm->input('User.nickname', ['type' => 'hidden', 'size' => 40, 'maxlength' => 255]) ?>
 		<?php //echo $this->BcForm->input('User.user_group_id', ['type' => 'hidden', 'options' => $userGroups, 'value' => 4]) ?>
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
				<?php echo $this->BcForm->input('InstantPageUser.tel', ['type' => 'text', 'size' => 30, 'maxlength' => 20, 'autofocus' => true, 'placeholder' => '']) ?>
				<?php echo $this->BcForm->error('InstantPageUser.tel') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.plan_id', 'プラン') ?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.plan_id', ['type' => 'radio', 'autofocus' => true, 'options' => $planIds]) ?>
				<?php echo $this->BcForm->error('InstantPageUser.plan_id') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('InstantPageUser.creator_flg', 'クリエイター設定') ?>
			</th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPageUser.creator_flg', array('type' => 'checkbox', 'label' => 'クリエイター')) ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<div id="helptextBlank" class="helptext">
					<ul>
						<li>クリエイターとして自作のテーマの利用状況を確認するかどうかを指定します。</li>
					</ul>
				</div>
				<?php echo $this->BcForm->error('InstantPageUser.creator_flg') ?>
			</td>
		</tr>
		<?php echo $this->BcForm->dispatchAfterForm() ?>
	</table>
</div>
