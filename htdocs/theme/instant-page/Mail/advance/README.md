# MailAdvanceテンプレート

## ファイル構造

以下のファイルセットが必要です。

theme/
	daimyo/
		Mail/
			advance/
				index.php 		 // 入力画面
				confirm.php 	 // 確認画面
				complate.php 	 // 完了画面
		Helper/
			ThemeMailHelper.php  // テンプレート用ヘルパー
		Elements/
			mail_advance/
				form_body.php 	 // フォーム本体（input/confirm共用）
				field_default.php // フィールドデフォルトテンプレート
				field_single.php // フィールドテンプレート
				field_group.php  // フィールドテンプレート（グループフィールド用）
				field_custom_....php // フィールドテンプレート（カスタムテンプレート）


## 仕様

以下の流れで動作します。
### 入力画面
index.php > form_body.php > ThemeMail::mailForm > field_single / field_group

＊確認画面の場合は起点がconfirm.phpとなります。

** index.php
BcBaser::element()でform_body.phpを呼び出します。

** form_body.php
ThemeMail::mailForm()をコールします。

** ThemeMail::mailForm()
フィールドデータを整形します。
整形が完了したら、オプションで渡されたテンプレートをコールし、１行ずつフィールドの書き出しを行います。

書き出しの際に以下の変数を渡します。

```
$data = [
	'group_name' => $group_name,
	'fields' => [
		0 => [
			'field_element_id' => $field_element_id
			'name' => $name,
			'field_name' => $field_name
			'require' => $require,
			'attention' => $attention,
			'type' => $type,
			'class' => $class,
			'before_attachment' => $before_attachment,
			'control' => $control,
			'controls' => $controls,
			'after_attachment' => $after_attachment,
			'error' => $error,
			'raw' => $raw
		]

	]
];
```

controlsは、各フィールドのinputとlabelを分解して配列で渡されます。
例：
```
<input type="radio" name="radio" value="hoge" id="radio1"><label for="radio1">hoge_text</label>
<input type="radio" name="radio" value="huga" id="radio2"><label for="radio2">huga_text</label>
```
↓
```
$controls = [
  0 => [
    'input' => <input type="radio" name="radio" value="hoge" id="radio1">,
    'label' => hoge_text
  ],
  1 => [
    'input' => <input type="radio" name="radio" value="huga" id="radio2">,
    'label' => huga_text
  ]

]
```


** field_default
$dataにて１行分のデータが渡されるのでHTML構造に沿ってテンプレートを構築します。

グループフィールのみ特別なテンプレートを当てたい場合は
templatesにfield_groupを定義し、テンプレートを作成していください。

また、templatesにcustom_{フィールドのname}を定義するとそのフィールドのみカスタムテンプレートが適応できます。
グループフィールドの場合は0番目のフィールドのname値もしくはグループ名を定義してください。