<?php
/**
 * baserCMS :  Based Website Development Project <https://basercms.net>
 * Copyright (c) baserCMS Users Community <https://basercms.net/community/>
 *
 * @copyright       Copyright (c) baserCMS Users Community
 * @link			https://basercms.net baserCMS Project
 * @package         Baser.View
 * @since           baserCMS v 4.4.0
 * @license         https://basercms.net/license/index.html
 */

/**
 * ページネーション
 * 呼出箇所：サイト内検索結果一覧、ブログトップ、カテゴリ別ブログ記事一覧、タグ別ブログ記事一覧、年別ブログ記事一覧、月別ブログ記事一覧、日別ブログ記事一覧
 *
 * BcBaserHelper::pagination() で呼び出す
 * （例）<?php $this->BcBaser->pagination() ?>
 *
 * @var BcAppView $this
 */
if (empty($this->Paginator)) {
	return;
}
if (!isset($modules)) {
	$modules = 4;
}
?>

<?php if ((int) $this->Paginator->counter(['format' => '%pages%']) > 1): ?>
	<div class="mod-pagination-01">
		<?php echo $this->Paginator->prev(' ', ['class' => 'prev page-numbers'], null, ['class' => 'prev page-numbers dn']) ?>
		<?php echo $this->Html->tag('span', $this->Paginator->numbers(['separator' => '', 'class' => 'number page-numbers', 'modulus' => $modules]), ['class' => 'numberBox']) ?>
		<?php echo $this->Paginator->next(' ', ['class' => 'next page-numbers'], null, ['class' => 'next page-numbers dn']) ?>
	</div>
<?php endif; ?>