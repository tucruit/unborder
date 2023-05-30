<?php
//$this->BcBaser->css('admin/ckeditor/editor', ['inline' => true]);
/*
謎：lib/Baser/Plugin/Blog/BlogPosts/admin/form.phpを参考にしたが、下記の謎が残っている。
・参考元は「bca-form-table__label」や「bca-form-table__input」といったクラスがコード上は存在しないのに、ブラウザで表示する際には付与されている。本コードではべた書き。
・参考元はsubmitボタンが<div class="submit">で囲われているが、本コードで同じクラスを当てても中央寄せと余白が正常にならない。
*/
?>

<?php if (isset($id)): ?>
	<?php echo $this->BcForm->create('CuSecurityPatche', ['url' => ['action' => 'edit', $id]]) ?>
<?php else: ?>
	<?php echo $this->BcForm->create('CuSecurityPatche', ['url' => ['action' => 'add']]) ?>
<?php endif; ?>

<div class="section">

    <table cellpadding="0" cellspacing="0" id="FormTable" class="form-table bca-form-table">
        <tr>
            <th class="col-head bca-form-table__label">
                <?php echo $this->BcForm->label('CuSecurityPatche.title', __d('baser', 'タイトル')) ?>
            </th>
            <td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('CuSecurityPatche.title', [
					'type' => 'text',
					'size' => 80,
					'maxlength' => 255,
					'data-input-text-size' => 'full-counter',
				]) ?>
            </td>
        </tr>

        <tr>
            <th class="col-head bca-form-table__label">
                <?php echo $this->BcForm->label('CuSecurityPatche.publish_date', __d('baser', '公開日')) ?>
            </th>
            <td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('CuSecurityPatche.publish_date', [
					'type' => 'datePicker',
					'size' => 20,
					'maxlength' => 255,
					'data-input-text-size' => 'full-counter',
				]) ?>
            </td>
        </tr>

        <tr>
            <th class="col-head bca-form-table__label">
                <?php echo $this->BcForm->label('CuSecurityPatche.version', __d('baser', 'バージョン')) ?>
            </th>
            <td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('CuSecurityPatche.version', [
					'type' => 'text',
					'size' => 20,
					'maxlength' => 255,
					'data-input-text-size' => 'full-counter',
				]) ?>
            </td>
        </tr>


        <tr>
            <th class="col-head bca-form-table__label">
                <?php echo $this->BcForm->label('CuSecurityPatche.url', __d('baser', 'URL')) ?>
            </th>
            <td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('CuSecurityPatche.url', [
					'type' => 'text',
					'size' => 80,
					'maxlength' => 255,
					'data-input-text-size' => 'full-counter',
				]) ?>
            </td>
        </tr>


        <tr>
            <th class="col-head bca-form-table__label">
                <?php echo $this->BcForm->label('CuSecurityPatche.done', __d('baser', 'パッチ適用状態')) ?>
            </th>
            <td class="col-input bca-form-table__input">
                <?php echo $this->BcForm->input( 'done' , [
                    'type' => 'radio',
                    'options' => [
                        0 => '未',
                        1 => '済'
                    ]
                ]) ?>
            </td>
        </tr>
        <tr>
            <th class="col-head bca-form-table__label">
                <?php echo $this->BcForm->label('CuSecurityPatche.comment', __d('baser', '備考')) ?>
            </th>
            <td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->input('CuSecurityPatche.comment', [
					'type' => 'textarea',
					'cols' => '10',
					'rows' => '5',
					'data-input-text-size' => 'full-counter',
				]) ?>
            </td>
        </tr>
    </table>

    <div class="bca-actions">
        <div class="bca-actions__main">
            <button type="submit" id="BtnSave" class="button bca-btn bca-actions__item" data-bca-btn-type="save" data-bca-btn-size="lg" data-bca-btn-width="lg">保存</button>

            <a href="<?php echo $this->BcBaser->getUrl(['action' => 'index']); ?>"><button type="button" class="button bca-btn bca-actions__item">戻る</button></a>
        </div>
    </div>

</div>

<?php echo $this->BcForm->end(); ?>
