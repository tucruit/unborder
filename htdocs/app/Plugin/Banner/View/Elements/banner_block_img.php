<picture>
	<?php if (!empty($bannerData['BannerFile']['breakpoints'])): ?>
		<?php foreach($bannerData['BannerFile']['breakpoints'] as $breakpoint): ?>
			<?php if ($breakpoint['name'] && $breakpoint['media_script']): ?>
				<source srcset="<?php echo h($breakpoint['name']) ?>" media="<?php echo h($breakpoint['media_script']) ?>" />
			<?php endif ?>
		<?php endforeach ?>
	<?php endif ?>
	<img src="<?php echo h($bannerData['BannerFile']['name']) ?>" alt="<?php echo h($bannerData['BannerFile']['alt']) ?>" />
</picture>
