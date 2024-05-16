<?php
/**
 * [BANNER][PUBLISH] バナーブロック
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<?php if(!empty($bannerDatas)): ?>
<div id="banner_block" class="rover_li">
	<div class="slide">
		<ul>
		<?php foreach ($bannerDatas as $bannerData): ?>
			<li>
			<?php if($bannerData['BannerFile']['url']): ?>
				<?php if($bannerData['BannerFile']['blank']): ?>
					<a href="<?php echo h($bannerData['BannerFile']['url']) ?>" target="_blank">
						<?php $this->BcBaser->element('Banner.banner_block_img', array('bannerData' => $bannerData)) ?>
					</a>
				<?php else: ?>
					<a href="<?php echo h($bannerData['BannerFile']['url']) ?>">
						<?php $this->BcBaser->element('Banner.banner_block_img', array('bannerData' => $bannerData)) ?>
					</a>
				<?php endif ?>
			<?php else: ?>
				<?php $this->BcBaser->element('Banner.banner_block_img', array('bannerData' => $bannerData)) ?>
			<?php endif ?>
				<?php if ($this->Banner->getDescription($bannerData)): ?>
					<p><?php $this->Banner->showDescription($bannerData) ?></p>
				<?php endif; ?>
			</li>
		<?php endforeach; ?>
		</ul>
	</div>
	<div class="prev"><a href="/#">前へ</a></div>
	<div class="next"><a href="/#">次へ</a></div>
</div>
<?php else: ?>
<p>バナーデータがありません。</p>
<?php endif; ?>
