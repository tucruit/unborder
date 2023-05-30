<?php
/**
 * [BANNER] バナー管理 一覧
 *
 * @copyright		Copyright 2014 - 2018, D-ZERO Co.,LTD.
 * @link			http://www.d-zero.co.jp/
 * @package			Banner
 * @license			MIT
 */
$this->BcListTable->setColumnNumber(6);
?>
<style>
	.cboxElement img {
		max-width: 200px;
	}
</style>

<!-- pagination -->
<?php $this->BcBaser->element('pagination') ?>

<table cellpadding="0" cellspacing="0" class="list-table sort-table bca-table-listup" id="ListTable">
	<thead class="bca-table-listup__thead">
		<tr>
			<th class="list-tool bca-table-listup__thead-th bca-table-listup__thead-th--select">
				<div>
					<?php if (!$sortmode): ?>
						<?php $this->BcBaser->link('<i class="bca-btn-icon-text" data-bca-btn-type="draggable"></i>' . __d('baser', '並び替え'), [$bannerArea['BannerArea']['id'], 'sortmode' => 1]) ?>
					<?php else: ?>
						<?php $this->BcBaser->link('<i class="bca-btn-icon-text" data-bca-btn-type="draggable"></i>' . __d('baser', 'ノーマル'), [$bannerArea['BannerArea']['id'], 'sortmode' => 0]) ?>
					<?php endif ?>
				</div>
			</th>
<?php if(!$sortmode): ?>
			<th class="bca-table-listup__thead-th"><?php echo $this->Paginator->sort('no', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' NO',
					'desc' => '<i class="bca-icon--desc"></i>'.' NO'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php echo $this->Paginator->sort('name', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' バナー',
					'desc' => '<i class="bca-icon--desc"></i>'.' バナー'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
				<br />
				<?php echo $this->Paginator->sort('name', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' ファイル名',
					'desc' => '<i class="bca-icon--desc"></i>'.' ファイル名'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<th class="bca-table-listup__thead-th">
				<?php echo $this->Paginator->sort('alt', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' ALT',
					'desc' => '<i class="bca-icon--desc"></i>'.' ALT'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
				<br />
				<?php echo $this->Paginator->sort('url', array(
					'asc' => '<i class="bca-icon--asc"></i>'.' リンク先URL',
					'desc' => '<i class="bca-icon--desc"></i>'.' リンク先URL'),
					array('escape' => false, 'class' => 'btn-direction bca-table-listup__a')) ?>
			</th>
			<?php echo $this->BcListTable->dispatchShowHead() ?>
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
<?php else: ?>
			<?php echo $this->BcForm->input('Sort.bannnerId', array('type'	=> 'hidden', 'class' => 'id', 'value' => $bannerArea['BannerArea']['id'])) ?>
			<th class="bca-table-listup__thead-th">NO</th>
			<th class="bca-table-listup__thead-th">バナー<br />ファイル名</th>
			<th class="bca-table-listup__thead-th">ALT<br />リンク先URL</th>
			<?php echo $this->BcListTable->dispatchShowHead() ?>
			<th class="bca-table-listup__thead-th">登録日<br />更新日</th>
<?php endif ?>
			<th class="bca-table-listup__thead-th"><?php echo __d('baser', 'アクション') ?></th>
		</tr>
	</thead>
<tbody>
<?php if(!empty($datas)): ?>
	<?php foreach($datas as $data): ?>
		<?php $this->BcBaser->element('banner_files/index_row', array('data' => $data)) ?>
	<?php endforeach; ?>
<?php else: ?>
	<tr>
		<td colspan="<?php echo $this->BcListTable->getColumnNumber() ?>"><p class="no-data">データがありません。</p></td>
	</tr>
<?php endif; ?>
	</tbody>
</table>

<!-- list-num -->
<?php $this->BcBaser->element('list_num') ?>
