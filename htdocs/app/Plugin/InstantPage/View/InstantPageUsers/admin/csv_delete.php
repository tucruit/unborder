<?php
/**
 * ユーザー用ビュー
 *
 */
?>
<?php echo $this->BcForm->create('InstantPageUser', array('type' => 'file')) ?>
<div class="section">
<table cellpadding="0" cellspacing="0" class="form-table bca-form-table">
	<tbody>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.file', 'CSVアップロード') ?></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->file('InstantPageUser.file', array('type' => 'file')) ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<?php echo $this->BcForm->error('InstantPageUser.file') ?>
				<div id="helptextInstantPageUserFile" class="helptext">
					<ul>
						<li><?php echo __d('baser', '該当するユーザーが削除されます。') ?></li>
						<li><?php echo __d('baser', '削除したデータは戻すことができません') ?></li>
					</ul>
				</div>
				<br /><small>CSVファイルをアップロードしてください。※100件ずつ推奨</small>
			</td>
		</tr>
	</tbody>
</table>
</div>

<?php if ($messageList): ?>
	<h3>実行結果</h3>
	<ul>
	<?php foreach ($messageList as $message): ?>
		<li><?php echo h($message); ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

<?php if ($deleteUserList): ?>
	<h3>削除ユーザー結果</h3>
	<ul>
	<?php foreach ($deleteUserList as $deleteUser): ?>
		<li>ログインID: <?php echo $deleteUser['メールアドレス']; ?><?php echo isset($deleteUser['担当者名']) ? '、担当者名: '. $deleteUser['担当者名']: '' ; ?> <?php echo isset($deleteUser['担当者名カナ']) ? $deleteUser['担当者名カナ'] : ''; ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>

<div class="submit section bca-actions">
	<div class="bca-actions__main">
	<?php echo $this->BcForm->submit('アップロード', array('class' => 'button bca-btn bca-actions__item', 'onClick'=> 'return confirm("アップロードされた担当者を全て削除します。よろしいですか？\n※ 削除したデータは元に戻すことができません。")')) ?>
</div>
</div>

<?php echo $this->BcForm->end() ?>
