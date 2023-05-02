<?php
/**
 * メールフォーム confirm
 */
?>
<!-- PAGE CONTENTS -->
<div class="signup signupIndex">
	<div class="l-subContentsContainer sub-container signupInner">
		<h2 class="mod-hl-01 signup-hl"><?php $this->BcBaser->contentsTitle(); ?>フォーム</h2>
		<?php if ($freezed):?>
			<p class="signup-lead">
				<?php echo nl2br(strip_tags($this->Mail->getDescription())) ?>
			</p>
		<?php endif;?>
		<?php $this->BcBaser->flash() ?>
		<?php
		if ($freezed) $this->ThemeMail->freeze();
		$this->BcBaser->element('mail_advance/form_body');
		?>
	</div>
</div>
<!-- /PAGE CONTENTS -->
