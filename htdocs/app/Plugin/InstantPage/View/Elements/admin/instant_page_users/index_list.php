<?php
/**
 * [ADMIN] ユーザー一覧　テーブル
 */
$this->BcListTable->setColumnNumber(11);
?>
<div class="bca-data-list__top">
<?php if ($this->BcBaser->isAdminUser()): ?>
	<div class="bca-action-table-listup">
		<?php echo $this->BcForm->input('ListTool.batch', ['type' => 'select', 'options' => ['del' => __d('baser', '削除')], 'empty' => __d('baser', '一括処理'), 'data-bca-select-size' =>'lg']) ?>
		<?php echo $this->BcForm->button(__d('baser', '適用'), ['id' => 'BtnApplyBatch', 'disabled' => 'disabled' , 'class' => 'bca-btn', 'data-bca-btn-size' => 'lg']) ?>
	</div>
<?php endif ?>
  <div class="bca-data-list__sub">
    <!-- pagination -->
    <?php $this->BcBaser->element('pagination') ?>
  </div>
</div>

<table cellpadding="0" cellspacing="0" class="list-table bca-table-listup" id="ListTable">
	<thead class="bca-table-listup__thead">
	<tr>
		<th class="list-tool bca-table-listup__thead-th  bca-table-listup__thead-th--select">
			<?php echo $this->BcForm->input('ListTool.checkall', ['type' => 'checkbox', 'label' => __d('baser', '一括選択')]) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('id',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'No'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'No')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('company',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '会社名'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '会社名')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('name',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'ログインID'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'ログインID')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('real_name_1',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'お名前'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'お名前')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('email',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'メールアドレス'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'メールアドレス')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('plan_id',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'プラン'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'プラン')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('creator_flg',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', 'クリエイター'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', 'クリエイター')
				],
				['escape' => false, 'class' => 'btn-direction bca-table-listup__a']) ?>
		</th>
		<th class="bca-table-listup__thead-th">
			<?php echo $this->Paginator->sort('prefecture_id',
				[
					'asc' => '<i class="bca-icon--asc"></i>' . __d('baser', '都道府県'),
					'desc' => '<i class="bca-icon--desc"></i>' . __d('baser', '都道府県')
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
			<?php $this->BcBaser->element('instant_page_users/index_row', ['data' => $data]) ?>
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
