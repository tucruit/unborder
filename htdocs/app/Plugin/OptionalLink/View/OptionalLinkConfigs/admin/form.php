<?php
/**
 * [ADMIN] 設定入力
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
$hasAddableBlog = false;
if (count($blogContentDatas) > 0) {
	$hasAddableBlog = true;
}
?>
<?php if ($this->request->params['action'] === 'admin_add'): ?>
	<?php echo $this->BcForm->create('OptionalLinkConfig', array('url' => array('action' => 'add'))); ?>
<?php else: ?>
	<?php echo $this->BcForm->create('OptionalLinkConfig', array('url' => array('action' => 'edit'))); ?>
	<?php echo $this->BcForm->input('OptionalLinkConfig.id', array('type' => 'hidden')); ?>
	<?php echo $this->BcForm->input('OptionalLinkConfig.blog_content_id', array('type' => 'hidden')); ?>

<h2>
<?php $this->BcBaser->link($blogContentDatas[$this->request->data['OptionalLinkConfig']['blog_content_id']] .' ブログ設定編集はこちら', array(
	'admin' => true, 'plugin' => 'blog', 'controller' => 'blog_contents',
	'action' => 'edit', $this->request->data['OptionalLinkConfig']['blog_content_id']
)); ?>
&nbsp;&nbsp;&nbsp;&nbsp;
<?php $this->BcBaser->link('≫記事一覧こちら', array(
	'admin' => true, 'plugin' => 'blog', 'controller' => 'blog_posts',
	'action' => 'index', $this->request->data['OptionalLinkConfig']['blog_content_id']
)); ?>
</h2>
<?php endif ?>

<div id="OptionalLinkConfigConfigTable">
<table cellpadding="0" cellspacing="0" class="form-table section bca-form-table">
	<?php if($this->request->params['action'] != 'admin_add'): ?>
	<tr>
		<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('OptionalLinkConfig.id', 'NO'); ?></th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->value('OptionalLinkConfig.id'); ?>
		</td>
	</tr>
	<?php endif ?>

	<?php if($this->request->params['action'] == 'admin_add'): ?>
		<?php if ($hasAddableBlog): ?>
	<tr>
		<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('OptionalLinkConfig.blog_content_id', 'ブログ'); ?></th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('OptionalLinkConfig.blog_content_id', array('type' => 'select', 'options' => $blogContentDatas)); ?>
			<?php echo $this->BcForm->error('OptionalLinkConfig.blog_content_id'); ?>
		</td>
	</tr>
		<?php else: ?>
	<tr>
		<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('OptionalLinkConfig.blog_content_id', 'ブログ'); ?></th>
		<td class="col-input bca-form-table__input">
			追加設定可能なブログがありません。
		</td>
	</tr>
		<?php endif ?>
	<?php endif ?>

	<?php if ($hasAddableBlog): ?>
	<tr>
		<th class="col-head bca-form-table__label">
			<?php echo $this->BcForm->label('OptionalLinkConfig.status', 'オプショナルリンクの利用'); ?>
			<?php echo $this->BcBaser->img('admin/icn_help.png', array('id' => 'helpOptionalLinkConfigStatus', 'class' => 'btn help', 'alt' => 'ヘルプ')); ?>
			<div id="helptextOptionalLinkConfigStatus" class="helptext">
				<ul>
					<li>ブログ記事でのオプショナルリンクの利用の有無を指定します。</li>
				</ul>
			</div>
		</th>
		<td class="col-input bca-form-table__input">
			<?php echo $this->BcForm->input('OptionalLinkConfig.status', array('type' => 'radio', 'options' => $this->BcText->booleanDoList('利用'))); ?>
			<?php echo $this->BcForm->error('OptionalLinkConfig.status'); ?>
		</td>
	</tr>
	<?php endif; ?>
</table>
</div>

<?php if ($hasAddableBlog): ?>
<div class="submit bca-actions">
	<?php echo $this->BcForm->submit('保　存', array('div' => false, 'class' => 'button bca-btn bca-actions__item', 'data-bca-btn-type' => 'save', 'data-bca-btn-size' => 'lg', 'data-bca-btn-width' => 'lg')) ?>
</div>
<?php endif ?>
<?php echo $this->BcForm->end(); ?>
