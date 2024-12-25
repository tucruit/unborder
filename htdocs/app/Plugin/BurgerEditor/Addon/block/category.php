<?php
/**
 * 利用するブロックを指定しない場合は全てのブロックが利用出来ます。
 *
 * カテゴリを指定する場合は以下に追加
 * ex)
 *   array(
 *     'カテゴリ１' => array('BlockName1', 'BlockName2'),
 *     'カテゴリ２' => array('BlockName3', 'BlockName4'),
 *   );
 */
$bgCategory = array(
	'ヘッダー' => array(
		'header-1' => 'ヘッダー',
	),
	'メイン：カスタム画像+テキスト' => array(
		'image-text-block1' => '画像+テキスト（左上）+ボタン（右下）',
		'image-text-block4' => '画像+テキスト（中央上）+ボタン（右下）',
		'image-text-block3' => '画像+文字影付き見出し（中央上）+テキスト（中央）+ボタン（中央下）',
		'image-text-block5' => '画像+文字影付き見出し（左上）+テキスト（左上）+ボタン（中央下）',
		'image-text-blur2' => '下部ぼかし画像+テキスト（左上）+ボタン（右下）',
		'image-text-blur3' => '下部ぼかし画像+テキスト（中央上）+ボタン（中央下）',
		'image-text-block2' => '角丸画像+テキスト（左上）+ボタン（右下）',
		'image-text-block6' => '角丸画像+テキスト（中央上）+ボタン（右下）',
		'image-text-blur_rou2' => '角丸下部ぼかし画像+テキスト（左上）+ボタン（右下）',
		'image-text-blur_rou3' => '角丸下部ぼかし画像+テキスト（中央上）+ボタン（中央下）',

		'image-title1' => '画像+左上囲い影見出し',
		'image-title2' => '画像+テキスト（中央）',
		'image-title3' => '画像+テキスト（中央上）',
		'image-title4' => '画像+テキスト（左上）',
		'image-title5' => '画像+テキスト（右上）',


		'image-medal' => '画像+メダル左下',
		'image-medal2' => '画像+メダル中央下',
		'image-medal3' => '画像+メダル右下',

		'oval-image-text' => '丸画像（右）+テキスト+ボタン',
		'oval-image-text2' => '丸画像（左）+テキスト+ボタン',
		'zoom-img-text' => 'ズーム画像+見出し',

		// //保留'image-text-blur1' => 'ぼかし画像+テキスト+ボタン',
		// //保留'image-text-blur_rou1' => '角丸ぼかし画像+テキスト+ボタン',
		'image-blur1' => '画像+半透明ボタン',
		// //保留'image-mix-box' => '複数画像+見出し+テキスト+ボタン',
	),
	'メイン：背景画像' => array(
		'back-img1' => '背景画像',

	),
	'ボタン' => array(
		'button' => 'ボタン',
		'button2' => 'ボタン x2',
		'button3' => 'ボタン x3',
		'download-file' => 'ファイル<br>ダウンロード',
		'download-file2' => 'ファイル<br>ダウンロード x2',
		'download-file3' => 'ファイル<br>ダウンロード x3',
	),
	'カスタムボタン' => array(
		'button-reflect' => 'リフレクト',
		'button-shake' => 'シェイク',
		'button-bound'=> 'バウンド',
		'button-syasen'=> '斜線枠',
		'button-syasen2'=> '斜線枠 x2',
		'button-kakumaru'=> '角丸',
		'button-rittai-kaku'=> '立体角',
		'button-rittai-kakumaru'=> '立体角丸',

		// 'button-custom' => 'ボタン（余白）',
		// 'button-custom2' => 'ボタン（余白） x2',
		// 'button-custom3' => 'ボタン（余白） x3',

		//追 'button-to-top'=> '右下固定ボタン',
	),
	'カスタム区切り線' => array(
		'hr-tensen' => '点線（区切り線）',
		'hr-syasen'=> '斜線（区切り線）',
		'hr-grade-center'=> '中央グラデ（区切り線）',
		'hr-haikeihasen'=> '背景色あり破線（区切り線）',
	),
	'見出し / テキスト / テキスト+画像' => array(
		'title' => '大見出し',
		'title2' => '中見出し',
		'wysiwyg' => '1カラムテキスト',
		'wysiwyg2' => '2カラムテキスト',

		'text-float-image1' => '画像右寄せ<small>テキスト回り込み</small>',
		'text-float-image2' => '画像左寄せ<small>テキスト回り込み</small>',
		'text-image1' => '画像右寄せ<small>テキスト回り込み無し</small>',
		'text-image2' => '画像左寄せ<small>テキスト回り込み無し</small>',
	),
	'カスタム見出し' => array(
		'title-dot-line' => '下点線見出し',
		'title-haikeikage' => '背景影あり見出し',
		'title-kakoi-kage' => '囲い影あり見出し',
		'title-center'=> '中央見出し',
		'title-keisya'=> '背景傾斜見出し',
		'title-fukidasi'=> '吹き出し見出し',
		'title-fukidasi2'=> '白抜き吹き出し見出し',
		'title-itimatu'=> '背景市松模様見出し',
		'title-kousi'=> '背景格子柄見出し',
		'title-mizutama'=> '背景水玉模様見出し',
		'title-kadoore'=> 'ドッグイヤー見出し',
		'title-transparent'=> '背景透過見出し',
		'title-first-letter'=> 'ファーストレター見出し',

	),
	'画像' => array(
		'image1' => '画像1列',
		'image2' => '画像2列',
		'image3' => '画像3列',
		'image4' => '画像4列',
		'image5' => '画像5列',
		'trimmed-image2' => 'トリミング画像2列',
		'trimmed-image3' => 'トリミング画像3列',
		'trimmed-image4' => 'トリミング画像4列',
		'trimmed-image5' => 'トリミング画像5列',
		'image-link1' => '画像1列<small>リンク付</small>',
		'image-link2' => '画像2列<small>リンク付</small>',
		'image-link3' => '画像3列<small>リンク付</small>',
		'image-link4' => '画像4列<small>リンク付</small>',
		'image-link5' => '画像5列<small>リンク付</small>',
		'trimmed-image-link2' => 'トリミング画像2列<small>リンク付</small>',
		'trimmed-image-link3' => 'トリミング画像3列<small>リンク付</small>',
		'trimmed-image-link4' => 'トリミング画像4列<small>リンク付</small>',
		'trimmed-image-link5' => 'トリミング画像5列<small>リンク付</small>',
	),
	'丸画像（スマホ改行表示）' => array(
		'oval-image1' => '画像1列',
		'oval-image-link1' => '画像1列<small>リンク付</small>',

		'oval-image2' => '正丸画像2列',
		'oval-image3' => '正丸画像3列',
		'oval-image4' => '正丸画像4列',
		'oval-image5' => '正丸画像5列',

		'oval-image-link2' => '正丸画像2列<small>リンク付</small>',
		'oval-image-link3' => '正丸画像3列<small>リンク付</small>',
		'oval-image-link4' => '正丸画像4列<small>リンク付</small>',
		'oval-image-link5' => '正丸画像5列<small>リンク付</small>',
		
		'image-text-r2' => '丸画像2列<small>テキスト付</small>',
		'image-text-r3' => '丸画像3列<small>テキスト付</small>',
		'image-text-r4' => '丸画像4列<small>テキスト付</small>',
		'image-text-r5' => '丸画像5列<small>テキスト付</small>',

		'image-link-text-r2' => '丸画像2列<small>リンク・テキスト付</small>',
		'image-link-text-r3' => '丸画像3列<small>リンク・テキスト付</small>',
		'image-link-text-r4' => '丸画像4列<small>リンク・テキスト付</small>',
		'image-link-text-r5' => '丸画像5列<small>リンク・テキスト付</small>',

	),
	'丸画像（スマホ横並び表示）' => array(
		'oval-image2_sp' => '正丸画像2列',
		'oval-image3_sp' => '正丸画像3列',
		'oval-image4_sp' => '正丸画像4列',
		'oval-image5_sp' => '正丸画像5列',

		'oval-image-link2_sp' => '正丸画像2列<small>リンク付</small>',
		'oval-image-link3_sp' => '正丸画像3列<small>リンク付</small>',
		'oval-image-link4_sp' => '正丸画像4列<small>リンク付</small>',
		'oval-image-link5_sp' => '正丸画像5列<small>リンク付</small>',
				
		'image-text-r2_sp' => '丸画像2列<small>テキスト付</small>',
		'image-text-r3_sp' => '丸画像3列<small>テキスト付</small>',
		'image-text-r4_sp' => '丸画像4列<small>テキスト付</small>',
		'image-text-r5_sp' => '丸画像5列<small>テキスト付</small>',

		'image-link-text-r2_sp' => '丸画像2列<small>リンク・テキスト付</small>',
		'image-link-text-r3_sp' => '丸画像3列<small>リンク・テキスト付</small>',
		'image-link-text-r4_sp' => '丸画像4列<small>リンク・テキスト付</small>',
		'image-link-text-r5_sp' => '丸画像5列<small>リンク・テキスト付</small>',
	),
	'画像+テキスト' => array(
		'image-text2' => '画像2列<small>テキスト付</small>',
		'image-text3' => '画像3列<small>テキスト付</small>',
		'image-text4' => '画像4列<small>テキスト付</small>',
		'image-text5' => '画像5列<small>テキスト付</small>',
		'image-link-text2' => '画像2列<small>リンク・テキスト付</small>',
		'image-link-text3' => '画像3列<small>リンク・テキスト付</small>',
		'image-link-text4' => '画像4列<small>リンク・テキスト付</small>',
		'image-link-text5' => '画像5列<small>リンク・テキスト付</small>',

	),
	'アニメーション画像' => array(
		
		'slidein-img' => '左からスライドイン',
		'slidein-img2' => '上からスライドイン',
		'slidein-img3' => '下からスライドイン',
		'slidein-img4' => '右からスライドイン',

		'zoom-img' => 'ズーム',
		// //保留'image-scrolldown' => 'scrollDown',

		//追 'slidein-img-f' => '左からスライドイン（倍速）',
		//追 'slidein-img2-f' => '上からスライドイン（倍速）',
		//追 'slidein-img3-f' => '下からスライドイン（倍速）',
		//追 'slidein-img4-f' => '右からスライドイン（倍速）',

		//追 'yurayura_l' => 'ゆらゆら画像左下',
		//追 'yurayura_r' => 'ゆらゆら画像右下',


	),
	'サイト前面アニメーション' => array(
		//追 'snow_dot' => 'ふわふわ雪アニメ',
		//追 'confetti' => '紙吹雪アニメ',
		//追 'fuwafuwa_ud' => 'ふわふわ（上下）',
		//追 //保留'fuwafuwa_wave' => 'ふわふわ（正弦波）',
		
	),
	'その他' => array(
		'table' => '2カラムテーブル',
		'gallery' => 'ギャラリー',
		'text-gallery1' => 'ギャラリー<small>+テキスト(左)</small>',
		'text-gallery2' => 'ギャラリー<small>+テキスト(右)</small>',
		'form' => 'メールフォーム',
		// //使用禁止'google-maps' => 'GoogleMaps',
		'youtube' => 'YouTube',
		'embed' => '埋め込みタグ<small>script / form</small>',
		'hr' => '区切り線',
		'medal' => 'メダル',
	),	
	'未分類' => array(
		//追 'snow_dot' => 'ふわふわ雪アニメ',

	),
);