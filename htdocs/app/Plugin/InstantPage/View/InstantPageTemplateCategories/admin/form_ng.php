<?php

$editorOptions = [];
$templates =[];

?>

	<?php echo $this->BcForm->input('InstantPageTemplateCategory.id', ['type' => 'hidden']) ?>

	<?php echo $this->BcFormTable->dispatchBefore() ?>

		<section id="BasicSetting" class="bca-section">
			<table class="form-table bca-form-table" data-bca-table-type="type2">
				<tr>
					<th class="col-head bca-form-table__label">
						タイトル
					</th>
					<td class="col-input bca-form-table__input">
						<?php echo $this->BcForm->text('InstantPageTemplateCategory.name', ['class' => 'bca-textbox__input']); ?>
					</td>
				</tr>
				<tr>
					<th class="col-head bca-form-table__label">
						説明
					</th>
					<td class="col-input bca-form-table__input">
						<?php echo $this->BcForm->textarea('InstantPageTemplateCategory.description'); ?>
					</td>
				</tr>
		</table>

		<?php echo $this->BcForm->ckeditor('InstantPageTemplateCategory.contents', [
			'type' => 'editor',
			'editor' => @$siteConfig['editor'],
			'editorUseDraft' => true,
			'editorDraftField' => 'draft',
			'editorWidth' => 'auto',
			'editorHeight' => '480px',
			'editorEnterBr' => @$siteConfig['editor_enter_br']
		]); ?>

		<?php echo $this->BcForm->error('InstantPageTemplateCategory.contents') ?>
		<?php echo $this->BcForm->error('InstantPageTemplateCategory.draft') ?>

		<section id="BasicSetting" class="bca-section">
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

<div hidden="hidden">
	<div id="Action"><?php echo $this->request->action ?></div>
</div>

<?php echo $this->BcFormTable->dispatchAfter() ?>
<div class="bca-actions">

	<div class="bca-actions__before">
		<?php echo $this->BcHtml->link(__d('baser', '一覧に戻る'), ['controller' => 'instant_page_template_categories', 'action' => 'index'], [
			'class' => 'button bca-btn',
			'data-bca-btn-type' => 'back-to-list'
		]) ?>
	</div>
	<div class="bca-actions__main">

		<?php if ($this->action == 'admin_edit' || $this->action == 'admin_add'): ?>
			<div class="bca-actions__main">
				<?php echo $this->BcForm->button(__d('baser', '保存'),
					[
						'type' => 'submit',
						'id' => 'BtnSave',
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
		<?php endif ?>
<?php echo $this->BcForm->end(); ?>
