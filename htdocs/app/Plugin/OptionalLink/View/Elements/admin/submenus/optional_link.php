<?php
/**
 * [ADMIN] サブメニュー
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */

?>
<tr>
	<th>オプショナルリンク設定管理メニュー</th>
	<td>
		<ul>
			<li><?php $this->BcBaser->link('オプショナルリンク設定一覧', array('admin' => true, 'plugin' => 'optional_link', 'controller' => 'optional_link_configs', 'action' => 'index')); ?></li>
			<?php if (!$hasDir): ?>
				<li><?php $this->BcBaser->link('ファイルアップロード用フォルダ作成', array('admin' => true, 'plugin' => 'optional_link', 'controller' => 'optional_link_configs', 'action' => 'init_folder')); ?></li>
			<?php endif; ?>
		</ul>
	</td>
</tr>
