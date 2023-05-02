<?php
/**
 * メールフォーム index
 */
?>
<!-- PAGE CONTENTS -->
<div class="signup signupIndex">
	<div class="l-subContentsContainer sub-container signupInner">
		<h2 class="mod-hl-01 signup-hl"><?php $this->BcBaser->contentsTitle(); ?>フォーム</h2>
		<p class="signup-lead">
			<?php echo nl2br(strip_tags($this->Mail->getDescription())) ?>
		</p>
		<?php $this->BcBaser->flash() ?>
		<?php $this->BcBaser->element('mail_advance/form_body');?>
	</div>
</div>
<!-- /PAGE CONTENTS -->

