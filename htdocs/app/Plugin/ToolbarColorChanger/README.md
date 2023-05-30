管理画面ツールバーの色を設定するbaserプラグインです。

install.overwrite.phpの条件分岐でそれぞれの環境別にツールバーの背景色を設定してください。

```
if ローカル環境
	Configure::write('ToolbarColorChanger.background', '#aaaaaa');
elseif デモ環境
	Configure::write('ToolbarColorChanger.background', '#bbbbbb');
else
	Configure::write('ToolbarColorChanger.background', '#cccccc');
endif
```
