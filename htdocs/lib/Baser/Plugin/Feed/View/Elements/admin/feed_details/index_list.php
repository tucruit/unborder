<?php
/**
 * baserCMS :  Based Website Development Project <https://basercms.net>
 * Copyright (c) baserCMS Users Community <https://basercms.net/community/>
 *
 * @copyright       Copyright (c) baserCMS Users Community
 * @link            https://basercms.net baserCMS Project
 * @package         Feed.View
 * @since           baserCMS v 0.1.0
 * @license         https://basercms.net/license/index.html
 */

/**
 * [ADMIN] フィード設定一覧
 * @var \BcAppView $this
 */
$this->BcListTable->setColumnNumber(5);
?>


<?php if ($this->action != 'admin_add'): ?>

	<?php
	$this->BcBaser->js([
		'admin/libs/jquery.baser_ajax_data_list',
		'admin/libs/jquery.baser_ajax_batch',
		'admin/libs/baser_ajax_data_list_config',
		'admin/libs/baser_ajax_batch_config'
	]);
	?>

	<?php
	$this->BcBaser->i18nScript([
		'message1' => __d('baser', 'データがありません。'),
		'message2' => __d('baser', '削除に失敗しました。'),
	]);
	?>
	<script type="text/javascript">
		$(function () {
			$.baserAjaxBatch.config.methods.del.result = function () {
				var config = $.baserAjaxBatch.config;
				var colspan = $(config.targetCheckbox + ":checked:first").parent().parent().find('td').length;
				$(config.targetCheckbox + ":checked").parent().parent().fadeOut(300, function () {
					$(this).remove();
					if ($(config.listTable + " tbody td").length) {
						$.baserAjaxDataList.initList();
						$(config.listTable + " tbody tr").removeClass('even odd');
						$.yuga.stripe();
					} else {
						$(config.listTable + " tbody").append('<td colspan="' + colspan + '"><p class="no-data">' + bcI18n.message1 + '</p></td>');
					}
				});
			};
			$.baserAjaxDataList.config.methods.del.result = function (row, result) {
				var config = $.baserAjaxDataList.config;
				var colspan = $(config.dataList + " tbody td").length
				if (result) {
					row.fadeOut(300, function () {
						row.remove();
						if ($(config.dataList + " tbody td").length) {
							$.baserAjaxDataList.initList();
							$(config.dataList + " tbody tr").removeClass('even odd');
							$.yuga.stripe();
						} else {
							$(config.dataList + " tbody").append('<td colspan="' + colspan + '"><p class="no-data">' + bcI18n.message1 + '</p></td>');
						}
					});
				} else {
					$(config.alertBox).html(bcI18n.message2);
					$(config.alertBox).fadeIn(500);
				}
			};
			$.baserAjaxDataList.init();
			$.baserAjaxBatch.init({url: $("#AjaxBatchUrl").html()});
		});
	</script>

	<div id="AjaxBatchUrl"
		 style="display:none"><?php $this->BcBaser->url(['plugin' => 'feed', 'controller' => 'feed_details', 'action' => 'ajax_batch', $this->request->params['pass'][0]]) ?></div>

	<div class="section">

		<h2 id="headFeedDetail"><?php echo __d('baser', 'フィード一覧') ?></h2>

		<table cellpadding="0" cellspacing="0" class="list-table" id="ListTable">
			<thead>
			<tr>
				<th scope="col" style="width:160px" class="list-tool">
					<div>
						<?php $this->BcBaser->link($this->BcBaser->getImg('admin/btn_add.png', ['alt' => __d('baser', '新規追加')]) . __d('baser', '新規追加'), ['controller' => 'feed_details', 'action' => 'add', $this->BcForm->value('FeedConfig.id')]) ?>
						　
					</div>
					<?php if ($this->BcBaser->isAdminUser()): ?>
						<div>
							<?php echo $this->BcForm->checkbox('ListTool.checkall', ['title' => __d('baser', '一括選択')]) ?>
							<?php echo $this->BcForm->input('ListTool.batch', ['type' => 'select', 'options' => ['del' => __d('baser', '削除')], 'empty' => __d('baser', '一括処理')]) ?>
							<?php echo $this->BcForm->button(__d('baser', '適用'), ['id' => 'BtnApplyBatch', 'disabled' => 'disabled']) ?>
						</div>
					<?php endif ?>
				</th>
				<th scope="col"><?php echo __d('baser', 'フィード名') ?></th>
				<th scope="col"><?php echo __d('baser', 'カテゴリフィルター') ?></th>
				<th scope="col"><?php echo __d('baser', 'キャッシュ時間') ?></th>
				<?php echo $this->BcListTable->dispatchShowHead() ?>
				<th scope="col"><?php echo __d('baser', '登録日') ?><br/><?php echo __d('baser', '更新日') ?></th>
			</tr>
			</thead>
			<tbody>
			<?php if (!empty($feedConfig['FeedDetail'])): ?>
				<?php foreach($feedConfig['FeedDetail'] as $feedDetail): ?>
					<?php $this->BcBaser->element('feed_details/index_row', ['data' => $feedDetail]) ?>
				<?php endforeach; ?>
			<?php else: ?>
				<tr>
					<td colspan="<?php echo $this->BcListTable->getColumnNumber() ?>"><p
							class="no-data"><?php echo __d('baser', 'データが見つかりませんでした。「追加する」ボタンをクリックしてフィード詳細を登録してください。') ?></p>
					</td>
				</tr>
			<?php endif; ?>
			</tbody>
		</table>

	</div>

<?php endif ?>
