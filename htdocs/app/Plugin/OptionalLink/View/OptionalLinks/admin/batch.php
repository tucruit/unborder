<?php
/**
 * [ADMIN] オプショナルリンク一括設定
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
?>
<?php echo $this->BcForm->create('OptionalLink', array('url' => array('action' => 'batch'))); ?>
<table cellpadding="0" cellspacing="0" class="list-table" id="ListTable">
	<tr>
		<th class="col-head" style="width:20%;">はじめに<br />お読み下さい。</th>
		<td class="col-input">
			<strong>オプショナルリンク一括設定では、ブログ別にオプショナルリンクを一括で登録できます。</strong>
			<ul>
				<li>オプショナルリンクの登録がないブログ記事用のオプショナルリンクを登録します。</li>
			</ul>
		</td>
	</tr>
	<tr>
		<th class="col-head">ブログの指定</th>
		<td class="col-input">
			<?php if($blogContentDatas): ?>
				<?php echo $this->BcForm->input('OptionalLink.blog_content_id', array('type' => 'select', 'options' => $blogContentDatas)); ?>
			<?php else: ?>
				ブログがないために設定できません。
			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<th class="col-head">オプショナルリンクの未登録状況</th>
		<td class="col-input">
			<ul>
			<?php foreach ($registerd as $value): ?>
				<li><?php echo $value['name']; ?>：
					<span class="large"><strong><?php echo $value['count']; ?> 件</strong></span></li>
			<?php endforeach ?>
			</ul>
		</td>
	</tr>
</table>

<div class="submit">
	<?php if($blogContentDatas): ?>
		<?php echo $this->BcForm->submit('一括設定する', array(
			'div' => false,
			'class' => 'btn-red button',
			'id' => 'BtnSubmit',
			'onClick'=>"return confirm('オプショナルリンクの一括設定を行いますが良いですか？')")); ?>
	<?php else: ?>
		ブログがないために設定できません。
	<?php endif ?>
</div>
<?php echo $this->BcForm->end(); ?>
