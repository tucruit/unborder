<?php
/**
* [PUBLISH] インスタントページ一覧
*/
?>
<section class="top-news">
	<div class="l-smallContainer top-newsInner">
		<h2 class="top-section-hl top-news-hl">インスタントページ一覧</h2>
		<div class="top-news-articleBoxContainer">
			<?php if (!empty($datas)): ?>
				<?php foreach ($datas as $key => $data) :?>
					<!-- BOX -->
					<article class="articleBox">
						<a href="#" class="articleBoxInner">
							<div class="articleBox-dateGroup">
								<time class="articleBox-date" datetime="2023-06-01">2023年06月01日</time>
								<div class="articleBox-catWrap"><span class="articleBox-cat">カテゴリー名</span></div>
							</div>
							<h3 class="articleBox-hl">ダミーの文章お知らせの内容を1ラインの文字ボリューム以内で入力</h3>
						</a>
					</article>
					<!-- /BOX -->
				<?php endforeach; ?>
				<?php $this->BcBaser->pagination(); ?>
			<?php else: ?>
			<?php endif;?>
			<!-- BOX -->
			<article class="articleBox">
				<a href="#" class="articleBoxInner">
					<div class="articleBox-dateGroup">
						<time class="articleBox-date" datetime="2023-06-01">2023年06月01日</time>
						<div class="articleBox-catWrap"><span class="articleBox-cat">カテゴリー名</span></div>
					</div>
					<h3 class="articleBox-hl">ダミーの文章お知らせの内容を1ラインの文字ボリューム以内で入力</h3>
				</a>
			</article>
			<!-- /BOX -->
			<!-- BOX -->
			<article class="articleBox">
				<a href="#" class="articleBoxInner">
					<div class="articleBox-dateGroup">
						<time class="articleBox-date" datetime="2023-06-01">2023年○月○日</time>
						<div class="articleBox-catWrap"><span class="articleBox-cat">カテゴリー名</span></div>
					</div>
					<h3 class="articleBox-hl">ダミーの文章お知らせの内容を1ラインの文字ボリューム以内で入力</h3>
				</a>
			</article>
			<!-- /BOX -->
			<!-- BOX -->
			<article class="articleBox">
				<a href="#" class="articleBoxInner">
					<div class="articleBox-dateGroup">
						<time class="articleBox-date" datetime="2023-06-01">2023年○月○日</time>
					</div>
					<h3 class="articleBox-hl">【Categoryなしサンプル】ダミーの文章お知らせの内容を1ラインの文字ボリューム以内で入力</h3>
				</a>
			</article>
			<!-- /BOX -->
		</div>
	</div>
</section>
