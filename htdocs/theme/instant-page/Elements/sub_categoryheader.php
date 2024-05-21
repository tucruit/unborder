<?php
	if (empty($title)) {
		$title = $this->BcBaser->getContentsTitle();
	}
?>

<div class="sub-h1">
	<div class="l-subContentsContainer sub-h1Inner">
		<h1 class="sub-h1-hl"><?php echo $title; ?></h1>
	</div>
</div>