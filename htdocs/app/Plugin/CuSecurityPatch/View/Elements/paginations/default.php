<?php
/**
 * baserCMS :  Based Website Development Project <https://basercms.net>
 * Copyright (c) baserCMS Users Community <https://basercms.net/community/>
 *
 * @copyright       Copyright (c) baserCMS Users Community
 * @link            https://basercms.net baserCMS Project
 * @package         Baser.View
 * @since           baserCMS v 0.1.0
 * @license         https://basercms.net/license/index.html
 */

/**
 * [PUBLISH] ページネーション標準
 *
 * $this->BcBaser->pagination(modulus) で呼び出す
 */
echo 'a';
if (empty($this->Paginator)) {
	return;
}
echo 'b';
if (!isset($modulus)) {
	$modulus = 8;
}
if (!isset($options)) {
	$options = [];
}
$pageCount = 0;
if (isset($this->Paginator->params['paging'][$this->Paginator->defaultModel()]['pageCount'])) {
	echo 'c';
	$pageCount = $this->Paginator->params['paging'][$this->Paginator->defaultModel()]['pageCount'];
}
?>

<div class="pagination">
	<div class="pagination-result">
		<?php echo $this->Paginator->counter(['format' => '結果：　%start%～%end% 件 ／ 総件数：　%count% 件']) ?>
	</div>
	<div class="pagination-numbers">
		<?php echo $this->Paginator->first('|<') ?>　
		<?php echo $this->Paginator->prev('<<', null, null, ['class' => ['disabled', 'number'], 'tag' => 'span']) ?>　
		<?php echo $this->Paginator->numbers() ?>　
		<?php echo $this->Paginator->next('>>', null, null, ['class' => 'disabled', 'tag' => 'span']) ?>　
		<?php echo $this->Paginator->last('>|') ?>
	</div>
</div>

<nav class="c-pagination" aria-label="ページ番号">
	<div class="c-pagination__prev">
		<a data-disabled="true">
			prev
		</a>
	</div>
	<div class="c-pagination__next">
		<a href="___NEXT_PAGE___" rel="next">
			next
		</a>
	</div>
	<ol class="c-pagination__numbers">
		<li class="c-pagination__number"><a aria-current="page">1</a></li>
		<li class="c-pagination__number"><a href="___NEXT_PAGE___">2</a></li>
		<li class="c-pagination__number"><a href="___NEXT_PAGE___">3</a></li>
		<li class="c-pagination__number"><a href="___NEXT_PAGE___">4</a></li>
	</ol>
</nav>

