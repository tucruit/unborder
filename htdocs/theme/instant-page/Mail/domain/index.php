<?php
/**
 * ドメイン取得申請フォーム index
 */
?>
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader'); ?>
<!-- /SUB H1 -->
<!-- PAGE CONTENTS -->
<div class="signup signupIndex">
	<div class="l-subContentsContainer sub-container signupInner">
		<h2 class="mod-hl-01 signup-hl"><?php $this->BcBaser->contentsTitle(); ?>フォーム</h2>
		<p class="signup-lead">
			<?php echo nl2br(strip_tags($this->Mail->getDescription())) ?>
		</p>
		<?php $this->BcBaser->flash() ?>
		<?php $this->BcBaser->element('mail_advance/form_body_domain');?>
	</div>
</div>
<!-- /PAGE CONTENTS -->

