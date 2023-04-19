<?php
/**
 * [BANNER] バナー管理 ヘルプ
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<?php if($this->request->action == 'admin_index'): ?>
<p>バナーの管理が行えます。</p>
<ul>
	<li>新しいバナーを登録するには、画面下の「新規追加」ボタンをクリックします。</li>
	<li>操作欄の <?php $this->BcBaser->img('admin/icn_tool_edit.png') ?> ボタンからは、バナーの編集画面へ移動できます。</li>
</ul>
<?php else: ?>
<p>バナー追加について</p>
<ul>
	<li>バナーエリアの設定値より大きいサイズの画像をアップロードした場合、画像アップロード時にエラーを表示します。</li>
</ul>
<?php endif ?>
