<?php
/**
 * [ADMIN] 設定初期化
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
?>
<?php echo $this->BcForm->create('OptionalLinkConfig', array('action' => 'first')); ?>
<?php echo $this->BcForm->input('OptionalLinkConfig.active', array('type' => 'hidden', 'value' => '1')); ?>
<table cellpadding="0" cellspacing="0" class="form-table section" id="ListTable">
	<tr>
		<th class="col-head">
			はじめに<br />お読み下さい。
		</th>
		<td class="col-input">
			<strong>オプショナルリンク設定データ作成では、各ブログ用のオプショナルリンク設定データを作成します。</strong>
			<ul>
				<li>オプショナルリンク設定データがないブログ用のデータのみ作成します。</li>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
	<?php echo $this->BcForm->submit('作成する', array(
		'div' => false,
		'class' => 'btn-red button',
		'id' => 'BtnSubmit',
		'onClick'=>"return confirm('オプショナルリンク設定データの作成を行いますが良いですか？')")); ?>
</div>
<?php echo $this->BcForm->end(); ?>
