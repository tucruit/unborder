<?php
/**
 * [ADMIN] オプショナルリンク一覧
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
$this->BcBaser->js(array(
	'admin/libs/jquery.baser_ajax_data_list', 
	'admin/libs/jquery.baser_ajax_batch', 
	'admin/libs/baser_ajax_data_list_config',
	'admin/libs/baser_ajax_batch_config'
));
?>

<script type="text/javascript">
$(document).ready(function(){
	$.baserAjaxDataList.init();
	$.baserAjaxBatch.init({ url: $("#AjaxBatchUrl").html()});
});
</script>

<div id="AjaxBatchUrl" style="display:none"><?php $this->BcBaser->url(array('controller' => 'optional_links', 'action' => 'ajax_batch')); ?></div>
<div id="AlertMessage" class="message" style="display:none"></div>
<div id="DataList"><?php $this->BcBaser->element('optional_links/index_list'); ?></div>
