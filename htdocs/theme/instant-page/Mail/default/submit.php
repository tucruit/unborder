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
 * メールフォーム送信完了ページ
 * 呼出箇所：メールフォーム
 *
 * @var BcAppView $this
 * @var array $mailContent メールコンテンツデータ
 */
if (Configure::read('debug') == 0 && $mailContent['MailContent']['redirect_url']) {
	$this->Html->meta(['http-equiv' => 'Refresh'], null, ['content' => '5;url=' . $mailContent['MailContent']['redirect_url'], 'inline' => false]);
}
?>

<!-- BREAD CRUMBS -->
<?php $this->BcBaser->crumbsList(['onSchema' => true]); ?>
<!-- /BREAD CRUMBS -->
<!-- SUB H1 -->
<?php $this->BcBaser->element('sub_categoryheader'); ?>
<!-- /SUB H1 -->

<!-- PAGE CONTENTS -->
<div class="contact isSubmit">
	<div class="l-subContentsContainer sub-container contactInner">
		<h2 class="mod-hl-01 contact-hl">お問い合わせフォーム</h2>
		<div class="contact-form">
			<p class="contact-form-thanksMsg">
				<?php echo __('お問い合わせ頂きありがとうございました。') ?><br>
				<?php echo __('確認次第、ご連絡させて頂きます。') ?>
			</p>
			<?php if (Configure::read('debug') == 0 && $mailContent['MailContent']['redirect_url']) : ?>
				<p class="contact-form-msg">※<?php echo __('%s 秒後にトップページへ自動的に移動します。', 5) ?></p>
				<p class="contact-form-msg"><a href="<?php echo $mailContent['MailContent']['redirect_url']; ?>"><?php echo __('	') ?></a></p>
			<?php endif; ?>
		</div>
	</div>
</div>
<!-- /PAGE CONTENTS -->