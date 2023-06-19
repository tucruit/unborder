━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　　◆◇　【インスタントページ】新規登録申請を受付けました　◇◆　　　
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　　　　　　　　　　　　　　　　　　　　　　　　　　<?php echo $other['date'] ?>


<?php if ($other['mode'] === 'user'): ?>
　この度は、当社ホームページをご利用頂き真に有難うございます。

<?php elseif ($other['mode'] === 'admin'): ?>
　インスタントページ ユーザー登録申請を受け付けました。
　受信内容は下記のとおりです。
　なお、このメールは自動転送システムです。
　受け付けた旨のメールもユーザーへ送られています。
<?php endif; ?>
<?php if ($other['mode'] === 'user'): ?>
<?php
	$config = Configure::read('Product');
	$configBcEnv = Configure::read('BcEnv');
	$token = $message['token'];
	$config['activateMaxTime'] = Configure::read('InstantPage.activateMaxTime');
?>
　お申込みを完了させるには、下記URLをクリックしてください。
　開かない場合はブラウザのアドレスにURLをコピー&ペーストし、開いてください。
　
　<?php echo Router::url(array('prefix' => 'mypage', 'plugin'=>'instant_page','controller' => 'instant_page_users', 'action' => 'mypage_activate', $token), true); ?>
<?php /*echo $configBcEnv['siteUrl']; ?>member/activate/<?php echo $token; */?>

　
　※URLが二行に分かれている場合、一行目と二行目を合わせた上で開いてください。
　※本登録には時間がかかる場合があります。URLをクリック後は処理が終了するまでしばらくお待ち下さい。
　
　※URLの有効期限は仮登録のお申し込みをいただいてから<?php echo $config['activateMaxTime'] ?>時間です。
　
　※このメールにお心当たりのない方は破棄していただきますようお願いいたします。
　※本メールに対するメールでのご返信・お問い合わせは、受け付けておりません。
　何かご不明な点等がございましたら、お手数ですが、お問い合わせフォームから
　お問い合わせください。
<?php else:?>
	<?php echo $this->element('../Emails/text/mail_data') ?>
<?php endif; ?>


━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

　<?php echo $mailConfig['site_name'] ? h($mailConfig['site_name']) : ''; ?>　
　<?php echo $mailConfig['site_url'] ? h($mailConfig['site_url']) : '' ?>　<?php echo $mailConfig['site_email'] ? h($mailConfig['site_email']) : ''; ?>　
　<?php if ($mailConfig['site_tel']): ?>TEL　<?php echo $mailConfig['site_tel']; ?><?php endif; ?><?php if ($mailConfig['site_fax']): ?>　FAX　<?php echo $mailConfig['site_fax']; ?><?php endif; ?>　

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
