<?php
/**
 * [ADMIN] 検索欄
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */

?>
<?php echo $this->BcForm->create('OptionalLinkConfig', array('url' => array('action' => 'index'))); ?>
<p class="bca-search__input-list">
	<span class="bca-search__input-item">
		<?php echo $this->BcForm->label('OptionalLinkConfig.blog_content_id', 'ブログ', ['class' => 'bca-search__input-item-label']); ?>
		&nbsp;<?php echo $this->BcForm->input('OptionalLinkConfig.blog_content_id', array('type' => 'select', 'options' => $blogContentDatas)); ?>
	</span>
	<span class="bca-search__input-item">
		<?php echo $this->BcForm->label('OptionalLinkConfig.status', '利用状態', ['class' => 'bca-search__input-item-label']); ?>
		&nbsp;<?php echo $this->BcForm->input('OptionalLinkConfig.status', array('type' => 'select', 'options' => $this->BcText->booleanMarkList(), 'empty' => '指定なし')); ?>
	</span>
</p>
<div class="button bca-search__btns">
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', '検索'), "javascript:void(0)", ['id' => 'BtnSearchSubmit', 'class' => 'bca-btn bca-btn-lg', 'data-bca-btn-size'=>"lg"]) ?></div>
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', 'クリア'), "javascript:void(0)", ['id' => 'BtnSearchClear', 'class' => 'bca-btn']) ?></div>
</div>
<?php echo $this->BcForm->end(); ?>
