<?php
/**
 * [InstantPageTemplate] InstantPageTemplate 追加／編集
 */
if (isset($user['user_group_id']) && InstantPageUtil::isMemberGroup($user['user_group_id'])) {
	include __DIR__ . DS . '../mypage/form.php';
} else {

	$this->BcBaser->css('admin/ckeditor/editor', ['inline' => true]);
	$this->BcBaser->js('InstantPage.admin/edit', false);
	$this->BcBaser->js('InstantPage.admin/form', false, [
		// 'id' => 'AdminBlogBLogPostsEditScript',
		// 'data-fullurl' => $fullUrl,
		// 'data-previewurl' => $this->Blog->getPreviewUrl($url, $this->request->params['Site']['use_subdomain'])
	]);
	//$this->BcBaser->js('InstantPage.instant_page_templates', false);
	$editorOptions = [];
	$templates =[];
	if (!isset($this->request->data['InstantPageTemplate']['user_id'])) {
		$this->request->data['InstantPageTemplate']['user_id'] = $user['id'];
	}
$this->BcBaser->i18nScript([
	'message1' => __d('baser', "テーマをアップロードします。よろしいですか？")
]);
?>

<?php if ($this->action == 'admin_add'): //新規追加のみファイルアップロード ?>
	<script>
		$(function () {
			$("#BtnSave").click(function () {
				if (confirm(bcI18n.message1)) {
					$.bcUtil.showLoader();
					return true;
				}
				return false;
			});
		});
	</script>
<?php endif; ?>
	<?php
	if (isset($this->request->data['InstantPage']['instant_page_template_id'])) {
		$this->BcBaser->css(['InstantPage.origin'], ['inline' => true]);
	}
	?>
	<?php
	 if ($this->action == 'admin_add') {
	 	$url = [
			'controller' => 'instant_page_templates',
			'action' => 'add',
		];
	 } elseif ($this->action == 'admin_edit') {
	 	$url = [
			'controller' => 'instant_page_templates',
			'action' => 'edit',
			$this->BcForm->value('InstantPageTemplate.id'),
			'id' => false
		];
	 }
	echo $this->BcForm->create('InstantPageTemplate', [
		'type' => 'file',
		'url' => $url,
		'id' => 'PageForm'
	]);
	?>
	<?php echo $this->BcForm->input('InstantPageTemplate.id', ['type' => 'hidden']) ?>
	<?php echo $this->BcForm->input('InstantPageTemplate.mode', ['type' => 'hidden']) ?>

	<?php echo $this->BcFormTable->dispatchBefore() ?>
		<section id="BasicSetting" class="bca-section">
			<?php if ($this->action == 'admin_add'): //新規追加のみファイルアップロード ?>
				<h2>テーマファイル アップロード<br>（zip圧縮してください）</h2>
			<?php else:?>
				<h2>テーマ作成者変更</h2>
			<?php endif; ?>
			<table class="form-table bca-form-table" data-bca-table-type="type2">
				<?php /*<tr>
					<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageTemplate.name', 'URL') ?>
					&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
				</th>
				<td class="col-input bca-form-table__input">
					<?php echo $this->BcForm->input('InstantPageTemplate.name', ['type' => 'text', 'size' => 20, 'autofocus' => true, 'class' => 'bca-textbox__input nameCheck']) ?>
					<?php echo $this->BcForm->error('InstantPageTemplate.name') ?>
					<span class="bca-post__url">
						<?php //echo strip_tags($linkedFullUrl, '<a>') ?>
					</span>
				</td>
			</tr> */?>
			<?php if ($this->action == 'admin_add'): //新規追加のみファイルアップロード ?>
				<tr>
					<th class="col-head bca-form-table__label">
						<?php
						echo $this->BcForm->label('Theme.file', __d('baser', 'テーマファイル'));
						?>&nbsp;<span class="bca-label" data-bca-label-type="required"><?php echo __d('baser', '必須') ?></span>
					</th>
					<td class="col-input bca-form-table__input">
							<?php echo $this->BcForm->input('Theme.file', ['type' => 'file']) ?>　
							<i class="bca-icon--question-circle btn help bca-help"></i>
							<div id="helptextFile" class="helptext">
								<ul>
									<li>フォルダ名がテーマ名になります</li>
									<li>フォルダ名は半角英数字とハイフン、アンダースコアのみで入力してください。</li>
									<li>フォルダ直下に screenshot.png (300 × 240px)を配置しておくと、アイキャッチ画像になります。<br>
									<?php $this->BcBaser->img('/theme/instant-page/screenshot.png', array('width' => '50px')); ?></li>
									<li>フォルダ直下に css フォルダを設置し、bge_style.css を配置してください。<br>（こちらが編集エリア全体のスタイルシートになります）</li>
									<li>フォルダ直下に InstantPages フォルダを設置し、 detail.php を配置してください。</li>
									<li>用意したフォルダはzip圧縮してからアップロードしてください。</li>
								</ul>
							</div>
						<?php echo $this->BcForm->error('Theme.file') ?>
					</td>
				</tr>
			<?php endif; ?>
			<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageTemplate.user_id', __d('baser', '作成者')) ?></th>
			<td class="col-input bca-form-table__input">
				<?php if (BcUtil::isAdminUser()): ?>
					<?php echo $this->BcForm->input('InstantPageTemplate.user_id', ['type' => 'select', 'options' => $users]) ?>
				<?php else: ?>
					<?php echo h($this->BcText->arrayValue($this->BcForm->value('InstantPageTemplate.user_id'), $users)) ?>　
					<?php echo $this->BcForm->hidden('InstantPageTemplate.user_id') ?>
				<?php endif?>
				<?php echo $this->BcForm->error('InstantPageTemplate.user_id') ?>
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
		<?php echo $this->BcHtml->link(__d('baser', '一覧に戻る'), ['controller' => 'instant_page_templates', 'action' => 'index'], [
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
<?php
}
