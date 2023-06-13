<?php if(!empty($InstantPage['Admin'])): ?>
ユーザー登録がありましたので通知いたします。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ユーザー情報
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
担当者：<?php if ($InstantPage['User']['real_name_1']) { echo $InstantPage['User']['real_name_1']; } ?> <?php if ($InstantPage['User']['real_name_2']) { echo $InstantPage['User']['real_name_2']; }?> 様

管理画面URL： <?php echo 'http://'.$_SERVER["HTTP_HOST"].'/cmsadmin/users/edit/' . $InstantPage['User']['id']; ?>

<?php else: ?>

<?php
	if ($InstantPage['User']['real_name_1']) {
		echo $InstantPage['User']['real_name_1'];
	}
?> <?php
	if ($InstantPage['User']['real_name_2']) {
		echo $InstantPage['User']['real_name_2'];
	}
?> 様

この度は、ユーザー登録頂き、誠にありがとうございます。
登録が完了しましたのでお知らせ致します。

━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ユーザ情報
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
ログインID：<?php echo $InstantPage['User']['name']; ?>

パスワード：**********（セキュリティのため暗号化されています）

<?php endif; ?>
