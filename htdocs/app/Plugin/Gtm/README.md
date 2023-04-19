# baserCMS用 Google Tag Manager コンテナID設定プラグイン

baserCMS4系専用 
Google Tag Manager コンテナIDを管理画面から登録し、レイアウトテンプレート描画時に、headタグ内とbody開始タグ直後にelementを出力します。

## 仕様

### head内のタグ出力
\<head\>
タグに属性がなければ、その直後にhead用のGTMタグエレメントを出力し、  
属性があれば、
\<meta.*?charset=\".\*\> の直前に出力されます。  
テンプレートにすでにGTMタグがあれば、出力されません。
<pre>/gtm.start/i</pre>
の文字列で検索しています。(Config/setting.phpで変更できます）  

### body開始直後のタグ出力

\<body\>タグの直後にbody用のGTMタグエレメントを出力します。  
テンプレートにすでにGTMタグがあれば、出力されません。  
<pre>/iframe src\=\"https\:\/\/www\.googletagmanager\.com/i</pre>の文字列で検索しています。(Config/setting.phpで変更できます）  

### 自動出力の停止

出力タグの出現位置の変更や、preg_match_all()を複数回実行する処理を回避したい場合、自動出力を停止できます。  
（Config/setting.phpで変更できます）  
その場合、Helperのメソッドを使ってエレメント出力できます。  
head内GTMタグを出力する。 →　$this->Gtm->headGtm() で呼び出す。  
body直後のGTMタグを出力する。 →　$this->Gtm->bodyGtm() で呼び出す。  
※ コンテナIDは変数「$key」にセットされています。  

Config/setting.phpにて自動か手動かを指定出来ます。

## 確認済バージョン

|baserCMS|Plugin|status|comment|
|:--|:--|:--|:--|
|4.6.2|1.0.0|未承認||
|4.6.1.1|1.0.0|未承認||
|4.6.1|1.0.0|未承認||
|4.5.6|1.0.0|未承認||
|4.4.2|1.0.0|未承認||
