<?php
/**
 * [ADMIN] パートナー企業一覧　テーブル
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
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('name',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '企業名'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '企業名')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('type',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'パートナー種別'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'パートナー種別')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('address',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '住所'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '住所')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('area_id',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'エリア'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'エリア')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('url',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'URL'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'URL')
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
			<?php $this->BcBaser->element('instant_pages/index_row', ['data' => $data]) ?>
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
