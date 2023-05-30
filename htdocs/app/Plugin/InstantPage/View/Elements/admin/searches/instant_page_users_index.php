<?php
/**
 * [InstantPage] ユーザー管理
 *
 */
?>
<?php echo $this->BcForm->create('InstantPageUser', array('url' => array('action' => 'index'))) ?>
<p>
	<span>
		<?php
		echo $this->BcForm->label('User.name', 'ログインID');
		echo "&nbsp;";
		echo $this->BcForm->input('User.name', ['type' => 'text', 'size' => '30']);
		?>
	</span>
	<span>
		<?php
		echo $this->BcForm->label('InstantPageUser.company', '会社名');
		echo "&nbsp;";
		echo $this->BcForm->input('InstantPageUser.company', ['type' => 'text', 'size' => '30']);
		?>
	</span>

	<span>
		<?php echo $this->BcForm->label('User.real_name_1', 'お名前（姓）') ?>
		<?php echo $this->BcForm->input('User.real_name_1', ['type' => 'text', 'size' => '30']) ?>
	</span>　
</p>
<p>
	<span>
		<?php echo $this->BcForm->label('User.email', 'メールアドレス') ?>
		<?php echo $this->BcForm->input('User.email', ['type' => 'text', 'size' => '30']) ?>
	</span>　
	<span>
		<?php echo $this->BcForm->label('InstantPageUser.prefecture_id', '都道府県') ?>
		<?php echo $this->BcForm->input('InstantPageUser.prefecture_id', ['type' => 'select', 'options' => $this->BcText->prefList(), 'escape' => true, 'empty' => __d('baser', '指定なし')]) ?>
	</span>　
</p>
<div class="button bca-search__btns">
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', '検索'), "javascript:void(0)", ['id' => 'BtnSearchSubmit', 'class' => 'bca-btn', 'data-bca-btn-type' => 'search']) ?></div>
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', 'クリア'), "javascript:void(0)", ['id' => 'BtnSearchClear', 'class' => 'bca-btn', 'data-bca-btn-type' => 'clear']) ?></div>
</div>
<?php echo $this->Form->end() ?>
