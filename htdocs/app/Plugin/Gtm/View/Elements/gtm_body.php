<?php
/**
 * [View] Gtm
 * [element] body開始タグ直後用 テンプレート
 */
?>
<?php if (isset($key)) :?>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=<?php echo h($key) ?>"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<?php endif; ?>
