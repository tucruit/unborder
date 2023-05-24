<?php
/**
 * baserCMS :  Based Website Development Project <https://basercms.net>
 * Copyright (c) baserCMS Users Community <https://basercms.net/community/>
 *
 * @copyright       Copyright (c) baserCMS Users Community
 * @link            https://basercms.net baserCMS Project
 * @package         Baser.View
 * @since           baserCMS v 4.4.0
 * @license         https://basercms.net/license/index.html
 */

/**
 * [PUBLISH] ナビゲーション
 *
 * ページタイトルが直属のカテゴリ名と同じ場合は、直属のカテゴリ名を省略する
 */
if (!isset($separator)) {
	$separator = '';
}
if (!isset($home)) {
	$home = __d('baser', 'トップページ');
}
$crumbs = $this->BcBaser->getCrumbs();
if (!empty($crumbs)) {
	foreach($crumbs as $key => $crumb) {
		// categoryページ以外を出力
		if(!strpos($crumb['url'],'/archives/category/')) {
			if ($this->BcArray->last($crumbs, $key)) {
				if ($this->viewPath != 'home' && $crumb['name']) {
					$this->BcBaser->addCrumb(h($crumb['name']));
				}
			} else {
				$this->BcBaser->addCrumb(h($crumb['name']), $crumb['url']);
			}
		}

	}
} elseif (empty($crumbs)) {
	if ($this->name == 'CakeError') {
		$this->BcBaser->addCrumb('404 NOT FOUND');
	}
}
?>

<?php if (empty($onSchema)): ?>
	<?php
	if ($this->BcBaser->isHome()) {
		echo $home;
	} else {
		$this->BcBaser->crumbs($separator, $home);
	}
	?>
<?php else: ?>
	<div class="sub-breadcrumbs">
		<div class="l-subContentsContainer sub-breadcrumbsInner">
			<ol class="sub-breadcrumbs-list" itemscope itemtype="http://schema.org/BreadcrumbList">
				<?php if ($this->BcBaser->isHome()): ?>
					<li itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem"><span
							itemprop="name"><?php echo $home ?></span>
						<meta itemprop="position" content="1"/>
					</li>
				<?php else: ?>
					<?php $this->BcBaser->crumbs($separator, $home, true) ?>
				<?php endif ?>
			</ol>
		</div>
	</div>
<?php endif ?>
