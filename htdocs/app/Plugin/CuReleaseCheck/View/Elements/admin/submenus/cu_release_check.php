<?php
/**
 * [BANNER][ADMIN] サブメニュー
 *
 * @copyright		Copyright 2013, Catchup, Inc.
 * @link			http://www.e-catchup.jp
 * @package			banner.views
 * @license			MIT
 */
?>
<tr>
	<th>リリースチェック管理メニュー</th>
	<td>
		<ul>
			<li><?php $this->BcBaser->link('チェックリスト', array('admin' => true, 'plugin' => 'cu_release_check', 'controller' => 'cu_release_check', 'action'=>'index')) ?></li>
			<li><?php $this->BcBaser->link('初期設定リスト', array('admin' => true, 'plugin' => 'cu_release_check', 'controller' => 'cu_release_check', 'action'=>'init')) ?></li>
		</ul>
	</td>
</tr>