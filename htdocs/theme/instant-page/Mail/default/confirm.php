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
 * メールフォーム確認ページ
 * 呼出箇所：メールフォーム
 *
 * @var BcAppView $this
 * @var bool $freezed 確認画面かどうか
 */
$className = '';
if ($freezed) {
	$this->Mailform->freeze();
	$className = 'isFreezed';
}
?>

<!-- BREAD CRUMBS -->
<?php $this->BcBaser->crumbsList(['onSchema' => true]); ?>
<!-- /BREAD CRUMBS -->
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader'); ?>
<!-- /SUB H1 -->

<!-- PAGE CONTENTS -->
<div class="contact <?php echo $className; ?>">
	<div class="l-subContentsContainer sub-container contactInner">
		<h2 class="mod-hl-01 contact-hl">お問い合わせフォーム</h2>
		<p class="contact-lead">
			こちらのフォームよりお気軽にお問い合わせください。担当者より返答させていただきます。
		</p>
		<div class="contact-form">
			<?php $this->BcBaser->flash() ?>
			<?php $this->BcBaser->element('mail_form') ?>
		</div>
	</div>
</div>
<!-- /PAGE CONTENTS -->