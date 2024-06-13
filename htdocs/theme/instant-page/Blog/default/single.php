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
 * ブログ記事詳細ページ
 * 呼出箇所：ブログ記事詳細ページ
 *
 * @var BcAppView $this
 * @var array $post ブログ記事データ
 */
$this->BcBaser->setDescription($this->Blog->getTitle() . '｜' . $this->Blog->getPostContent($post, false, false, 50));
?>

<!-- BREAD CRUMBS -->
<?php $this->BcBaser->crumbsList(['onSchema' => true]); ?>
<!-- /BREAD CRUMBS -->
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader', ['title' => $this->Blog->getTitle()]); ?>
<!-- /SUB H1 -->

<!-- PAGE CONTENTS -->
<div class="news newsSingle">
	<div class="sub-container">
		<div class="l-subContentsContainer l-2ColumnContainer">
			<!-- l-main -->
			<div class="l-main">
				<div class="newsSingle-header">
					<div class="newsSingle-header-dateGroup">
						<time class="dateGroup-date" datetime="<?php $this->Blog->postDate($post, 'Y-m-d') ?>"><?php $this->Blog->postDate($post, 'Y年m月d日') ?></time>
						<?php if(!empty($this->Blog->getCategoryTitle($post))): ?>
							<span class="mod-catTag dateGroup-cat"><?php echo $this->Blog->getCategoryTitle($post); ?></span>
						<?php endif; ?>
					</div>
					<h1 class="mod-hl-01 newsSingle-header-hl"><?php $this->BcBaser->contentsTitle() ?></h1>
				</div>
				<div class="newsSingle-body" id="post-detail">
					<?php $this->Blog->postContent($post) ?>
				</div>
				<a href="<?php echo $post['BlogContent']['Content']['url']; ?>" class="mod-btn-01 news-buckBtn">
					<span class="btnInner">お知らせ一覧に戻る</span>
				</a>
			</div>
			<!-- /l-main -->
			<!-- l-sub -->
			<?php $this->BcBaser->element('sub_side'); ?>
			<!-- /l-sub -->
		</div>
	</div>
</div>
<!-- /PAGE CONTENTS -->