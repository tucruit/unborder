<?php
/**
 * [ADMIN] インスタントページ設定一覧
 */
?>
<div role="main" class="myPage">
	<h1 class="mod-hl-pageTitle">マイページ</h1>
	<div class="l-container l-contentsContainer myPageInner">
		<?php /*if (empty($datas) || count($datas) < intval($limit)) :?>
			<div class="myPage-btnGroup">
				<a href="/cmsadmin/instant_page/instant_pages/add" class="mod-btn-square-01 myPage-btnGroup-lpNew">
					<span class="btnInner">LP新規作成</span>
				</a>

			</div>
		<?php endif;*/?>
		<div class="js-scrollable myPage-siteTableWrap">
			<table class="myPage-siteTable">
				<thead>
					<tr>
						<th>アイキャッチ</th>
						<th>タイトル</th>
						<th>説明</th>
						<th>利用数</th>
						<th>最終更新日</th>
						<?php /*<th>独自ドメイン</th>*/?>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($datas)):?>
						<?php foreach ($datas as $data) :?>
							<tr>
								<!-- <td class="bca-table-listup__tbody-td"><?php //echo $data['InstantPageTemplate']['id'] ?></td> -->
								<td class="bca-table-listup__tbody-td">
									<?php
									if (isset($themedatas[$data['InstantPageTemplate']['name']]) && $themedatas[$data['InstantPageTemplate']['name']]['screenshot']) {
										$this->BcBaser->img('/theme/' . $data['InstantPageTemplate']['name'] . '/screenshot.png', ['alt' => $data['InstantPageTemplate']['name'], 'width' => '50px']);
									}
									?>
								</td>
								<td class="bca-table-listup__tbody-td">
									<?php
									if (isset($themedatas[$data['InstantPageTemplate']['name']]) && $themedatas[$data['InstantPageTemplate']['name']]['title']) {
										echo h($themedatas[$data['InstantPageTemplate']['name']]['title']);
									}else {
										echo h($data['InstantPageTemplate']['name']);
									}
									?>
								</td>
								<td class="bca-table-listup__tbody-td">
									<?php
									if (isset($themedatas[$data['InstantPageTemplate']['name']]) && $themedatas[$data['InstantPageTemplate']['name']]['description']) {
										echo nl2br(h(mb_strimwidth($themedatas[$data['InstantPageTemplate']['name']]['description'], 0, 160, '...', 'utf8')));
									}
									?>
								</td>
								<td class="bca-table-listup__tbody-td">
									<?php echo isset($data['InstantPage']) ? count($data['InstantPage']) : ''; ?>
								</td>
								<td class="bca-table-listup__tbody-td">
									<?php //echo $this->BcTime->format('Y年m月d日 H:i:s', $data['InstantPageTemplate']['created']) ?><br>
									<?php echo $this->BcTime->format('Y年m月d日 H:i:s', $data['InstantPageTemplate']['modified']) ?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
