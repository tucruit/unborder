<?php
/**
 * [BANNER] バナー管理
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
	'url' => ['action' => 'add', $this->request->params['pass'][0]],
	'title' => __d('baser', '新規バナー追加'),
]);
?>

<script type="text/javascript">
	$(function(){
		$.baserAjaxDataList.config.methods.del.confirm = 'このデータを本当に削除してもいいですか？';
		$.baserAjaxDataList.init();
		$.baserAjaxSortTable.init({ url: $("#AjaxSorttableUrl").html()});
	});
</script>

<div id="AjaxSorttableUrl" style="display:none"><?php $this->BcBaser->url(array('plugin' => 'banner', 'controller' => 'banner_files', 'action' => 'ajax_update_sort', $bannerArea['BannerArea']['id'])) ?></div>
<div id="AlertMessage" class="message" style="display:none"></div>
<div id="MessageBox" style="display:none"><div id="flashMessage" class="notice-message"></div></div>
<div id="DataList"><?php $this->BcBaser->element('banner_files/index_list') ?></div>
