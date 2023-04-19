<?php
/**
 * [BANNER][PUBLISH] バナー一覧
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<?php if($datas): ?>
<ul>
<?php foreach ($datas as $bannerData): ?>
	<?php if($bannerData['BannerFile']['url']): ?>
		<?php if($bannerData['BannerFile']['blank']): ?>
			<li><a href="<?php echo $bannerData['BannerFile']['url'] ?>" target="_blank"><img src="<?php echo $bannerData['BannerFile']['name'] ?>" alt="<?php echo $bannerData['BannerFile']['alt'] ?>" /></a></li>
		<?php else: ?>
			<li><a href="<?php echo $bannerData['BannerFile']['url'] ?>"><img src="<?php echo $bannerData['BannerFile']['name'] ?>" alt="<?php echo $bannerData['BannerFile']['alt'] ?>" /></a></li>
		<?php endif ?>
	<?php else: ?>
		<li><img src="<?php echo $bannerData['BannerFile']['name'] ?>" alt="<?php echo $bannerData['BannerFile']['alt'] ?>" /></li>
	<?php endif ?>
<?php endforeach ?>
</ul>
<?php else: ?>
<p>バナーデータがありません。</p>
<?php endif ?>
