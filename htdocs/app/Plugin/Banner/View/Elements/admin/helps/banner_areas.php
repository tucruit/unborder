<?php
/**
 * [BANNER] バナーエリア管理 一覧 ヘルプ
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<?php if($this->request->action == 'admin_index'): ?>
<p>バナーエリアの管理が行えます。</p>
<ul>
	<li>新しいバナーエリアを登録するには、画面下の「新規追加」ボタンをクリックします。</li>
	<li>操作欄の <?php $this->BcBaser->img('admin/icn_tool_manage.png') ?> ボタンからは、指定エリアのバナーファイル管理画面へ移動できます。</li>
	<li>操作欄の <?php $this->BcBaser->img('admin/icn_tool_edit.png') ?> ボタンからは、バナーエリア設定の編集画面へ移動できます。</li>
</ul>
<?php else: ?>
<p>サイズチェック設定について</p>
<ul>
	<li>設定値より大きいサイズの画像をアップロードした場合、画像アップロード時にエラーを表示します。</li>
	<li>入力しなかった場合は、サイズチェックは行われずそのまま画像がアップロードされます。</li>
</ul>
<p>説明設定について</p>
<ul>
	<li>説明を利用にするにチェックした場合、バナー画像ごとに説明を設定することができます。</li>
</ul>
<?php endif ?>
