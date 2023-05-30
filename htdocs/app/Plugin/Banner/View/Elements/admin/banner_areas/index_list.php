<?php
/**
 * [BANNER] バナーエリア管理 一覧
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
?>
<!-- pagination -->
<?php $this->BcBaser->element('pagination') ?>

<table cellpadding="0" cellspacing="0" class="list-table sort-table bca-table-listup" id="ListTable">
	<thead class="bca-table-listup__thead">
		<tr>
			<th class="bca-table-listup__thead-th"><?php echo $this->Paginator->sort('id', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' NO',
					'desc' => '<i class="bca-icon--desc"></i>'.' NO'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php echo $this->Paginator->sort('name', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' 表示場所',
					'desc' => '<i class="bca-icon--desc"></i>'.' 表示場所'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php echo $this->Paginator->sort('width', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' 横幅',
					'desc' => '<i class="bca-icon--desc"></i>'.' 横幅'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php echo $this->Paginator->sort('height', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' 高さ',
					'desc' => '<i class="bca-icon--desc"></i>'.' 高さ'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<th class="bca-table-listup__thead-th"><?php echo $this->Paginator->sort('created', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' 登録日',
					'desc' => '<i class="bca-icon--desc"></i>'.' 登録日'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
				<br />
				<?php echo $this->Paginator->sort('modified', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' 更新日',
					'desc' => '<i class="bca-icon--desc"></i>'.' 更新日'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<th class="bca-table-listup__thead-th"><?php echo __d('baser', 'アクション') ?></th>
		</tr>
	</thead>
<tbody>
<?php if(!empty($datas)): ?>
	<?php foreach($datas as $data): ?>
		<?php $this->BcBaser->element('banner_areas/index_row', array('data' => $data)) ?>
	<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan="6"><p class="no-data">データがありません。</p></td>
	</tr>
<?php endif; ?>
	</tbody>
</table>

<!-- list-num -->
<?php $this->BcBaser->element('list_num') ?>
