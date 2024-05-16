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
 * パーツ用ブログ記事一覧
 * 呼出箇所：トップページ
 *
 * BcBaserHelper::blogPosts( コンテンツ名, 件数 ) で呼び出す
 * （例）<?php $this->BcBaser->blogPosts('news', 3) ?>
 *
 * @var BcAppView $this
 * @var array $posts ブログ記事リスト
 */
?>


<?php if ($posts) : ?>
	<?php foreach ($posts as $key => $post) : ?>
		<article class="articleBox">
			<a href="<?php echo $this->Blog->getPostLinkUrl($post); ?>" class="articleBoxInner">
				<div class="articleBox-dateGroup">
					<time class="articleBox-date" datetime="<?php $this->Blog->postDate($post, 'Y-m-d'); ?>"><?php $this->Blog->postDate($post, 'Y年m月d日'); ?></time>
					<?php if(!empty($this->Blog->getCategory($post, ['link' => false]))): ?>
						<div class="articleBox-catWrap"><span class="mod-catTag articleBox-cat"><?php $this->Blog->category($post,  ['link' => false]) ?></span></div>
					<?php endif; ?>
				</div>
				<h3 class="articleBox-hl"><?php $this->Blog->postTitle($post, false) ?></h3>
			</a>
		</article>
	<?php endforeach ?>
	<a href="<?php echo $post['BlogContent']['Content']['url']; ?>" class="top-btn top-news-moreBtn">
		<span class="btnInner">すべてのお知らせを見る</span>
	</a>
<?php else : ?>
	<div class="articleBox">
		<p class="top-news-noData"><?php echo __('記事がありません。'); ?></p>
	</div>
<?php endif ?>