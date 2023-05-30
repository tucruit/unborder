<?php
/**
 * [BANNER] バナーエリア管理　行
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<tr<?php $this->BcListTable->rowClass($this->Banner->allowPublish($data), $data, ['class' => ['sortable']]) ?>>
	<td class="row-tools bca-table-listup__tbody-td">
		<?php if ($sortmode): ?>
			<span class="sort-handle"><i class="bca-btn-icon-text" data-bca-btn-type="draggable"></i><?php echo __d('baser', 'ドラッグ可能') ?></span>
			<?php echo $this->BcForm->input('Sort.id'.$data['BannerFile']['id'], array('type'	=> 'hidden', 'class' => 'id', 'value' => $data['BannerFile']['id'])); ?>
		<?php endif; ?>
	</td>
	<td class="bca-table-listup__tbody-td"><?php echo $data['BannerFile']['no']; ?></td>
	<td class="eye_catch bca-table-listup__tbody-td">
		<?php if (Hash::get($data, 'BannerFile.name')): ?>
		<?php echo $this->BcUpload->uploadImage('BannerFile.name', $data['BannerFile']['name'], array(
								'imgsize'	=>'thumb',
								'alt'		=> $data['BannerFile']['name'],
								'rel'		=> 'colorbox',
								'style'		=> 'max-width: 200px')
		) ?>
		<br />
		<?php echo $this->BcBaser->link($data['BannerFile']['name'], array('action' => 'edit', $bannerArea['BannerArea']['id'], $data['BannerFile']['id']), array('title' => '編集', 'escape' => true)) ?>
		<?php endif; ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['BannerFile']['alt']); ?>
		<br />
		<?php echo $this->Banner->judgeTargetBlank($data['BannerFile']['blank'], array('tag' => 'span'), array('class' => 'tag')) ?>
		<?php echo h($data['BannerFile']['url']); ?>
		<?php $publishTerm = $this->Banner->judgePublishTerm($data, array('tag' => 'span'), array('class' => 'tag')); ?>
		<?php if ($publishTerm): ?>
			<br />
			<?php echo $this->Banner->judgePublishTerm($data, array('tag' => 'span'), array('class' => 'tag')) ?>
			<small>
				<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['BannerFile']['publish_begin']) ?>
				&nbsp;〜&nbsp;
				<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['BannerFile']['publish_end']) ?>
			</small>
		<?php endif; ?>
	</td>
	<?php echo $this->BcListTable->dispatchShowRow($data) ?>
	<td class="bca-table-listup__tbody-td" style="white-space: nowrap">
		<?php echo $this->BcTime->format('Y-m-d', $data['BannerFile']['created']) ?>
		<br />
		<?php echo $this->BcTime->format('Y-m-d', $data['BannerFile']['modified']) ?>
	</td>
	<td class="row-tools bca-table-listup__tbody-td">
		<?php $this->BcBaser->link('',
				array('action' => 'ajax_unpublish', $bannerArea['BannerArea']['id'], $data['BannerFile']['id']), array('title' => '非公開', 'class' => 'btn-unpublish bca-btn-icon', 'data-bca-btn-type' => 'unpublish', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('action' => 'ajax_publish', $bannerArea['BannerArea']['id'], $data['BannerFile']['id']), array('title' => '公開', 'class' => 'btn-publish bca-btn-icon', 'data-bca-btn-type' => 'publish', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('action' => 'edit', $bannerArea['BannerArea']['id'], $data['BannerFile']['id']), array('title' => '編集', 'class' => 'btn-edit bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('action' => 'ajax_copy', $bannerArea['BannerArea']['id'], $data['BannerFile']['id'], 'sortmode' => $sortmode), array('title' => 'コピー', 'class' => 'btn-copy bca-btn-icon', 'data-bca-btn-type' => 'copy', 'data-bca-btn-size' => 'lg')) ?>
		<?php $this->BcBaser->link('',
				array('action' => 'ajax_delete', $bannerArea['BannerArea']['id'], $data['BannerFile']['id']), array('title' => '削除', 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg')) ?>
	</td>
</tr>
