<?php
/**
 * [InstantPage] ユーザー管理
 *
 */
?>

<?php echo $this->BcForm->create('InstantPageTemplate', array('url' => array('action' => 'index'))) ?>
<p>
	<span><?php echo $this->BcForm->label('User.id', 'ユーザー名'); ?></span>
	<span>
		<?php
		echo $this->BcForm->input('User.id', ['type' => 'select', 'options' => $userDatas, 'escape' => true, 'empty' => __d('baser', '指定なし')]);
		?>
	</span>
</p>
<?php echo $this->BcSearchBox->dispatchShowField() ?>
<div class="button bca-search__btns">
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', '検索'), "javascript:void(0)", ['id' => 'BtnSearchSubmit', 'class' => 'bca-btn', 'data-bca-btn-type' => 'search']) ?></div>
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', 'クリア'), "javascript:void(0)", ['id' => 'BtnSearchClear', 'class' => 'bca-btn', 'data-bca-btn-type' => 'clear']) ?></div>
</div>
<?php echo $this->BcForm->end() ?>
