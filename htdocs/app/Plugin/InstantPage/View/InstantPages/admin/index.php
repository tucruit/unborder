<?php
/**
 * [ADMIN] パートナー企業設定一覧
 */
if (isset($user['user_group_id']) && InstantPageUtil::isMemberGroup($user['user_group_id'])) {
	include __DIR__ . DS . '../mypage/index.php';
} else {
$this->BcBaser->js([
	'admin/libs/jquery.baser_ajax_data_list',
	'admin/libs/jquery.baser_ajax_batch',
	'admin/libs/baser_ajax_data_list_config',
	'admin/libs/baser_ajax_batch_config'
]);
// 新規作成ボタン
$this->BcAdmin->addAdminMainBodyHeaderLinks([
	'url' => ['action' => 'add'],
	'title' => __d('baser', '新規追加'),
]);
?>

<script type="text/javascript">
	$(document).ready(function () {
		$.baserAjaxDataList.init();
		$.baserAjaxBatch.init({url: $("#AjaxBatchUrl").html()});
	});
</script>
<?php /*
<div class="panel-box bca-panel-box" id="FunctionBox">
	<?php echo $this->BcForm->create('Function', ['type' => 'get', 'url' => ['controller' => 'instant_pages', 'action' => 'download_csv', ]]) ?>
	<?php echo $this->BcForm->input('Function.encoding', ['type' => 'radio', 'options' => ['UTF-8' => 'UTF-8', 'SJIS-win' => 'SJIS'], 'value' => 'UTF-8']) ?>&nbsp;&nbsp;
	<?php echo $this->BcForm->input('Function.conditions', ['type' => 'hidden']) ?>
	<?php echo $this->BcForm->submit(__d('baser', 'CSVダウンロード'), ['div' => false, 'class' => 'button-small', 'id' => 'SearchMerge']) ?>
	<?php echo $this->BcForm->end() ?>
</div>
*/?>
<div id="AjaxBatchUrl"
	 hidden><?php $this->BcBaser->url(['controller' => 'instant_pages', 'action' => 'ajax_batch']) ?></div>
<div id="AlertMessage" class="message" hidden></div>
<div id="MessageBox" hidden>
	<div id="flashMessage" class="notice-message"></div>
</div>
<div id="DataList" class="bca-data-list"><?php $this->BcBaser->element('instant_pages/index_list') ?></div>

<?php
}
