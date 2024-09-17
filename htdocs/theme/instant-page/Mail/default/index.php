<?php

/**
 * baserCMS :  Based Website Development Project <https://basercms.net>
 * Copyright (c) baserCMS Users Community <https://basercms.net/community/>
 *
 * @copyright        Copyright (c) baserCMS Users Community
 * @link            https://basercms.net baserCMS Project
 * @package            Baser.View
 * @since            baserCMS v 4.4.0
 * @license            https://basercms.net/license/index.html
 */

/**
 * メールフォーム
 * 呼出箇所：メールフォーム
 *
 * @var BcAppView $this
 */
?>
<!-- BREAD CRUMBS -->
<?php $this->BcBaser->crumbsList(['onSchema' => true]); ?>
<!-- /BREAD CRUMBS -->
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader'); ?>
<!-- /SUB H1 -->

<!-- PAGE CONTENTS -->
<div class="contact contactIndex">
	<div class="l-subContentsContainer sub-container contactInner">
		<h2 class="mod-hl-01 contact-hl">お問い合わせフォーム</h2>
		<p class="contact-lead">
			こちらのフォームよりお気軽にお問い合わせください。担当者より返答させていただきます。
		</p>
		<div class="contact-form">
			<?php $this->BcBaser->flash() ?>
			<?php $this->BcBaser->element('mail_form') ?>
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="UTF-8"></script>
			<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
			<script type="text/javascript" src="/theme/admin-third/js/admin/vendors/i18n/ui.datepicker-ja.js"></script>
			<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/themes/start/jquery-ui.min.css"> 
			<script>
			$('#MailMessageCalender').datepicker();
			const datetime = new Date();
			const datetime_today = datetime.getFullYear() + "-" +
									(datetime.getMonth() + 1)  + "-" + 
									datetime.getDate(); 
			$('#MailMessageCalender').datepicker({dateFormat: 'yy-mm-dd'});
			$("#MailMessageCalender").datepicker("setDate", datetime_today);
			</script>
		</div>
	</div>
</div>
<!-- /PAGE CONTENTS -->