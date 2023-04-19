<?php
/**
 * [BANNER] バナーエリア管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
$this->BcBaser->js(array(
	'admin/libs/jquery.baser_ajax_data_list',
	'admin/libs/jquery.baser_ajax_sort_table',
	'admin/libs/baser_ajax_data_list_config'
));
$this->BcAdmin->addAdminMainBodyHeaderLinks([
	'url' => ['action' => 'add'],
	'title' => __d('baser', '新規バナーエリア追加'),
]);
?>

<script type="text/javascript">
	$(function(){
		$.baserAjaxDataList.config.methods.del.confirm = 'このデータを本当に削除してもいいですか？';
		$.baserAjaxDataList.init();
		$.baserAjaxSortTable.init({ url: $("#AjaxSorttableUrl").html()});
	});
</script>

<div id="AlertMessage" class="message" style="display:none"></div>
<div id="DataList"><?php $this->BcBaser->element('banner_areas/index_list') ?></div>
