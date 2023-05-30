<?php
/**
 * メールフォーム confirm
 */
?>
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader'); ?>
<!-- /SUB H1 -->
<!-- PAGE CONTENTS -->
<div class="signup signupIndex">
	<div class="l-subContentsContainer sub-container signupInner">
		<?php if (!$freezed):?>
			<h2 class="mod-hl-01 signup-hl"><?php $this->BcBaser->contentsTitle(); ?>フォーム</h2>
			<p class="signup-lead"><?php echo nl2br(strip_tags($this->Mail->getDescription())) ?></p>
		<?php else:?>
			<h2 class="mod-hl-01 signup-hl"><?php $this->BcBaser->contentsTitle(); ?> 確認</h2>
			<p class="signup-lead"><?php echo __('入力した内容に間違いがなければ「送信する」ボタンをクリックしてください。') ?></p>
		<?php endif;?>
		<?php $this->BcBaser->flash() ?>
		<?php
		if ($freezed) $this->ThemeMail->freeze();
		$this->BcBaser->element('mail_advance/form_body');
		?>
	</div>
</div>
<!-- /PAGE CONTENTS -->
