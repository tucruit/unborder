<?php
/*
 * mypage footer
 */
// ログインユーザーの取得
$user = $this->Session->read('Auth');
$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
if (empty($instantPageUser)) {
	include __DIR__ . DS . '../footer.php';
} else {
?>
<!-- FOOTER -->
<footer role="contentinfo" class="footer">
	<div class="l-container footerInner">
		<small class="footer-copyright">Copyright © 2023 UNBORDER ltd.</small>
	</div>
</footer>
<!-- /FOOTER -->
<?php
}
