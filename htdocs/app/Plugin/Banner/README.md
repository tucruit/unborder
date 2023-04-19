# Banner プラグイン

Banner プラグインは、バナーエリア別にバナーを管理できるbaserCMS専用のプラグインです。

## Installation

1. 圧縮ファイルを解凍後、BASERCMS/app/Plugin/Banner に配置します。
2. 管理システムのプラグイン管理にアクセスし、表示されている Banner プラグイン をインストール（有効化）して下さい。
3. バナーエリア別に、バナーを追加してください。


### Use Sample

公開側での利用サンプルは以下を参照してください。

```
<?php $this->Banner->showBanner($bannerAreaName, $options); ?>
```

- $bannerAreaName: 文字列 - バナーエリア名
- $options: 配列
    - 'num' => 0: 表示する数
    - 'template' => 'banner_block': エレメントファイル名


## 確認済バージョン

|baserCMSバージョン|プラグインバージョン|ステータス|コメント|
|:--|:--|:--|:--|
|3系|-|承認済|動作可|
|4.0.6|3.0.1|承認|動作可|
|4.0.7|3.0.1|承認|動作可|
|4.0.8|3.0.1|承認|動作可|
|4.0.8|3.0.2|承認|動作可|
|4.0.8|3.0.3|承認|動作可|
|4.0.9|3.0.3|承認|動作可|
|4.0.9|3.0.4|承認|動作可|
|4.2.0|4.0.0|承認|動作可|
|4.3.4|4.1.1|承認|動作可|


## Thanks ##

- [http://basercms.net](http://basercms.net/)
- [http://wiki.basercms.net/](http://wiki.basercms.net/)
- [http://doc.basercms.net/](http://doc.basercms.net/)
- [http://cakephp.jp](http://cakephp.jp)
- [Semantic Versioning 2.0.0](http://semver.org/lang/ja/)
