<?php
/**
 * [InstantInstantPage] InstantInstantPage 追加／編集
 */
$this->BcBaser->css('admin/ckeditor/editor', ['inline' => true]);
$this->BcBaser->js('admin/Instantpages/edit', false);
$editorOptions = [];
$layoutTemplates =[];
if (isset($user['InstantPageUser'])) {
	$this->request->data['InstantPage']['instant_page_user_id'] = $user['InstantPageUser']['id'];
}
?>

<section id="BasicSetting" class="bca-section">
	<table class="form-table bca-form-table" data-bca-table-type="type2">
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.name', 'URL') ?>
				&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPage.name', ['type' => 'text', 'size' => 20, 'autofocus' => true]) ?>
				<?php echo $this->BcForm->error('InstantPage.name') ?>
				<span class="bca-post__url">
          			<?php //echo strip_tags($linkedFullUrl, '<a>') ?>
        		</span>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php
				echo $this->BcForm->label('InstantPage.title', __d('baser', 'タイトル'));
				?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPage.title', ['type' => 'text', 'size' => 50]) ?>　
					<?php echo $this->BcForm->error('InstantPage.title') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.self_status', __d('baser', '公開状態')) ?>
				&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPage.self_status', ['type' => 'radio', 'options' => [0 => __d('baser', '公開しない'), 1 => __d('baser', '公開する')]]) ?>
				<br>
				<?php echo $this->BcForm->error('InstantPage.self_status') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.self_status', __d('baser', '公開日時')) ?></th>
			<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPage.self_publish_begin', [
						'type' => 'dateTimePicker',
						'size' => 12,
						'maxlength' => 10,
						'dateLabel' => ['text' => __d('baser', '開始日付')],
						'timeLabel' => ['text' => __d('baser', '開始時間')]
					]) ?>
					&nbsp;〜&nbsp;
					<?php echo $this->BcForm->input('InstantPage.self_publish_end', [
						'type' => 'dateTimePicker',
						'size' => 12, 'maxlength' => 10,
						'dateLabel' => ['text' => __d('baser', '終了日付')],
						'timeLabel' => ['text' => __d('baser', '終了時間')]
					]) ?>
				<br>
				<?php echo $this->BcForm->error('InstantPage.self_publish_begin') ?>
				<?php echo $this->BcForm->error('InstantPage.self_publish_end') ?>
				<?php if (($this->BcForm->value('InstantPage.publish_begin') != $this->BcForm->value('InstantPage.self_publish_begin')) ||
					($this->BcForm->value('InstantPage.publish_end') != $this->BcForm->value('InstantPage.self_publish_end'))): ?>
					<p>※ <?php echo __d('baser', '親フォルダの設定を継承し公開期間が設定されている状態となっています') ?><br>
						（<?php echo $this->BcTime->format('Y/m/d H:i', $this->BcForm->value('InstantPage.publish_begin')) ?>
						〜
						<?php echo $this->BcTime->format('Y/m/d H:i', $this->BcForm->value('InstantPage.publish_end')) ?>）
					</p>
				<?php endif ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.instant_page_user_id', __d('baser', '作成者')) ?></th>
			<td class="col-input bca-form-table__input">
				<?php if (BcUtil::isAdminUser()): ?>
					<?php echo $this->BcForm->input('InstantPage.instant_page_user_id', ['type' => 'select', 'options' => $users]) ?>
				<?php else: ?>
					<?php echo h($this->BcText->arrayValue($this->BcForm->value('InstantPage.instant_page_user_id'), $users)) ?>　
					<?php echo $this->BcForm->hidden('InstantPage.instant_page_user_id') ?>
				<?php endif?>
				<?php echo $this->BcForm->error('InstantPage.instant_page_user_id') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('Content.layout_template', __d('baser', 'レイアウトテンプレート')) ?></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('Content.layout_template', ['type' => 'select', 'options' => $layoutTemplates]) ?>
				　
				<?php echo $this->BcForm->error('Content.layout_template') ?>　
			</td>
		</tr>

	</table>
</section>



<div hidden="hidden">
	<div id="Action"><?php echo $this->request->action ?></div>
</div>

<?php echo $this->BcForm->create('InstantPage') ?>
<?php echo $this->BcForm->input('InstantPage.mode', ['type' => 'hidden']) ?>
<?php echo $this->BcForm->input('InstantPage.id', ['type' => 'hidden']) ?>

<?php echo $this->BcFormTable->dispatchBefore() ?>

<div class="bca-section bca-section-editor-area">
	<?php echo $this->BcForm->editor('InstantPage.contents', array_merge([
		'editor' => @$siteConfig['editor'],
		'editorUseDraft' => true,
		'editorDraftField' => 'draft',
		'editorWidth' => 'auto',
		'editorHeight' => '480px',
		'editorEnterBr' => @$siteConfig['editor_enter_br']
	], $editorOptions)); ?>
	<?php echo $this->BcForm->error('InstantPage.contents') ?>
	<?php echo $this->BcForm->error('InstantPage.draft') ?>
</div>

<?php if (BcUtil::isAdminUser()): ?>
	<section class="bca-section" data-bca-section-type="form-group">
		<div class="bca-collapse__action">
			<button type="button" class="bca-collapse__btn" data-bca-collapse="collapse"
			data-bca-target="#InstantpageSettingBody" aria-expanded="false" aria-controls="InstantpageSettingBody"><?php echo __d('baser', '詳細設定') ?>&nbsp;&nbsp;<i
			class="bca-icon--chevron-down bca-collapse__btn-icon"></i></button>
		</div>
		<div class="bca-collapse" id="InstantpageSettingBody" data-bca-state="">
			<table class="form-table bca-form-table" data-bca-table-type="type2">
				<?php if($InstantpageTemplateList): ?>
					<tr>
						<th class="bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.Instantpage_template', __d('baser', '固定ページテンプレート')) ?></th>
						<td class="col-input bca-form-table__input">
							<?php echo $this->BcForm->input('InstantPage.Instantpage_template', ['type' => 'select', 'options' => $InstantpageTemplateList]) ?>
							<div
							class="helptext"><?php echo __d('baser', 'テーマフォルダ内の、InstantPages/templates テンプレートを配置する事で、ここでテンプレートを選択できます。') ?></div>
							<?php echo $this->BcForm->error('InstantPage.Instantpage_template') ?>
						</td>
					</tr>
				<?php endif ?>
				<tr>
					<th class="bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.code', __d('baser', 'コード')) ?></th>
					<td class="col-input bca-form-table__input">
						<?php echo $this->BcForm->input('InstantPage.code', [
							'type' => 'textarea',
							'cols' => 36,
							'rows' => 5,
							'style' => 'font-size:14px;font-family:Verdana,Arial,sans-serif;'
						]); ?>
						<i class="bca-icon--question-circle btn help bca-help"></i>
						<div
						class="helptext"><?php echo __d('baser', '固定ページの本文には、ソースコードに切り替えてPHPやJavascriptのコードを埋め込む事ができますが、ユーザーが間違って削除してしまわないようにこちらに入力しておく事もできます。<br>入力したコードは、自動的にコンテンツ本体の上部に差し込みます。') ?></div>
						<?php echo $this->BcForm->error('InstantPage.code') ?>
					</td>
				</tr>
				<?php echo $this->BcForm->dispatchAfterForm() ?>
			</table>
		</div>
	</section>
	<?php else: ?>
		<?php echo $this->BcForm->input('InstantPage.code', ['type' => 'hidden']) ?>
	<?php endif ?>

	<?php echo $this->BcFormTable->dispatchAfter() ?>

	<?php echo $this->BcForm->submit(__d('baser', '保存'), [
		'div' => false,
		'class' => 'button bca-btn',
		'data-bca-btn-type' => 'save',
		'data-bca-btn-size' => 'lg',
		'data-bca-btn-width' => 'lg',
		'id' => 'BtnSave'
	]) ?>
<div class="bca-actions">
	<div class="bca-actions__before">
		<?php echo $this->BcHtml->link(__d('baser', '一覧に戻る'), ['controller' => 'Instantpages', 'action' => 'index'], [
			'class' => 'button bca-btn',
			'data-bca-btn-type' => 'back-to-list'
		]) ?>
	</div>
	<div class="bca-actions__main">
			<?php echo $this->BcForm->button(__d('baser', 'プレビュー'), [
				'class' => 'button bca-btn bca-actions__item',
				'data-bca-btn-type' => 'preview',
				'id' => 'BtnPreview'
			]) ?>
	</div>
	<div class="bca-actions__sub">
			<?php echo $this->BcForm->button('削除', [
				'data-bca-btn-type' => 'delete',
				'data-bca-btn-size' => 'sm',
				'data-bca-btn-color' => 'danger',
				'class' => 'button bca-btn',
				'id' => 'BtnDelete'
			]) ?>
	</div>
</div>
	<?php echo $this->BcForm->end(); ?>
