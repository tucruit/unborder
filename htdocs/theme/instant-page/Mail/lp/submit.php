<?php
/**
 * メールフォーム送信完了ページ
 * 呼出箇所：メールフォーム
 *
 * @var BcAppView $this
 * @var array $mailContent メールコンテンツデータ
 */
if ($mailContent['MailContent']['redirect_url']) {
	$url = $mailContent['MailContent']['redirect_url'];
	header('Location: '. h($url));
	exit;
}
