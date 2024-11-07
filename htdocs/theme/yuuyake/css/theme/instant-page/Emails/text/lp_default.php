<?php
/**
 * [EMAIL] LP用メール送信
 */
if ($message['author']) {
	$mailConfig['site_name'] = $message['author_name'];
	$mailConfig['site_email'] = $message['author_email'];
	$mailConfig['site_tel'] = $message['author_tel'];
}
$mailConfig['site_url'] = $message['url'] ? $this->BcBaser->getUrl($message['url'], true): $mailConfig['site_url'];
?>

<?php echo $other['date'] ?>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　　　　　　　　◆◇　<?php echo __d('baser', 'お問い合わせを受け付けました')?>　◇◆
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

<?php if ($other['mode'] === 'user'): ?>
　<?php echo __d('baser', 'この度は、お問い合わせいただきありがとうございます。')?>　
　<?php echo __d('baser', '送信内容は下記のようになっております。')?>　
<?php elseif ($other['mode'] === 'admin'): ?>
　<?php echo sprintf(__d('baser', '%s へのお問い合わせを受け付けました。'), $mailConfig['site_name']) ?>　
　<?php echo __d('baser', '受信内容は下記のとおりです。')?>　
<?php endif; ?>

━━━━◇◆━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　◆ <?php echo __d('baser', 'お問い合わせ内容')?>　
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━◆◇━━━━
<?php echo $this->element('../Emails/text/lp_data') ?>



────────────────────────────────────

<?php if ($other['mode'] === 'user'): ?>
　<?php echo __d('baser', 'なお、このメールは自動返信メールとなっております。')?>　
　<?php echo __d('baser', 'メールを確認させて頂き次第、早急にご連絡させていただきます。')?>　
　<?php echo __d('baser', '恐れ入りますがしばらくお待ちください。')?>　
<?php elseif ($other['mode'] === 'admin'): ?>
　<?php echo __d('baser', 'なお、このメールは自動転送システムです。')?>　
　<?php echo __d('baser', '受け付けた旨のメールもユーザーへ送られています。')?>　
<?php endif; ?>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

　<?php echo $mailConfig['site_name']; ?>　
　<?php echo $mailConfig['site_url'] ?>　
<?php if ($mailConfig['site_tel']): ?>　E-MAIL　<?php echo $mailConfig['site_email']; ?>　<?php endif; ?>
<?php if ($mailConfig['site_tel']): ?>　TEL　<?php echo $mailConfig['site_tel']; ?>　<?php endif; ?>
<?php if ($mailConfig['site_fax']): ?>　FAX　<?php echo $mailConfig['site_fax']; ?>　<?php endif; ?>

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
