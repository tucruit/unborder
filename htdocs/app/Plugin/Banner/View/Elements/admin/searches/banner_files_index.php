<?php
/**
 * [BANNER] バナーエリア管理
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<?php echo $this->BcForm->create('BannerFile', array('url' => array('action'=>'index', $bannerArea['BannerArea']['id']))) ?>
<p>
	<span style="margin-right: 20px;">
		<?php echo $this->BcForm->label('BannerFile.name', 'ファイル名') ?>
		&nbsp;<?php echo $this->BcForm->input('BannerFile.name', array('type' => 'text', 'size' => '20')) ?>
	</span>
	<span>
		<?php echo $this->BcForm->label('BannerFile.alt', 'alt') ?>
		&nbsp;<?php echo $this->BcForm->input('BannerFile.alt', array('type' => 'text', 'size' => '20')) ?>
	</span>
</p>
<p>
	<span style="margin-right: 20px;">
		<?php echo $this->BcForm->label('BannerFile.url', 'リンクURL') ?>
		&nbsp;<?php echo $this->BcForm->input('BannerFile.url', array('type' => 'text', 'size' => '20')) ?>
	</span>
	<span style="margin-right: 20px;">
		<?php echo $this->BcForm->label('BannerFile.status', '公開状態') ?>
		&nbsp;<?php echo $this->BcForm->input('BannerFile.status', array('type' => 'select', 'options' => $this->BcText->booleanMarkList(), 'empty' => '指定なし')) ?>
	</span>
	<span>
		<?php echo $this->BcForm->label('BannerFile.blank', '別窓で開く') ?>
		&nbsp;<?php echo $this->BcForm->input('BannerFile.blank', array('type' => 'select', 'options' => $this->BcText->booleanMarkList(), 'empty' => '指定なし')) ?>
	</span>
	<?php echo $this->BcSearchBox->dispatchShowField() ?>
</p>
<div class="button">
	<?php echo $this->BcForm->submit('admin/btn_search.png', array('alt' => '検索', 'class' => 'btn'), array('div' => false, 'id' => 'BtnSearchSubmit')) ?>
</div>
<?php echo $this->BcForm->end() ?>
