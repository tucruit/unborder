<?php
/**
 * [InstantPage] InstantPage 追加／編集
 */
$state = [0 => __d('baser', '契約中'), 1 => __d('baser', '未契約')];
$kantan = [0 => __d('baser', '両方'), 1 => __d('baser', '有（仕切）'), 2 => __d('baser', '有（手数料）')];
$isFrontDisplayed = [1 => __d('baser', '表示'), 0 => __d('baser', '非表示')];
$data = $this->request->data;
$this->BcBaser->js(array('admin/vendors/ajaxzip3'), false);
?>
<script type="text/javascript">
$(window).load(function() {
	$("#InstantPageName").focus();
});
</script>

<?php if($this->request->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('InstantPage', array('url' => array('action' => 'add'))) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('InstantPage', array('url' => array('action' => 'edit'))) ?>
	<?php echo $this->BcForm->input('InstantPage.id', array('type' => 'hidden')) ?>
<?php endif ?>

<h2>基本項目</h2>
<table cellpadding="0" cellspacing="0" class="form-table section bca-form-table">
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.type', 'パートナー種別') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<div id="InstantPages" class="bca-form-table__group bca-InstantPages">
				<?php
				//パートナー種別は配列に戻して表示
				if (!empty($data['InstantPage']['type'])) {
					$this->request->data['InstantPage']['type'] = explode('|', $data['InstantPage']['type']);
				}
				echo $this->BcForm->input('InstantPage.type', [
					'type' => 'select',
					'multiple' => 'checkbox',
					'options' => Configure::read('mj.partner_type'),
				]); ?>
				<?php echo $this->BcForm->error('InstantPage.type') ?>
			</div>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.state', '契約状態') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<div id="InstantPages" class="bca-form-table__group bca-InstantPages">
				<?php
				echo $this->BcForm->input('InstantPage.state', [
					'type' => 'radio',
					'options' => $state,
				]); ?>
				<?php echo $this->BcForm->error('InstantPage.state') ?>
			</div>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.kantan', '販売形態') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<div id="InstantPages" class="bca-form-table__group bca-InstantPages">
				<?php
				echo $this->BcForm->input('InstantPage.kantan', [
					'type' => 'radio',
					'options' => $kantan,
				]); ?>
				<?php echo $this->BcForm->error('InstantPage.kantan') ?>
			</div>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.is_front_displayed', 'パートナー一覧表示') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<div id="InstantPages" class="bca-form-table__group bca-InstantPages">
				<?php
				echo $this->BcForm->input('InstantPage.is_front_displayed', [
					'type' => 'radio',
					'options' => $isFrontDisplayed,
				]); ?>
				<?php echo $this->BcForm->error('InstantPage.is_front_displayed') ?>
			</div>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.contact_date', '契約開始日') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.contact_date', ['type' => 'datePicker', 'size' => 20, 'maxlength' => 10]) ?>
			<?php echo $this->BcForm->error('InstantPage.contact_date') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.no', '契約NO') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.no', ['type' => 'text', 'size' => 10, 'maxlength' => 11]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<div id="helptextNo" class="helptext">
				<ul>
					<li>契約NOを指定します。</li>
				</ul>
			</div>
			<?php echo $this->BcForm->error('InstantPage.no') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.code', '共通顧客コード') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.code', ['type' => 'text', 'size' => 10, 'maxlength' => 11]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<div id="helptextCode" class="helptext">
				<ul>
					<li>共通顧客コードを指定します。</li>
				</ul>
			</div>
			<?php echo $this->BcForm->error('InstantPage.code') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.app_no', '申請No') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.app_no', ['type' => 'text', 'size' => 10, 'maxlength' => 11]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<div id="helptextAppNo" class="helptext">
				<ul>
					<li>申請Noを指定します。</li>
				</ul>
			</div>
			<?php echo $this->BcForm->error('InstantPage.app_no') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.name', '企業名') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.name', ['type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<div id="helptextName" class="helptext">
				<ul>
					<li>企業名を指定します。</li>
				</ul>
			</div>
			<?php echo $this->BcForm->error('InstantPage.name') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.name_furigana', '企業名（カナ）') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.name_furigana', ['type' => 'text', 'size' => 40, 'maxlength' => 255, 'counter' => true]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<div id="helptextNameFurigana" class="helptext">
				<ul>
					<li>企業名のフリガナを指定します。</li>
					<li>全角カタカナで入力してください。</li>
				</ul>
			</div>
			<?php echo $this->BcForm->error('InstantPage.name_furigana') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.zip_code', '郵便番号') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
<script>
	function onkeyup(event) {
  AjaxZip3.zip2addr(this, '', 'data[InstantPage][prefecture_id]', 'data[InstantPage][address]')
}
</script>
			<?php echo $this->BcForm->input('InstantPage.zip_code', [
				'type' => 'text',
				'size' => 10,
				'maxlength' => 15,
				'onkeyup' => "AjaxZip3.zip2addr(this,'','data[InstantPage][prefecture_id]','data[InstantPage][address]')"
			]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<div id="helptextPrefectureId" class="helptext">
				<ul>
					<li>半角英数ハイフン有りで入力してください。</li>
				</ul>
			</div>
			<?php echo $this->BcForm->error('InstantPage.zip_code') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.prefecture_id', '都道府県') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.prefecture_id', [
				'type' => 'select',
				'options' => $this->BcText->prefList(),
				'escape' => true,
			]) ?>
			<?php echo $this->BcForm->error('InstantPage.prefecture_id') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.address', '住所') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.address', ['type' => 'text', 'size' => 80, 'maxlength' => 255, 'counter' => true]) ?>
			<?php echo $this->BcForm->error('InstantPage.address') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.tel', '電話番号') ?>&nbsp;<span class="required bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.tel', ['type' => 'text', 'size' => 80, 'maxlength' => 255]) ?>
			<?php echo $this->BcForm->error('InstantPage.tel') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.fax', 'FAX') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.fax', ['type' => 'text', 'size' => 80, 'maxlength' => 255]) ?>
			<?php echo $this->BcForm->error('InstantPage.fax') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.homepage', 'ホームページURL') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.homepage', ['type' => 'text', 'size' => 80, 'maxlength' => 255]) ?>
			<?php echo $this->BcForm->error('InstantPage.homepage') ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.description', '企業紹介') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<span class="bca-form-table__input-wrap">
				<?php echo $this->BcForm->ckeditor('InstantPage.description', [
					'editorWidth' => 'auto',
					'editorHeight' => '120px',
					'editorToolType' => 'simple',
					'editorEnterBr' => @$siteConfig['editor_enter_br']
				]); ?>
				<?php echo $this->BcForm->error('InstantPage.description') ?>
			</span>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.url', '紹介記事URL') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.url', ['type' => 'text', 'size' => 80, 'maxlength' => 255]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<?php echo $this->BcForm->error('InstantPage.url') ?>
			<div id="helptextNameUrl" class="helptext">
				<ul>
					<li>http〜から記入した場合、リストページでは別タブで開くようになります。</li>
					<li>サイト内の場合、https://partner.mjs.co.jpを省略して「/」から始めてください。</li>
				</ul>
			</div>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.solution', '取得ソリューション') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<div id="InstantPages" class="bca-form-table__group bca-InstantPages">
				<?php
				//ソリューションは配列に戻して表示
				if (!empty($data['InstantPage']['solution'])) {
					$this->request->data['InstantPage']['solution'] = explode('|', $data['InstantPage']['solution']);
				}
				echo $this->BcForm->input('InstantPage.solution', [
					'type' => 'select',
					'multiple' => 'checkbox',
					'options' => Configure::read('mj.solution'),
				]); ?>
				<?php echo $this->BcForm->error('InstantPage.solution') ?>
			</div>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('InstantPage.domain', '許可ドメイン') ?>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPage.domain', ['type' => 'textarea', 'cols' => 60, 'rows' => 2]) ?>
			<i class="bca-icon--question-circle btn help bca-help"></i>
			<div id="helptextDomain" class="helptext">
				<ul>
					<li>※ドメインを複数登録される場合には、カンマ(,)区切りでご入力ください。</li>
				</ul>
			</div>
			<?php echo $this->BcForm->error('InstantPage.domain') ?>
		</td>
	</tr>
</table>

<div class="submit bca-actions">
	<?php echo $this->BcForm->submit('保　存', array('div' => false, 'class' => 'button bca-btn bca-actions__item', 'data-bca-btn-type' => 'save', 'data-bca-btn-size' => 'lg', 'data-bca-btn-width' => 'lg')) ?>
	<?php if ($editable): ?>
		<div class="bca-actions__sub">
			<?php if ($this->request->action == 'admin_edit' && $deletable): ?>
				<?php $this->BcBaser->link(__d('baser', '削除'), ['action' => 'delete', $this->BcForm->value('InstantPage.id')], ['class' => 'submit-token button bca-btn bca-actions__item', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'sm'], sprintf(__d('baser', '%s を本当に削除してもいいですか？'), $this->BcForm->value('InstantPage.name')), false); ?>
			<?php endif; ?>
		</div>
	<?php endif; ?>
</div>
<?php echo $this->BcForm->end() ?>
<?php if($this->request->action == 'admin_edit'): ?>
<h2>担当者情報</h2>
<table cellpadding="0" cellspacing="0" class="form-table section bca-form-table">
		<tr>
			<th class="col-head bca-form-table__label">
				<p>担当者情報</p>
			</th>
			<td class="col-input bca-form-table__input">
				<p><?php $this->BcBaser->link('担当者の追加はこちら', '/cmsadmin/instant_page/instant_page_users/add?partner_id='. $data['InstantPage']['id'])?></p>
			</td>
		</tr>
</table>
<?php endif;?>
