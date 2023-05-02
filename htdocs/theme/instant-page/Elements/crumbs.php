<?php
/**
 * [PUBLISH] ナビゲーション
 *
 * ページタイトルが直属のカテゴリ名と同じ場合は、直属のカテゴリ名を省略する
 */
if (!isset($separator)) {
	$separator = '&nbsp;&gt;&nbsp;';
}
if (!isset($home)) {
	$home = __d('baser', 'トップページ');
}
$crumbs = $this->BcBaser->getCrumbs();
if (!empty($crumbs)) {
	foreach($crumbs as $key => $crumb) {
		if ($this->BcArray->last($crumbs, $key)) {
			if ($this->viewPath != 'home' && $crumb['name']) {
				$this->BcBaser->addCrumb(h($crumb['name']));
			}
		} else {
			$this->BcBaser->addCrumb(h($crumb['name']), $crumb['url']);
		}
	}
} elseif (empty($crumbs)) {
	if ($this->name == 'CakeError') {
		$this->BcBaser->addCrumb('404 NOT FOUND');
	}
}
?>
<!-- BREAD CRUMBS -->
<div class="sub-breadcrumbs">
	<div class="l-subContentsContainer sub-breadcrumbsInner">
		<ol class="sub-breadcrumbs-list">
			<?php $this->BcBaser->crumbs('', $home, true) ?>
		</ol>
	</div>
</div>
<!-- /BREAD CRUMBS -->
