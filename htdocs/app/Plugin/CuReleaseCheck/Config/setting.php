<?php
/**
 * システムナビ
 */
$config['BcApp.adminNavi.BgeConverter'] = array(
	'name'		=> 'CuReleaseCheck',
	'contents'	=> array(
		array('name' => 'check list', 
			'url' => array('admin' => true, 'plugin' => 'cu_release_check', 'controller' => 'cu_release_check', 'action' => 'index'))
	)
);