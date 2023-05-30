<?php
/**
 * [ADMIN] 設定一覧: 行
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
$classies = array();
if (!$this->OptionalLink->allowPublish($data)) {
	$classies = array('unpublish', 'disablerow');
} else {
	$classies = array('publish');
}
$class = ' class="' . implode(' ', $classies) . '"';

?>
<tr<?php echo $class; ?>>
	<td class="bca-table-listup__tbody-td" style="width: 45px;"><?php echo $data['OptionalLinkConfig']['id']; ?></td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $this->BcBaser->link($blogContentDatas[$data['OptionalLinkConfig']['blog_content_id']], array('action' => 'edit', $data['OptionalLinkConfig']['id']), array('title' => '編集')) ?>
	</td>
	<td class="bca-table-listup__tbody-td" style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['OptionalLinkConfig']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['OptionalLinkConfig']['modified']) ?>
	</td>
	<td class="row-tools bca-table-listup__tbody-td">
		<?php $this->BcBaser->link('',
				array('action' => 'ajax_unpublish', $data['OptionalLinkConfig']['id']), array('title' => '非公開', 'class' => 'btn-unpublish bca-btn-icon', 'data-bca-btn-type' => 'unpublish', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('action' => 'ajax_publish', $data['OptionalLinkConfig']['id']), array('title' => '公開', 'class' => 'btn-publish bca-btn-icon', 'data-bca-btn-type' => 'publish', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('admin' => true, 'plugin' => 'blog', 'controller' => 'blog_contents', 'action' => 'edit', $data['OptionalLinkConfig']['blog_content_id']), array('title' => 'ブログ確認', 'class' => 'bca-btn-icon', 'data-bca-btn-type' => 'preview', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('action' => 'edit', $data['OptionalLinkConfig']['id']), array('title' => '編集', 'class' => 'btn-edit bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('action' => 'ajax_delete', $data['OptionalLinkConfig']['id']), array('title' => '削除', 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg')) ?>
	</td>
</tr>
