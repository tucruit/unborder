<?php
/**
 * [InstantPage] ユーザー管理
 *
 */
$userRealNmaes = Hash::combine($users, '{n}.InstantPageUser.id', '{n}.User.real_name_1');
?>

<?php echo $this->BcForm->create('InstantPage', array('url' => array('action' => 'index'))) ?>
<p>
	<span><?php echo $this->BcForm->label('InstantPageUser.id', 'ユーザー名'); ?></span>
	<span>
		<?php
		echo $this->BcForm->input('InstantPageUser.id', ['type' => 'select', 'options' => $userRealNmaes, 'escape' => true, 'empty' => __d('baser', '指定なし')]);
		?>
	</span>
</p>
<?php /*
<p>
	<span>
		<?php echo $this->BcForm->label('InstantPageUser.prefecture_id', '都道府県') ?>
		<?php echo $this->BcForm->input('InstantPageUser.prefecture_id', ['type' => 'select', 'options' => $this->BcText->prefList(), 'escape' => true, 'empty' => __d('baser', '指定なし')]) ?>
	</span>　
</p>
*/?>
<?php echo $this->BcSearchBox->dispatchShowField() ?>
<div class="button bca-search__btns">
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', '検索'), "javascript:void(0)", ['id' => 'BtnSearchSubmit', 'class' => 'bca-btn', 'data-bca-btn-type' => 'search']) ?></div>
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', 'クリア'), "javascript:void(0)", ['id' => 'BtnSearchClear', 'class' => 'bca-btn', 'data-bca-btn-type' => 'clear']) ?></div>
</div>
<?php echo $this->BcForm->end() ?>
