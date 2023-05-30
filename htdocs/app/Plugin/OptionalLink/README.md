# オプショナルリンク プラグイン

OptionalLink プラグインは、ブログ記事に任意のURLを設定できる入力欄を追加できるbaserCMS専用のプラグインです。


## Installation

1. 圧縮ファイルを解凍後、BASERCMS/app/Plugin/OptionalLink に配置します。
2. 管理システムのプラグイン管理に入って、表示されている OptionalLink プラグイン を有効化して下さい。
3. プラグインの有効化後、システムナビの「オプショナルリンク プラグイン」の設定一覧へ移動し、利用するブログを追加し、有効化を行なってください。
4. 利用が有効なブログ記事の投稿画面にアクセスすると、入力項目が追加されてます。
5. アップロードファイルについては、拡張子が以下のものを許可しています。
　　jpg, png, gif, ico, pdf, zip, svg, csv, doc, docx, ppt, pptx, xls, xlsx, txt
　　それ以外の拡張子を許可する場合は、/app/Plugin/OptionalLink/Config/setting_customize.php.default を setting_customize.php にリネームして 　　許可したい拡張子を追加して下さい。


### テーマ側での調整について

- 利用テーマ内で、ブログ記事一覧を表示する箇所で調整を伴います。以下はbc_sampleテーマでの利用例です。
    - 例: /app/webroot/theme/bc_sample/Blog/default/index.php

```
<?php if (!empty($posts)): ?>
	<?php
		$optionalLink = false;
		if (in_array('OptionalLink', Configure::read('BcStatus.enablePlugins'), true)) {
			$optionalLink = true;
		}
	?>
	<?php foreach ($posts as $post): ?>
	<?php
		$postUrl = $this->Blog->getPostLinkUrl($post);
		$target = '';
		if ($optionalLink) {
			$postUrl = $this->OptionalLink->getPostUrl($post);
			$target = $this->OptionalLink->getPostTarget($post);
		}
	?>
	<article class="bs-blog-post__item clearfix">
		<?php if(!empty($post['BlogPost']['eye_catch'])): ?>
		<?php echo $postUrl ? '<a href="'. h($postUrl). '" '. $target. ' class="bs-blog-post__item-eye-catch">' : ''; ?>
			<?php $this->Blog->eyeCatch($post, ['width' => 150, 'link' => false]) ?>
		<?php echo $postUrl ? '</a>' : '' ?>
		<?php endif ?>
		<span class="bs-blog-post__item-date"><?php $this->Blog->postDate($post, 'Y.m.d') ?></span>
		<?php $this->Blog->category($post, ['class' => 'bs-blog-post__item-category']) ?>
		<span class="bs-blog-post__item-title">
			<?php echo $postUrl ? '<a href="'. h($postUrl). '" '. $target. '>' : ''; ?>
				<?php $this->Blog->postTitle($post, false) ?>
			<?php echo $postUrl ? '</a>' : '' ?>
		</span>
		<?php if(strip_tags($post['BlogPost']['content'] . $post['BlogPost']['detail'])): ?>
		<div class="bs-top-post__item-detail"><?php $this->Blog->postContent($post, true, false, 46) ?>...</div>
		<?php endif ?>
	</article>
	<?php endforeach; ?>
<?php else: ?>
<p class="bs-blog-no-data"><?php echo __('記事がありません。'); ?></p>
<?php endif ?>
```


## Uses Config

オプショナルリンク設定画面では、ブログ別に以下の設定を行う事ができます。
- オプショナルリンクの利用の有無を選択できます。

### ファイルの公開期間利用について

- ファイルアップロードに必要なファイルやフォルダは、インストール時は自動生成されます。
- ファイルアップロードに必要なファイルやフォルダが存在しない場合、オプショナルリンク設定画面にアラートが表示されます。
  - 管理システムにログイン状態で /admin/optional_link/optional_link_configs/init_folder にアクセスすると、ファイルの公開期間制限に必要なファイルとフォルダが生成されます。

### 留意点

- ブログ記事が大量（1,000件〜）に存在する場合、著しく動作が遅くなる可能性があります。  
その場合は、optional_links テーブル内の blog_post_id、blog_content_id にインデックスを作成すると改善する場合があります。
- 記事リンクの設定がある場合の記事詳細URLにアクセスした場合、設定URLにリダイレクトします。
- ブログ記事にURL設定 or ファイルリンク設定 or リンクナシ設定の場合、検索コンテンツには登録されません。


## CU確認済バージョン

|baserCMSバージョン|プラグインバージョン|ステータス|コメント|
|:--|:--|:--|:--|
|4.0.9|3.0.0|未承認|動作可|
|4.0.9|3.0.1|未承認|動作可|
|4.0.10|3.0.1|未承認|動作可|
|4.0.11|3.0.1|未承認|動作可|
|4.0.11|3.0.2|未承認|動作可|
|4.1.2|3.0.3|未承認|動作可|
|4.1.5|3.0.4|未承認|動作可(PHP7.2 NG)|
|4.1.5|3.0.5|未承認|動作可(PHP7.2 OK)|
|4.2.0|3.0.3|未承認|admin-second対応|
|4.3.4|4.0.0|未承認|admin-thrid対応|
|4.6.0|4.1.4|未承認|4.6.0のBcUploadBehaviorに対応|


## 影響Model

|対象Model|アソシエーション|関連モデル|備考|
|:--|:--|:--|:--|
|BlogPost|hasOne|OptionalLink|管理画面: 編集/保存/削除|
|BlogPost|hasOne|OptionalLink|公開画面: 一覧/詳細|
|BlogContent|hasOne|OptionalLinkConfig|管理画面: 編集/保存/削除|
|BlogContent|hasOne|OptionalLinkConfig|公開画面: 一覧/詳細|


## Thanks

- [http://basercms.net/](http://basercms.net/)
- [http://wiki.basercms.net/](http://wiki.basercms.net/)
- [http://cakephp.jp](http://cakephp.jp)
- [Semantic Versioning 2.0.0](http://semver.org/lang/ja/)


### TODO

- 日本語ファイル名の扱いを検討する
