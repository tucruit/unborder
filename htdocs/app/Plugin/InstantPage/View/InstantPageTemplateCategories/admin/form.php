<?php

	$editorOptions = [];
	$templates =[];

	?>

<?php echo $this->BcForm->create('InstantPageTemplateCategory', array('enctype' => 'multipart/form-data')) ?>


	<div hidden="hidden">
		<div id="Action"><?php echo $this->request->action ?></div>
	</div>


	<table class="form-table bca-form-table" data-bca-table-type="type2">
		<tr>
			<th class="col-head bca-form-table__label">
				タイトル<span class="required bca-label"
							  data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->text('InstantPageTemplateCategory.name', ['class' => 'bca-textbox__input']); ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				説明<span class="required bca-label"
						  data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
			</th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->textarea('InstantPageTemplateCategory.description', ['class' => 'bca-textarea__textarea']); ?>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageTemplateCategory.image_1', 'サムネイル') ?></th>
			<td class="col-input bca-form-table__input">
				<?php if ($this->request->action == 'admin_edit' && !empty($this->request->data['InstantPageTemplateCategory']['image_1'])): ?>
					<?php $this->BcBaser->img($imgPath.$this->request->data['InstantPageTemplateCategory']['image_1'], array('width' => '150px')) ?>
					<br />
					<?php echo $this->BcForm->input('InstantPageTemplateCategory.del_image_1', array('type' => 'checkbox', 'label' => 'この画像を削除する')) ?>
					<br />
				<?php endif; ?>
				<?php echo $this->BcForm->input('InstantPageTemplateCategory.image_1', array('type' => 'file')) ?>
				<?php echo $this->BcForm->error('InstantPageTemplateCategory.image_1') ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<div id="helptextName" class="helptext">
					<ul>
						<li>画像を登録できます。</li>
						<li>JPEG、PNG、GIFのみ利用できます。</li>
						<li>PDFなどのデータはフリースペースを使ってアップロードしてください。</li>
					</ul>
				</div>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageTemplateCategory.image_2', 'スクリーンショット（全体像）') ?></th>
			<td class="col-input bca-form-table__input">
				<?php if ($this->request->action == 'admin_edit' && !empty($this->request->data['InstantPageTemplateCategory']['image_2'])): ?>
					<?php $this->BcBaser->img($imgPath.$this->request->data['InstantPageTemplateCategory']['image_2'], array('width' => '150px')) ?>
					<br />
					<?php echo $this->BcForm->input('InstantPageTemplateCategory.del_image_2', array('type' => 'checkbox', 'label' => 'この画像を削除する')) ?>
					<br />
				<?php endif; ?>
				<?php echo $this->BcForm->input('InstantPageTemplateCategory.image_2', array('type' => 'file')) ?>
				<?php echo $this->BcForm->error('InstantPageTemplateCategory.image_2') ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<div id="helptextName" class="helptext">
					<ul>
						<li>画像を登録できます。</li>
						<li>JPEG、PNG、GIFのみ利用できます。</li>
						<li>PDFなどのデータはフリースペースを使ってアップロードしてください。</li>
					</ul>
				</div>
			</td>
		</tr>
	</table>


	<?php echo $this->BcFormTable->dispatchBefore() ?>

	<div class="bca-section bca-section-editor-area">
		<?php
		//p($siteConfig);
		echo $this->BcForm->editor('InstantPageTemplateCategory.contents', array_merge([
			'editor' => $siteConfig['editor'],//'ckeditor',
			'editorUseDraft' => true,
			'editorDraftField' => 'draft',
			'editorWidth' => 'auto',
			'editorHeight' => '480px',
			'editorEnterBr' => $siteConfig['editor_enter_br']
		], $editorOptions));
		?>

		<?php echo $this->BcForm->error('InstantPageTemplateCategory.contents') ?>
		<?php echo $this->BcForm->error('InstantPageTemplateCategory.draft') /**/?>
	</div>

	<section id="BasicSetting" class="bca-section" style="margin-top: 50px">
		<table class="form-table bca-form-table" data-bca-table-type="type2">
			<tr>
				<th class="col-head bca-form-table__label">
					公開設定
				</th>
				<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPageTemplateCategory.status', [
						'type' => 'select',
						'class' => 'bca-select__select',
						'options' => [0=>'非公開', 1 => '公開']
					]); ?>
				</td>
			</tr>
		</table>
	</section>



	<?php echo $this->BcFormTable->dispatchAfter() ?>
	<div class="bca-actions">
		<div class="bca-actions__before">
			<?php echo $this->BcHtml->link(__d('baser', '一覧に戻る'), ['controller' => 'instant_pages', 'action' => 'index'], [
				'class' => 'button bca-btn',
				'data-bca-btn-type' => 'back-to-list'
			]) ?>
		</div>
		<div class="bca-actions__main">

			<?php if ($this->action == 'admin_edit' || $this->action == 'admin_add'): ?>
				<div class="bca-actions__main">
					<?php echo $this->BcForm->button(__d('baser', 'プレビュー'),
						[
							'id' => 'BtnPreview',
							'div' => false,
							'class' => 'button bca-btn bca-actions__item',
							'data-bca-btn-type' => 'preview',
						]) ?>
					<?php echo $this->BcForm->button(__d('baser', '保存'),
						[
							'type' => 'submit',
							'id' => '',
							'div' => false,
							'class' => 'button bca-btn bca-actions__item',
							'data-bca-btn-type' => 'save',
							'data-bca-btn-size' => 'lg',
							'data-bca-btn-width' => 'lg',
						]) ?>
					</div>
				<?php endif ?>
			</div>
			<?php if ($this->action == 'admin_edit'): ?>
				<div class="bca-actions__sub">
					<?php $this->BcBaser->link(__d('baser', '削除'), ['action' => 'delete', $this->BcForm->value('InstantPage.id')],
						[
							'class' => 'submit-token button bca-btn bca-actions__item',
							'data-bca-btn-type' => 'delete',
							'data-bca-btn-size' => 'sm',
							'data-bca-btn-color' => 'danger'
						], sprintf(__d('baser', '%s を本当に削除してもいいですか？\n※ ブログ記事はゴミ箱に入らず完全に消去されます。'), $this->BcForm->value('InstantPage.name')), false); ?>
				</div>
				<?php echo $this->BcForm->input('InstantPageTemplateCategory.id', ['type' => 'hidden']) ?>
			<?php endif ?>
	<?php echo $this->BcForm->end(); ?>

