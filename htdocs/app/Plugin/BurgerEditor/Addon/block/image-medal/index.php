<?php
/**
 * BurgerEditor <baserCMS plugin>
 *
 * @copyright		Copyright 2013 -, D-ZERO Co.,LTD.
 * @link			https://www.d-zero.co.jp/
 * @package			burger_editor
 * @since			Baser v 3.0.0
 * @license			https://market.basercms.net/files/baser_market_license.pdf
 */
?>
<div class="image-medal">
	<?php $this->BurgerEditor->type('title-transparent') ?>
	<div class="text-wrap">
		<?php $this->BurgerEditor->type('ckeditor') ?>
	</div>
	<div class="medal-wrap">
		<div class="medal-wrap__inner">
			<?php $this->BurgerEditor->type('medal') ?>
			<?php $this->BurgerEditor->type('ckeditor') ?>
		</div>
		<div class="medal-wrap__inner">
			<?php $this->BurgerEditor->type('medal') ?>
			<?php $this->BurgerEditor->type('ckeditor') ?>
		</div>
		<div class="medal-wrap__inner">
			<?php $this->BurgerEditor->type('medal') ?>
			<?php $this->BurgerEditor->type('ckeditor') ?>
		</div>
	</div>
</div>
