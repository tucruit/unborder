<?php
/**
 * BurgerEditor <baserCMS plugin>
 *
 * @copyright		Copyright 2013 -, D-ZERO Co.,LTD.
 * @link			https://www.d-zero.co.jp/
 * @package			burger_editor
 * @since			Baser v 3.0.0
 * @license			https://market.basercms.net/files/baser_market_license.pdf
 */
?>
<div class="fuwafuwaWrapper">
	<?php $this->BurgerEditor->type('back-img1') ?>
	<div class="fuwafuwa_wave">
		<?php $this->BurgerEditor->type('oval-image') ?>
	</div>
</div>
<script>
const fly = (fuwafuwa_wave) => {
  const startTime = performance.now()
  const amplitude = { x: -10, y: 10, rotation: -2 }
  const speed = { x: 0.0004, y: 0.001 }

  const tick = () => {
    const diff = performance.now() - startTime
    const x = amplitude.x * Math.sin(speed.x * diff)
    const y = amplitude.y * Math.sin(speed.y * diff)
    const rotation = amplitude.rotation * (1 + Math.sin(speed.y * diff))

    fuwafuwa_wave.style.transform = `rotate(${rotation}deg) translate(${x}%, ${y}%)`

    requestAnimationFrame(tick)
  }
</script>