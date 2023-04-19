<?php
/**
 * [BANNER][ADMIN] サブメニュー
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<tr>
	<th>バナーエリア管理メニュー</th>
	<td>
		<ul>
			<li><?php $this->BcBaser->link('バナープラグイン設定', array('admin' => true, 'plugin' => 'banner', 'controller' => 'banner', 'action'=>'config')) ?></li>
			<li><?php $this->BcBaser->link('バナーエリア一覧', array('admin' => true, 'plugin' => 'banner', 'controller' => 'banner_areas', 'action'=>'index')) ?></li>
			<li><?php $this->BcBaser->link('バナーエリア新規登録', array('admin' => true, 'plugin' => 'banner', 'controller' => 'banner_areas', 'action'=>'add')) ?></li>
			<?php if($this->name == 'BannerFiles'): ?>
			<li><?php $this->BcBaser->link('['. $bannerArea['BannerArea']['name'] . ']管理', array('admin' => true, 'plugin' => 'banner', 'controller' => 'banner_areas', 'action' => 'edit', $bannerArea['BannerArea']['id']), ['escape' => true]) ?></li>
			<?php endif ?>
		</ul>
	</td>
</tr>
<?php if($this->name == 'BannerFiles'): ?>
<tr>
	<th>バナー管理メニュー</th>
	<td>
		<ul>
			<li><?php $this->BcBaser->link('バナー一覧', array('admin' => true, 'plugin' => 'banner', 'controller' => 'banner_files', 'action'=>'index', $bannerArea['BannerArea']['id'])) ?></li>
			<li><?php $this->BcBaser->link('バナー新規登録', array('admin' => true, 'plugin' => 'banner', 'controller' => 'banner_files', 'action'=>'add', $bannerArea['BannerArea']['id'])) ?></li>
		</ul>
	</td>
</tr>
<?php endif ?>
