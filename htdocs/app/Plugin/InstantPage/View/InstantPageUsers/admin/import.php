<?php
/**
 * インスタントページユーザーCSV一括登録
 *
 */
$noCompanyList = [];
?>
<?php echo $this->BcForm->create('InstantPageUser', array('type' => 'file')) ?>
<div class="section">
<table cellpadding="0" cellspacing="0" class="form-table bca-form-table">
	<tbody>
		<tr>
			<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('InstantPageUser.file', 'CSVアップロード') ?></th>
			<td class="col-input bca-form-table__input">
				<?php echo $this->BcForm->file('InstantPageUser.file', array('type' => 'file')) ?>
				<?php echo $this->BcForm->error('InstantPageUser.file') ?>
				<br /><small>CSVファイルをアップロードしてください。※100件ずつ推奨</small>
			</td>
		</tr>
		<tr>
			<th class="col-head bca-form-table__label">
				<?php echo $this->BcForm->label('InstantPageUser.password', __d('baser', '共通パスワード')) ?>
			</th>
			<td class="col-input bca-form-table__input">
				<!-- ↓↓↓自動入力を防止する為のダミーフィールド↓↓↓ -->
        <input type="password" name="dummypass" style="top:-100px;left:-100px;position:fixed;" />
				<?php echo $this->BcForm->input('InstantPageUser.password', ['type' => 'password', 'size' => 20, 'maxlength' => 255]) ?>
				<i class="bca-icon--question-circle btn help bca-help"></i>
				<?php echo $this->BcForm->error('InstantPageUser.password') ?>
				<div id="helptextPassword" class="helptext">
					<ul>
						<li><?php echo __d('baser', '半角英数字(英字は大文字小文字を区別)とスペース、記号(._-:/()#,@[]+=&;{}!$*)のみで入力してください') ?></li>
						<li><?php echo __d('baser', '最低６文字以上で入力してください') ?></li>
					</ul>
				</div>
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

<?php if ($addUserList): ?>
	<h3>追加ユーザー結果</h3>
	<ul>
		<?php foreach ($addUserList as $addUser): ?>
			<?php if ($addUser['company']):?>
				<li>ログインID: <?php echo h($addUser['InstantPageUser']['name']); ?>、担当者名: <?php echo h($addUser['InstantPageUser']['real_name_1']); ?> <?php echo h($addUser['InstantPageUser']['real_name_2']); ?>、 顧客名:  <?php echo h($addUser['company']); ?></li>
			<?php else:?>
				<?php $noCompanyList[] = $addUser ?>
			<?php endif;?>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>
<?php if (!empty($noCompanyList)): ?>
	<h3>追加ユーザー（企業登録なし）一覧</h3>
	<ul>
		<?php foreach ($noCompanyList as $addUser): ?>
				<li>ログインID: <?php echo $addUser['InstantPageUser']['name']; ?>、担当者名: <?php echo $addUser['InstantPageUser']['real_name_1']; ?> <?php echo $addUser['InstantPageUser']['real_name_2']; ?>はパートナー企業が登録されていません。</li>
		<?php endforeach; ?>
	</ul>
<?php endif; ?>

<div class="submit section bca-actions">
	<div class="bca-actions__main">
	<?php echo $this->BcForm->submit('アップロード', array('class' => 'button bca-btn bca-actions__item', 'onClick'=>"return confirm('アップロードします。  よろしいですか？')")) ?>
</div>
</div>

<?php echo $this->BcForm->end() ?>
