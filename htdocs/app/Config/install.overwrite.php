<?php
/**
 * install.phpの最後に下記を追加してください
 * include __DIR__ . DS . 'install.overwrite.php'; 
 */
// 本番ドメイン設定 - 固定で入力すること
Configure::write('BcEnv.siteUrl', 'https://example.com/');
Configure::write('BcEnv.sslUrl', 'https://example.com/');
Configure::write('BcEnv.cmsUrl', 'https://example.com/');
Configure::write('BcApp.adminSsl', false);
Configure::write('Asset.timestamp', 'force');

/**
 * LB SSLインストール対応
 * サーバ環境でSSL判別が出来ないため、fullBaseUrlに規定値で追加
 */
//if (strpos($_SERVER['HTTP_HOST'], 'example.com') !== false) {
//	Configure::write('App.fullBaseUrl', 'https://example.com/');
//}

// コンソール
//if (strpos(__FILE__, "/path/to/root/app/Config/isntall.overwrite.php") !== false) {
//	Configure::write('BcEnv.siteUrl', 'https://example.com/');
//	Configure::write('BcEnv.sslUrl', 'https://example.com/');
//}

if (!empty($_SERVER['HTTP_HOST'])) {
	// ローカル環境
	if (strpos($_SERVER['HTTP_HOST'], '.localhost') !== false) {
		Configure::write('ToolbarColorChanger.background', '#3F51B5');
		Configure::write('BcEnv.siteUrl', 'http://'.$_SERVER["HTTP_HOST"].'/');
		Configure::write('BcEnv.sslUrl', '');
		Configure::write('BcEnv.cmsUrl', '');
	}
	// デモ環境
	if (strpos($_SERVER['HTTP_HOST'], '.demo.domain') !== false) {
		Configure::write('ToolbarColorChanger.background', '#A79B8E');
		Configure::write('BcEnv.siteUrl', 'http://'.$_SERVER["HTTP_HOST"].'/');
		Configure::write('BcEnv.sslUrl', '');
		Configure::write('BcEnv.cmsUrl', '');
	}

}
