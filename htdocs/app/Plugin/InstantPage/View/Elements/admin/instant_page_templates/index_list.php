<?php
/**
 * [ADMIN] InstantPage一覧　テーブル
 */
$this->BcListTable->setColumnNumber(7);
?>

<!-- pagination -->
<?php $this->BcBaser->element('pagination') ?>

<table cellpadding="0" cellspacing="0" class="list-table bca-table-listup" id="ListTable">
	<thead class="bca-table-listup__thead">
	<tr>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('id',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'No'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'No')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">スクリーンショット</th>
		<th class="bca-table-listup__thead-th">タイトル</th>
		<th class="bca-table-listup__thead-th">説明</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('name',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'name'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'name')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('user_id',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '登録者'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '登録者')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('user_id',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '利用数'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '利用数')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<?php echo $this->BcListTable->dispatchShowHead() ?>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('created',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '登録日'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '登録日')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?><br/>
			<?php echo $this->Paginator->sort('modified',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '更新日'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '更新日')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th"><?php echo __d('baser', 'アクション') ?></th>
	</tr>
	</thead>
	<tbody>
	<?php if (!empty($datas)): ?>
		<?php foreach($datas as $data): ?>
			<?php $this->BcBaser->element('instant_page_templates/index_row', ['data' => $data]) ?>
		<?php endforeach; ?>
	<?php else: ?>
		<tr>
			<td colspan="<?php echo $this->BcListTable->getColumnNumber() ?>">
				<p class="no-data"><?php echo __d('baser', 'データが見つかりませんでした。') ?></p></td>
		</tr>
	<?php endif; ?>
	</tbody>
</table>

<!-- list-num -->
<?php $this->BcBaser->element('list_num') ?>
