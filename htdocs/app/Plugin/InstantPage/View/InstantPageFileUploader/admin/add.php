
<div style="margin-top: 60px">
	田端さん
</div>


<?php echo $this->BcForm->create('InstantPageFileUploader', array('enctype' => 'multipart/form-data')) ?>


<table class="form-table bca-form-table" data-bca-table-type="type2">
	<tr>
		<th class="col-head bca-form-table__label">
			タイトル
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->text('InstantPageFileUploader.name', ['class' => 'bca-textbox__input']); ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label">
			説明
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->textarea('InstantPageFileUploader.description', ['class' => 'bca-textarea__textarea']); ?>
		</td>
	</tr>
	<tr>
		<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageFileUploader.image_1', '背景画像') ?></th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('InstantPageFileUploader.image_1', array('type' => 'file')) ?>
			<?php echo $this->BcForm->error('InstantPageFileUploader.image_1') ?>
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
	<?php echo $this->BcForm->hidden('InstantPageFileUploader.user_id', ['value' => $thisUserId]); ?>
</table>




<div class="bca-actions">

	<div class="bca-actions__main">

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

	<?php echo $this->BcForm->end(); ?>
