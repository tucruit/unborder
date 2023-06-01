<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<?php $this->BcBaser->title(); ?>
	<!-- keywords -->
	<?php if (!empty($this->BcBaser->getKeywords())) : ?>
		<meta name="keywords" content="<?php $this->BcBaser->contentsTitle() ?>,<?php echo $this->BcBaser->getKeywords() ?>">
	<?php else : ?>
		<meta name="keywords" content="">
	<?php endif ?>
	<!-- /keywords -->
	<!-- description -->
	<?php
	$descriptionTxt = "";
	if ($this->BcBaser->isHome()) {
		$descriptionTxt = $this->BcBaser->getDescription();
	} else {
		$descriptionTxt = $this->BcBaser->getContentsTitle() . '｜' . $this->BcBaserCustom->getDescription_new();
	}
	?>
	<meta name="description" content="<?php echo $descriptionTxt ?>">
	<!-- /description -->
	<meta name="viewport" content="width=device-width,initial-scale=1.0,minimum-scale=1.0">
	<meta name="format-detection" content="telephone=no">
	<!-- FAVICON -->
	<link rel="icon" href="<?php $this->BcBaser->themeUrl(); ?>favicon.ico">
	<link rel="shortcut icon" href="<?php $this->BcBaser->themeUrl(); ?>img/common/favicon_180.png">
	<link rel="apple-touch-icon" href="<?php $this->BcBaser->themeUrl(); ?>img/common/favicon_180.png">
	<!-- /FAVICON -->
	<!-- FONTS -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Zen+Kaku+Gothic+New:wght@300;400;500;700;900&display=swap" rel="stylesheet">
	<!-- /FONTS -->
	<!-- CSS -->
	<link rel="stylesheet" href="<?php $this->BcBaser->themeUrl(); ?>css/common.css">
	<link rel="stylesheet" href="<?php $this->BcBaser->themeUrl(); ?>css/import.css">
	<!-- /CSS -->
	<!-- JS LIBRARY -->
	<script src="<?php $this->BcBaser->themeUrl(); ?>js/lib/jquery-3.6.0.min.js"></script>
	<!-- /JS LIBRARY -->
	<!-- SHARE -->
	<meta name="twitter:card" content="summary">
	<meta property="og:type" content="website">
	<meta property="og:locale" content="ja_JP">
	<?php
	$themaUrl = $this->BcBaser->getThemeUrl();
	$themaFullUrl = $this->BcBaser->getUri($themaUrl, false);
	?>
	<meta property="og:image" content="<?php echo $themaFullUrl; ?>img/common/ogp_logo.png">
	<meta property="og:url" content="<?php $this->BcBaser->url(null, true, false); ?>">
	<meta property="og:title" content="<?php echo $this->BcBaser->getTitle(); ?>">
	<meta property="og:description" content="<?php echo $descriptionTxt; ?>">
	<meta property="og:site_name" content="<?php echo $this->BcBaser->getSiteName(); ?>">
	<!-- /SHARE -->
	<?php $this->BcBaser->scripts() ?>
	<?php $this->BcBaser->googleAnalytics() ?>
</head>

<body>
	<!-- HEADER -->
	<?php $this->BcBaser->header() ?>
	<!-- /HEADER -->

	<!-- MAIN -->
	<main>
		<!-- CONTENTS -->
		<div role="main">
			<?php if ($this->BcBaser->isHome() || $this->BcBaser->getContentsName() == "Home") : ?>
				<div class="top">
					<!-- KV -->
					<div class="top-kv">
						<div class="top-kvInner">
							<div class="top-kv-txt">
								<picture class="top-kv-txt-catch">
									<source srcset="<?php $this->BcBaser->themeUrl() ?>img/top/kv_catch_sp.svg" media="(max-width: 1023px)">
									<img src="<?php $this->BcBaser->themeUrl() ?>img/top/kv_catch.svg" alt="ランディングページ制作支援ツール インスタントページ" class="imgFit">
								</picture>
								<div class="top-kv-txt-btnWrap">
									<a href="#" class="top-kv-txt-btn">
										<span class="btnInner">今すぐはじめる</span>
									</a>
								</div>
							</div>
							<div class="top-kv-img">
								<img src="<?php $this->BcBaser->themeUrl() ?>img/top/kv_animation.gif" alt="" class="imgFit">
							</div>
						</div>
					</div>
					<!-- /KV -->
					<!-- LEAD -->
					<div class="top-lead" id="top-lead">
						<div class="l-smallContainer top-leadInner">
							<div class="top-lead-logo">
								<img src="<?php $this->BcBaser->themeUrl() ?>img/common/logo.svg" alt="ランディングページ制作支援ツール インスタントページ" class="imgFit">
							</div>
							<h2 class="top-section-hl top-lead-hl">
								<span class="isSentence">「即席」だけど</span><span class="isSentence"><span class="noWrap">「ウマい」LPを</span><span class="noWrap">作ります</span></span>
							</h2>
							<p class="top-lead-txt">
								インスタントページは、お好きなデザインを選んで、文章をいれるだけの「即席LPサービス」です。<br>
								「Webの事は分からない…」「デザインのセンスがなくて…」「手間をかける時間がない…」、そんなアナタにピッタリ。<br>
								ぜひ、”お湯すらいらない「即席」体験”をお楽しみください！
							</p>
							<!-- MOVIE HIDDEN
							<div class="top-lead-movieBlock">
								<div class="top-lead-movieBlockInner">
									<div class="top-lead-movieBlock-videoWrap">
										<video src="<?php $this->BcBaser->themeUrl() ?>img/top/lead_mv_system-operation.mp4" autoplay muted playsinline loop class="imgObjectFitCover"></video>
									</div>
								</div>
							</div>
							 -->
						</div>
					</div>
					<!-- /LEAD -->
					<!-- RECOMMEND -->
					<section class="top-recommend" id="top-recommend">
						<div class="l-smallContainer top-recommendInner">
							<h2 class="top-section-hl top-recommend-hl">こんな方にオススメ！</h2>
							<div class="top-recommend-caseBoxContainer">
								<!-- BOX -->
								<section class="caseBox">
									<h3 class="caseBox-hl">
										<small class="isSub">CASE<span class="isNum">1</span></small>
										<span class="isMain">急遽決まった集客イベント…<br>スグ明日にでもLPがほしい！</span>
									</h3>
									<div class="caseBox-img">
										<img src="<?php $this->BcBaser->themeUrl() ?>img/top/recommend_case_img_01.svg" width="280" height="190" alt="" class="imgFit" loading="lazy">
									</div>
									<p class="caseBox-cf">
										<span class="noWrap">（例）お店を経営されている方／</span><span class="noWrap">集客戦略ご担当者様</span>
									</p>
								</section>
								<!-- /BOX -->
								<!-- BOX -->
								<section class="caseBox">
									<h3 class="caseBox-hl">
										<small class="isSub">CASE<span class="isNum">2</span></small>
										<span class="isMain">週替わりチラシの感覚で<br>バンバンLPを投下したい！</span>
									</h3>
									<div class="caseBox-img">
										<img src="<?php $this->BcBaser->themeUrl() ?>img/top/recommend_case_img_02.svg" width="280" height="190" alt="" class="imgFit" loading="lazy">
									</div>
									<p class="caseBox-cf">
										（例）量販・流通の販促ご担当者様
									</p>
								</section>
								<!-- /BOX -->
								<!-- BOX -->
								<section class="caseBox">
									<h3 class="caseBox-hl">
										<small class="isSub">CASE<span class="isNum">3</span></small>
										<span class="isMain">採用LPを部署・対象別などで<br>ミクロに照準し打ちたい！</span>
									</h3>
									<div class="caseBox-img">
										<img src="<?php $this->BcBaser->themeUrl() ?>img/top/recommend_case_img_03.svg" width="280" height="190" alt="" class="imgFit" loading="lazy">
									</div>
									<p class="caseBox-cf">
										<span class="noWrap">（例）企業の採用ご担当者様／</span><span class="noWrap">求人広告代理店様</span>
									</p>
								</section>
								<!-- /BOX -->
								<!-- BOX -->
								<section class="caseBox">
									<h3 class="caseBox-hl">
										<small class="isSub">CASE<span class="isNum">4</span></small>
										<span class="isMain">自社のお客様のLP制作を<br>超スピーディーに支援したい！</span>
									</h3>
									<div class="caseBox-img">
										<img src="<?php $this->BcBaser->themeUrl() ?>img/top/recommend_case_img_04.svg" width="280" height="190" alt="" class="imgFit" loading="lazy">
									</div>
									<p class="caseBox-cf">
										<span class="noWrap">（例）WEBサイト制作会社様／</span><span class="noWrap">広告代理店様／コンサルティング会社様</span>
									</p>
								</section>
								<!-- /BOX -->
								<!-- BOX -->
								<section class="caseBox">
									<h3 class="caseBox-hl">
										<small class="isSub">CASE<span class="isNum">5</span></small>
										<span class="isMain">LP制作を外注せず社内で<br>出来るようにしたい！</span>
									</h3>
									<div class="caseBox-img">
										<img src="<?php $this->BcBaser->themeUrl() ?>img/top/recommend_case_img_05.svg" width="280" height="190" alt="" class="imgFit" loading="lazy">
									</div>
									<p class="caseBox-cf">
										<span class="noWrap">（例）WEBサイト制作会社様／</span><span class="noWrap">広告代理店様／コンサルティング会社様</span>
									</p>
								</section>
								<!-- /BOX -->
								<!-- BOX -->
								<section class="caseBox">
									<h3 class="caseBox-hl">
										<small class="isSub">CASE<span class="isNum">6</span></small>
										<span class="isMain">従来の制作工数を減らしつつ<br>サクっとテストマーケしたい！</span>
									</h3>
									<div class="caseBox-img">
										<img src="<?php $this->BcBaser->themeUrl() ?>img/top/recommend_case_img_06.svg" width="280" height="190" alt="" class="imgFit" loading="lazy">
									</div>
									<p class="caseBox-cf">
										<span class="noWrap">（例）WEBサイト制作会社様／</span><span class="noWrap">広告代理店様／コンサルティング会社様</span>
									</p>
								</section>
								<!-- /BOX -->
							</div>
						</div>
					</section>
					<!-- /RECOMMEND -->
					<!-- FEATURES -->
					<section class="top-features" id="top-features">
						<div class="top-featuresInner">
							<div class="l-smallContainer top-features-contentsContainer">
								<h2 class="top-section-hl top-features-hl">
									<span class="isSentence">インスタントページの</span><span class="isSentence">「即席LP」体験</span>
								</h2>
								<div class="top-features-featuresBoxContainer">
									<!-- BOX -->
									<section class="featuresBox">
										<small class="featuresBox-header">
											<span class="featuresBox-headerInner">
												<span class="isTxt">FEATURE</span><span class="isNum">1</span>
											</span>
										</small>
										<div class="featuresBox-body">
											<div class="featuresBox-body-img">
												<div class="featuresBox-body-imgInner">
													<img src="<?php $this->BcBaser->themeUrl() ?>img/top/feature_img_01.svg" width="338" height="262" alt="" class="imgFit" loading="lazy">
												</div>
											</div>
											<div class="featuresBox-body-txt">
												<h3 class="featuresBox-body-txt__hl">デザイン選択が「即席」</h3>
												<p class="featuresBox-body-txt__txt">
													インスタントページなら、たくさんあるデザインパターンの中からお好きなものを選択できます。<br>
													デザインを考える時間がなくても「即席」でLPが出来上がります。
												</p>
											</div>
										</div>
									</section>
									<!-- BOX -->
									<!-- BOX -->
									<section class="featuresBox">
										<small class="featuresBox-header">
											<span class="featuresBox-headerInner">
												<span class="isTxt">FEATURE</span><span class="isNum">2</span>
											</span>
										</small>
										<div class="featuresBox-body">
											<div class="featuresBox-body-img">
												<div class="featuresBox-body-imgInner">
													<img src="<?php $this->BcBaser->themeUrl() ?>img/top/feature_img_02.svg" width="338" height="262" alt="" class="imgFit" loading="lazy">
												</div>
											</div>
											<div class="featuresBox-body-txt">
												<h3 class="featuresBox-body-txt__hl">レイアウト作成が「即席」</h3>
												<p class="featuresBox-body-txt__txt">
													インスタントページなら、ブロック（ページのパーツ）を積み上げるようにレイアウトを作成できます。<br>
													Webのことが分からなくても「即席」でLPが出来上がります。
												</p>
											</div>
										</div>
									</section>
									<!-- BOX -->
									<!-- BOX -->
									<section class="featuresBox">
										<small class="featuresBox-header">
											<span class="featuresBox-headerInner">
												<span class="isTxt">FEATURE</span><span class="isNum">3</span>
											</span>
										</small>
										<div class="featuresBox-body">
											<div class="featuresBox-body-img">
												<div class="featuresBox-body-imgInner">
													<img src="<?php $this->BcBaser->themeUrl() ?>img/top/feature_img_03.svg" width="338" height="262" alt="" class="imgFit" loading="lazy">
												</div>
											</div>
											<div class="featuresBox-body-txt">
												<h3 class="featuresBox-body-txt__hl">ページ公開も「即席」</h3>
												<p class="featuresBox-body-txt__txt">
													インスタントページなら、高価なWebサーバーを用意しなくてもインターネットにページを公開できます。<br>
													高価な機材がなくても「即席」でLPが出来上がります。
												</p>
											</div>
										</div>
									</section>
									<!-- BOX -->
								</div>
								<!-- <a href="#" class="top-btn top-features-moreBtn">
									<span class="btnInner">出来ることをもっと見る</span>
								</a> -->
							</div>
						</div>
					</section>
					<!-- /FEATURES -->
					<!-- PLAN -->
					<section class="top-plan" id="top-plan">
						<div class="l-smallContainer top-planInner">
							<h2 class="top-section-hl top-plan-hl">料金プラン</h2>
							<p class="top-plan-lead">
								インスタントページの料金プランは「無料プラン」「有料レギュラープラン」「有料ビジネスプラン」の3つだけ。まずは無料プランでお試しください。
							</p>
							<table class="top-plan-table">
								<thead>
									<tr>
										<th>&thinsp;</th>
										<th>無料プラン</th>
										<th>
											<span class="noWrap">有料</span><span class="noWrap">レギュラー</span><span class="noWrap">プラン</span>
										</th>
										<th>
											<span class="noWrap">有料</span><span class="noWrap">ビジネス</span><span class="noWrap">プラン</span>
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<th>
											<div class="isCellInner">
												月額料金
											</div>
										</th>
										<td><em>無　料</em></td>
										<td><em><span class="noWrap">ASK! ／</span><span class="noWrap">月</span></em></td>
										<td><em><span class="noWrap">ASK! ／</span><span class="noWrap">月</span></em></td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">作成できる</span><span class="noWrap">LPの上限</span>
											</div>
										</th>
										<td>１ページのみ</td>
										<td>５ページまで</td>
										<td>５ページまで</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">選べる</span><span class="noWrap">デザイン</span>
											</div>
										</th>
										<td>
											<span class="noWrap"><span class="noWrap">無料</span><span class="noWrap">テンプレート</span></span><span class="noWrap">使用可能</span>
										</td>
										<td>すべて</td>
										<td>すべて</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">レスポンシブ</span><span class="noWrap">対応</span>
											</div>
										</th>
										<td>○</td>
										<td>○</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">SSL</span><span class="noWrap">対応</span>
											</div>
										</th>
										<td>○</td>
										<td>○</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">SNS</span><span class="noWrap">連携</span>
											</div>
										</th>
										<td>×</td>
										<td>○</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">独自</span><span class="noWrap">ドメイン</span>
											</div>
										</th>
										<td>×</td>
										<td>○</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">アクセス</span><span class="noWrap">解析</span>
											</div>
										</th>
										<td>×</td>
										<td>○</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">HTML/</span><span class="noWrap">CSS/</span><span class="noWrap">JS埋め込み</span>
											</div>
										</th>
										<td>×</td>
										<td>○</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">オンライン</span><span class="noWrap">決済</span>
											</div>
										</th>
										<td>×</td>
										<td>○</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">オンライン</span><span class="noWrap">予約</span>
											</div>
										</th>
										<td>×</td>
										<td>×</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">メルマガ</span><span class="noWrap">配信</span>
											</div>
										</th>
										<td>×</td>
										<td>×</td>
										<td>○</td>
									</tr>
									<tr>
										<th>
											<div class="isCellInner">
												<span class="noWrap">パスワード</span><span class="noWrap">保護</span>
											</div>
										</th>
										<td>×</td>
										<td>×</td>
										<td>○</td>
									</tr>
								</tbody>
							</table>
							<!-- <a href="#" class="top-btn top-plan-moreBtn">
								<span class="btnInner">料金プランをもっと見る</span>
							</a> -->
						</div>
					</section>
					<!-- /PLAN -->
					<!-- NEWS -->
					<section class="top-news" id="top-news">
						<div class="l-smallContainer top-newsInner">
							<h2 class="top-section-hl top-news-hl">お知らせ</h2>
							<div class="top-news-articleBoxContainer">
								<?php $this->BcBaser->blogPosts('news') ?>
							</div>
						</div>
					</section>
					<!-- /NEWS -->
				</div>
			<?php else : ?>
				<?php $this->BcBaser->flash() ?>
				<?php $this->BcBaser->content() ?>
			<?php endif; ?>
			<!-- CONTACT -->
			<?php $this->BcBaser->element('mod_contact'); ?>
			<!-- /CONTACT -->
		</div>
	</main>
	<!-- /MAIN -->

	<!-- FOOTER -->
	<?php $this->BcBaser->footer() ?>
	<!-- /FOOTER -->

	<!-- PAGE TOP BTN -->
	<div class="pageTop-box">
		<div id="pageTop" class="pageTop">
			<img src="<?php echo $this->BcBaser->themeUrl(); ?>img/common/btn_pgtop.svg" alt="ページトップに戻る" class="imgFit">
		</div>
	</div>
	<!-- /PAGE TOP BTN -->

	<!-- JS -->
	<script src="<?php $this->BcBaser->themeUrl(); ?>js/common_navigation.js"></script>
	<script src="<?php $this->BcBaser->themeUrl(); ?>js/common.js"></script>
	<!-- /JS -->

	<?php $this->BcBaser->func() ?>
</body>

</html>
