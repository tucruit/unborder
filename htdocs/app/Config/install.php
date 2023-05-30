<?php
Configure::write('Security.salt', 'UbUUEC22EacVRHIhI181C6TaJ2SeSLEjDmF1N3Da');
Configure::write('Security.cipherSeed', '61848049262326091481034716683');
Configure::write('Cache.disable', false);
Configure::write('Cache.check', true);
Configure::write('BcEnv.siteUrl', 'https://instant-page.demo2022.e-catchup.jp/');
Configure::write('BcEnv.sslUrl', '');
Configure::write('BcEnv.mainDomain', '');
Configure::write('BcApp.adminSsl', false);
Configure::write('BcApp.allowedPhpOtherThanAdmins', false);
Cache::config('default', array('engine' => 'File'));
Configure::write('debug', 0);

include __DIR__ . DS . 'install.overwrite.php';
