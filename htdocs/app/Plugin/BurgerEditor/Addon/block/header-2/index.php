<?php
/**
 * BurgerEditor <baserCMS plugin>
 *
 * @copyright		Copyright 2013 - 2015, D-ZERO Co.,LTD.
 * @link			http://d-zero.com/
 * @package			burger_editor
 * @since			Baser v 3.0.0
 * @license			http://barket.jp/files/baser_market_license.pdf
 */
?>
<header data-bgb="text-image2">
    <div class="bgt-grid bgt-grid--first bgt-grid6" data-bge-grid-changeable>
		<?php $this->BurgerEditor->type('ckeditor') ?>
	</div>
	<div class="bgt-grid bgt-grid--last  bgt-grid2" data-bge-grid-changeable>
		<?php $this->BurgerEditor->type('image') ?>
	</div>
</header>
