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
	'ボタン' => array(
		'button' => 'ボタン',
		'button2' => 'ボタン x2',
		'button3' => 'ボタン x3',
		'download-file' => 'ファイル<br>ダウンロード',
		'download-file2' => 'ファイル<br>ダウンロード x2',
		'download-file3' => 'ファイル<br>ダウンロード x3',
	),
	'その他' => array(
		'table' => '2カラムテーブル',
		'gallery' => 'ギャラリー',
		'text-gallery1' => 'ギャラリー<small>+テキスト(左)</small>',
		'text-gallery2' => 'ギャラリー<small>+テキスト(右)</small>',
		'google-maps' => 'GoogleMaps',
		'youtube' => 'YouTube',
		'embed' => '埋め込みタグ<small>script / form</small>',
		'hr' => '区切り線'
	)
);
