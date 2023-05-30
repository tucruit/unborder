<?php
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
<div class="bs-pagination">
	<?php echo $this->Paginator->prev('< '. __('前へ'), ['class' => 'bs-pagination__prev'], null, ['class' => 'bs-pagination__prev disabled']) ?>
	<?php echo $this->Html->tag('span', $this->Paginator->numbers(['separator' => '', 'class' => 'bs-pagination__number', 'modulus' => $modules])) ?>
	<?php echo $this->Paginator->next(__('次へ'). ' >', ['class' => 'bs-pagination__next'], null, ['class' => 'bs-pagination__next disabled']) ?>
</div>
<?php endif; ?>
