<?php
/**
 * [InstantInstantPage] InstantInstantPage 追加／編集
 */
if (isset($user['user_group_id']) && InstantPageUtil::isMemberGroup($user['user_group_id'])) {
	include __DIR__ . DS . '../mypage/form.php';
} else {
	$this->BcBaser->css('admin/ckeditor/editor', ['inline' => true]);
	$this->BcBaser->js('InstantPage.admin/edit', false);
	$users = isset($users) ? $users : $this->InstantPageUser->getUserList();
	$InstantpageTemplateList = isset($InstantpageTemplateList) ? $InstantpageTemplateList : ['default', 'pop'];
	$editorOptions = [];
	$templates =[];
	if (isset($user['InstantPageUser'])) {
		$this->request->data['InstantPage']['instant_page_user_id'] = $user['InstantPageUser']['id'];
	}
	?>
	<?php if ($this->action == 'admin_add'): ?>
		<?php echo $this->BcForm->create('InstantPage', ['type' => 'file', 'url' => ['action' => 'add'], 'id' => 'InstantPageForm']) ?>
	<?php elseif ($this->action == 'admin_edit'): ?>
		<?php echo $this->BcForm->create('InstantPage', ['type' => 'file', 'url' => ['controller' => 'instant_pages', 'action' => 'edit', $this->BcForm->value('InstantPage.id'), 'id' => false], 'id' => 'InstantPageForm']) ?>
	<?php endif; ?>
	<?php echo $this->BcForm->input('InstantPage.id', ['type' => 'hidden']) ?>
	<?php echo $this->BcForm->input('InstantPage.mode', ['type' => 'hidden']) ?>

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
				<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.status', __d('baser', '公開状態')) ?>
				&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPage.status', ['type' => 'radio', 'options' => [0 => __d('baser', '公開しない'), 1 => __d('baser', '公開する')]]) ?>
				<br>
				<?php echo $this->BcForm->error('InstantPage.status') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.publish_begin', __d('baser', '公開日時')) ?></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('InstantPage.publish_begin', [
					'type' => 'dateTimePicker',
					'size' => 12,
					'maxlength' => 10,
					'dateLabel' => ['text' => __d('baser', '開始日付')],
					'timeLabel' => ['text' => __d('baser', '開始時間')]
				]) ?>
				&nbsp;〜&nbsp;
				<?php echo $this->BcForm->input('InstantPage.publish_end', [
					'type' => 'dateTimePicker',
					'size' => 12, 'maxlength' => 10,
					'dateLabel' => ['text' => __d('baser', '終了日付')],
					'timeLabel' => ['text' => __d('baser', '終了時間')]
				]) ?>
				<br>
				<?php echo $this->BcForm->error('InstantPage.publish_begin') ?>
				<?php echo $this->BcForm->error('InstantPage.publish_end') ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.instant_page_users_id', __d('baser', '作成者')) ?></th>
			<td class="col-input bca-form-table__input">
				<?php if (BcUtil::isAdminUser()): ?>
					<?php echo $this->BcForm->input('InstantPage.instant_page_users_id', ['type' => 'select', 'options' => $users]) ?>
					<?php else: ?>
						<?php echo h($this->BcText->arrayValue($this->BcForm->value('InstantPage.instant_page_users_id'), $users)) ?>　
						<?php echo $this->BcForm->hidden('InstantPage.instant_page_users_id') ?>
					<?php endif?>
					<?php echo $this->BcForm->error('InstantPage.instant_page_users_id') ?>
				</td>
			</tr>
			<?php if($InstantpageTemplateList): ?>
				<tr>
					<th class="bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.template', __d('baser', '固定ページテンプレート')) ?></th>
					<td class="col-input bca-form-table__input">
						<?php echo $this->BcForm->input('InstantPage.template', ['type' => 'radio', 'options' => $InstantpageTemplateList]) ?>
						<div
						class="helptext"><?php echo __d('baser', 'テーマフォルダ内の、InstantPages/templates テンプレートを配置する事で、ここでテンプレートを選択できます。') ?></div>
						<?php echo $this->BcForm->error('InstantPage.template') ?>
					</td>
				</tr>
			<?php endif ?>
			<tr>
				<th class="bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.page_key_word', __d('baser', 'キーワード')) ?></th>
				<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPage.page_key_word', [
						'type' => 'textarea',
						'cols' => 36,
						'rows' => 5,
						'style' => 'font-size:14px;font-family:Verdana,Arial,sans-serif;'
					]); ?>
					<?php echo $this->BcForm->error('InstantPage.page_key_word') ?>
				</td>
			</tr>
			<tr>
				<th class="bca-form-table__label"><?php echo $this->BcForm->label('InstantPage.page_description', __d('baser', 'ディスクリプション')) ?></th>
				<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPage.page_description', [
						'type' => 'textarea',
						'cols' => 36,
						'rows' => 5,
						'style' => 'font-size:14px;font-family:Verdana,Arial,sans-serif;'
					]); ?>
					<?php echo $this->BcForm->error('InstantPage.page_description') ?>
				</td>
			</tr>
		</table>
	</section>



	<div hidden="hidden">
		<div id="Action"><?php echo $this->request->action ?></div>
	</div>


	<?php echo $this->BcFormTable->dispatchBefore() ?>

	<div class="bca-section bca-section-editor-area">
		<?php
		//p($siteConfig);
		echo $this->BcForm->editor('InstantPage.contents', array_merge([
			'editor' => 'ckeditor',//$siteConfig['editor'],
			'editorUseDraft' => true,
			'editorDraftField' => 'draft',
			'editorWidth' => 'auto',
			'editorHeight' => '480px',
			'editorEnterBr' => $siteConfig['editor_enter_br']
		], $editorOptions));
		?>
		<?php echo $this->BcForm->error('InstantPage.contents') ?>
		<?php echo $this->BcForm->error('InstantPage.draft') /**/?>
	</div>



	<?php echo $this->BcFormTable->dispatchAfter() ?>
	<div class="bca-actions">

		<div class="bca-actions__before">
			<?php echo $this->BcHtml->link(__d('baser', '一覧に戻る'), ['controller' => 'instant_pages', 'action' => 'index'], [
				'class' => 'button bca-btn',
				'data-bca-btn-type' => 'back-to-list'
			]) ?>
		</div>
		<div class="bca-actions__main">

		<?php echo $this->BcForm->submit(__d('baser', '保存'), [
			'div' => false,
			'class' => 'button bca-btn',
			'data-bca-btn-type' => 'save',
			'data-bca-btn-size' => 'lg',
			'data-bca-btn-width' => 'lg',
			'id' => 'BtnSave'
		]) ?>
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
	<?php
}
