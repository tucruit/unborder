BurgerEditor スタイルシートの変更方法
=================================

BurgerEditorではレイアウトと初期サンプルスタイルが書かれたCSSファイルを自動的に読み込みます。

- `Plugin/BurgerEditor/webroot/css/bge_style_default.css`
	- レイアウトが書かれたCSSです。
	- マージン・ブレークポイントなどの変更が必要な場合は次の`bge_style.css`でCSSのカスケードを利用してプロパティを上書きしてください。
	- このファイルを変更することはおすすめしません。
- `Plugin/BurgerEditor/webroot/css/bge_style.css`
	- 初期サンプルスタイルが書かれたCSSです。
	- 基本的にすべて書き換えることを推奨します。
	- 管理画面側にスタイルを反映するにはこのファイルにそのスタイルが記述されている必要があります。

スタイルを変更する方法として主に2つあります。

- A) 別のスタイルシートファイルを後読みし上書きする（ただし管理画面にはスタイルが反映されない）
- B) テーマフォルダに設置する

本ドキュメントでは B について詳しく解説します。

* * *

## テーマフォルダに設置する

BurgerEditorではプラグインのフォルダ内の `Plugin/BurgerEditor/webroot/css/bge_style.css` を自動的に読み込みますが、テーマ側のcssフォルダに `bge_style.css` がある場合、プラグイン内のものは読み込まれずにテーマ側が優先させれて読み込まれます。

`Plugin/BurgerEditor/webroot/css/` にあるCSSファイルを直接変更しても反映はされますが、アップデート時に誤って上書きしてしまう恐れがあるなどオススメできませんので上書き用として準備してある `Plugin/BurgerEditor/stylesheets/bge_style.css` を `webroot/theme/[テーマ名]/css/` へコピーし、それを書き換えることでプラグイン本体のファイルには影響なくスタイルを変更することができます。

### SASSを利用していない場合

上記のとおり `Plugin/BurgerEditor/stylesheets/bge_style.css` を `webroot/theme/[テーマ名]/css/` へコピーし、それを書き換えることでスタイルを変更できます。

### SASSを利用している場合

SASSファイル（SCSS形式）を用意していますので、スタイルの共通部分などを **変数** から変更可能です。 `Plugin/BurgerEditor/stylesheets/sass/settings.scss` に変数はまとめられています。

任意の場所にコピーするか、直接パスを `@import` で読み込み、自由にスタイルを調整してください。
