<?php
/**
 * [ADMIN] オプショナルリンク一覧: 行
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
$classies = array();
if ($this->OptionalLink->allowPublish($data)) {
	$classies = array('publish');
} else {
	$classies = array('unpublish', 'disablerow');
}
$class = ' class="' . implode(' ', $classies) . '"';

?>
<tr<?php echo $class; ?>>
	<td class="bca-table-listup__tbody-td" style="width: 45px;">
		<?php echo $this->BcBaser->link($data['OptionalLink']['id'], array('action' => 'edit', $data['OptionalLink']['id']), array('title' => '編集')) ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php
		if (isset($blogContentDatas[$data['OptionalLink']['blog_content_id']])) {
			echo $blogContentDatas[$data['OptionalLink']['blog_content_id']];
		} else {
			echo '削除ブログ';
		}
		?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $data['OptionalLink']['name'] ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $this->BcText->booleanDo($data['OptionalLink']['blank'], '指定') ?>
	</td>
	<td class="bca-table-listup__tbody-td" style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['OptionalLink']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['OptionalLink']['modified']) ?>
	</td>
	<td class="row-tools bca-table-listup__tbody-td">
		<?php
		// 設定済み記事一覧のコントロールと、オプショナルリンクのオンオフが混ざっててインターフェース的に見ずらいためブログ記事への導線以外のボタンをコメントアウトします
		/*
		$this->BcBaser->link(
			'',
			array('action' => 'ajax_unpublish', $data['OptionalLink']['id']),
			array('title' => '無効', 'class' => 'btn-unpublish bca-btn-icon', 'data-bca-btn-type' => 'unpublish', 'data-bca-btn-size' => 'lg')
		); ?>
		<?php $this->BcBaser->link(
			'',
			array('action' => 'ajax_publish', $data['OptionalLink']['id']),
			array('title' => '有効', 'class' => 'btn-publish bca-btn-icon', 'data-bca-btn-type' => 'publish', 'data-bca-btn-size' => 'lg')
		);
		*/ ?>
		<?php // ブログ記事編集画面へ移動  ?>
		<?php

		if (isset($blogContentDatas[$data['OptionalLink']['blog_content_id']])) {
			$this->BcBaser->link(
				'',
				array('admin' => true, 'plugin' => 'blog', 'controller' => 'blog_posts', 'action' => 'edit', $data['OptionalLink']['blog_content_id'], $data['OptionalLink']['blog_post_id']),
				array('title' => 'ブログ記事編集', 'class' => 'bca-btn-icon', 'data-bca-btn-type' => 'preview', 'data-bca-btn-size' => 'lg')
			);
	 	} ?>
		<?php
		/*
		$this->BcBaser->link(
			'',
			array('action' => 'edit', $data['OptionalLink']['id']),
			array('title' => '編集', 'class' => 'btn-edit bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg')
		); ?>
		<?php $this->BcBaser->link(
			'',
			array('action' => 'ajax_delete', $data['OptionalLink']['id']),
			array('title' => '削除', 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg')
		);
		*/ ?>
	</td>
</tr>
