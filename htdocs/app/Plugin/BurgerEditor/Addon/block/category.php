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
	'カスタムボタン' => array(
		'button-reflect' => 'リフレクト',
		'button-shake' => 'シェイク',
		'button-bound'=> 'バウンド',
		'button-syasen'=> '斜線枠',
		'button-syasen2'=> '斜線枠 x2',
		'button-kakumaru'=> '角丸',
		'button-rittai-kaku'=> '立体角',
		'button-rittai-kakumaru'=> '立体角丸',
		'button-custom' => 'ボタン（余白）',
		'button-custom2' => 'ボタン（余白） x2',
		'button-custom3' => 'ボタン（余白） x3',
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
	'カスタム見出し / テキスト / テキスト+画像' => array(
		'text-image3' => '画像上寄せ+テキスト',
		'text-image4' => '画像+テキスト',
	),
	'カスタム見出し' => array(
		'title-dot-line' => '下点線見出し',
		'title-haikeikage' => '背景影あり見出し',
		'title-kakoi-kage' => '囲い影あり見出し',
		// 'title-stripe' => '下斜線模様見出し',
		// 'title-patch' => '上アーチ見出し',
		// 'title-center'=> '中央下線見出し',
		'title-keisya'=> '背景傾斜見出し',
		'title-fukidasi'=> '吹き出し見出し',
		'title-fukidasi2'=> '白抜き吹き出し見出し',
		'title-itimatu'=> '背景市松模様見出し',
		'title-kousi'=> '背景格子柄見出し',
		'title-mizutama'=> '背景水玉模様見出し',
		'title-kadoore'=> 'ドッグイヤー見出し',
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
	'カスタム画像+テキスト' => array(
		'image-text-block1' => '画像+テキスト+ボタン',
		'image-text-blur1' => 'ぼかし画像+テキスト+ボタン',
		'image-blur1' => '下部ぼかし画像+テキスト',

	),
	'ボタン' => array(
		'button' => 'ボタン',
		'button2' => 'ボタン x2',
		'button3' => 'ボタン x3',
		'download-file' => 'ファイル<br>ダウンロード',
		'download-file2' => 'ファイル<br>ダウンロード x2',
		'download-file3' => 'ファイル<br>ダウンロード x3',
	),
	'カスタム区切り線' => array(
		'hr-tensen' => '点線（区切り線）',
		'hr-syasen'=> '斜線（区切り線）',
		'hr-grade-center'=> '中央グラデ（区切り線）',
		'hr-haikeihasen'=> '背景色あり破線（区切り線）',
	),
	'その他' => array(
		'table' => '2カラムテーブル',
		'gallery' => 'ギャラリー',
		'text-gallery1' => 'ギャラリー<small>+テキスト(左)</small>',
		'text-gallery2' => 'ギャラリー<small>+テキスト(右)</small>',
		'form' => 'メールフォーム',
		'form_2' => 'メールフォーム２',
		'form_3' => 'メールフォーム３',
		// 'google-maps' => 'GoogleMaps',
		'youtube' => 'YouTube',
		'embed' => '埋め込みタグ<small>script / form</small>',
		'hr' => '区切り線'
	)
);
