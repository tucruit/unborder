<?php
$this->BcBaser->setTitle(strip_tags($data['InstantPage']['title']));
$this->BcBaser->setDescription(strip_tags($data['InstantPage']['page_description']));
$this->BcBaser->setKeywords(strip_tags($data['InstantPage']['page_key_word']));
?>

<div role="main">
	<!-- BREAD CRUMBS -->
	<div class="sub-breadcrumbs">
		<div class="l-subContentsContainer sub-breadcrumbsInner">
			<ol class="sub-breadcrumbs-list">
				<li><a href="index.html">トップページ</a></li>
				<li><?php $this->BcBaser->contentsTitle() ?></li>
			</ol>
		</div>
	</div>
	<!-- /BREAD CRUMBS -->
	<!-- SUB H1 -->
	<div class="sub-h1">
		<div class="l-subContentsContainer sub-h1Inner">
			<h1 class="sub-h1-hl"><?php $this->BcBaser->contentsTitle() ?></h1>
		</div>
	</div>
	<!-- /SUB H1 -->

	<!-- PAGE CONTENTS -->
	<div class="<?php echo h($data['InstantPageUser']['User']['name']). ' '. h($data['InstantPage']['name']) ?> ">

		<div class="l-subContentsContainer sub-container sample">
			<?php echo $data['InstantPage']['contents'] ?>
		</div>
	</div>
	<!-- /PAGE CONTENTS -->
</div>
