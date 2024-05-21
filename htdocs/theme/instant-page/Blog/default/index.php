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
 * ブログトップ
 * 呼出箇所：ブログトップ
 *
 * @var BcAppView $this
 * @var array $posts ブログ記事リスト
 */
$this->BcBaser->setDescription($this->Blog->getDescription());
?>

<!-- BREAD CRUMBS -->
<?php $this->BcBaser->crumbsList(['onSchema' => true]); ?>
<!-- /BREAD CRUMBS -->
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader'); ?>
<!-- /SUB H1 -->

<!-- PAGE CONTENTS -->
<div class="news newsList">
	<div class="sub-container">
		<div class="l-subContentsContainer l-2ColumnContainer">
			<!-- l-main -->
			<div class="l-main">
				<div class="newsList-articleBoxContainer">
					<?php if (!empty($posts)) : ?>
						<?php foreach ($posts as $post) : ?>
							<?php
								$category = $this->Blog->getCategory($post, ['link' => false]);
							?>
							<!-- BOX -->
							<article class="articleBox">
								<a href="<?php echo $this->Blog->getPostLinkUrl($post) ?>" class="articleBoxInner">
									<div class="articleBox-dateGroup">
										<time class="articleBox-date" datetime="<?php $this->Blog->postDate($post, 'Y-m-d') ?>"><?php $this->Blog->postDate($post, 'Y年m月d日') ?></time>
										<div class="articleBox-catWrap">
											<?php if (!empty($category)): ?>
												<span class="mod-catTag articleBox-cat"><?php echo $category; ?></span>
											<?php endif; ?>
										</div>
									</div>
									<h3 class="articleBox-hl"><?php $this->Blog->postTitle($post, false); ?></h3>
								</a>
							</article>
							<!-- /BOX -->
						<?php endforeach; ?>
					<?php else : ?>
						<p class="bs-blog-no-data"><?php echo __('記事がありません。'); ?></p>
					<?php endif ?>
				</div>
				<!-- PAGINATION -->
				<?php $this->BcBaser->pagination('simple'); ?>
				<!-- /PAGINATION -->
			</div>
			<!-- /l-main -->
			<!-- l-sub -->
			<?php $this->BcBaser->element('sub_side'); ?>
			<!-- /l-sub -->
		</div>
	</div>
</div>
<!-- /PAGE CONTENTS -->