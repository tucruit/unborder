<?php
$this->BcAdmin->addAdminMainBodyHeaderLinks([
	'url' => ['action' => 'add'],
	'title' => __d('baser', '新規追加'),
]);
$auto = Configure::read('CuSecurityPatch.auto');
?>
<?php if ($auto):?>
    <div class="pagination bca-pagination">
        <div class="page-numbers bca-page-numbers">
            <?php if (!empty($patches)) :?>
                <p>最終確認日時: <?php echo h(date( "Y-m-d H:i:s", strtotime($patches[0]['CuSecurityPatche']['modified']))) ?></p>
            <?php endif;?>
        </div>
    </div>
<?php endif;?>
<table class="list-table bca-table-listup" id="ListTable">
    <thead class="bca-table-listup__thead">
        <tr>
            <th class="bca-table-listup__thead-th">No</th>
            <th class="bca-table-listup__thead-th">公開日</th>
            <th class="bca-table-listup__thead-th">タイトル</th>
            <th class="bca-table-listup__thead-th">影響を受けるシステム<br>※baserCMSのバージョン</th>
            <th class="bca-table-listup__thead-th">パッチ<br>適用状況</th>
            <th class="bca-table-listup__thead-th">作成日<br>編集日</th>
            <th class="bca-table-listup__thead-th">アクション</th>
        </tr>
    </thead>
    <tbody class="bca-table-listup__tbody">
        <?php foreach($patches as $patch): ?>
        <tr class="<?php echo $patch['CuSecurityPatche']['done'] ? 'disablerow ' : ''; ?>bca-table-listup__tbody-tr" id="Row<?php echo h($patch['CuSecurityPatche']['id']) ?>">
            <td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--no"><?php echo h($patch['CuSecurityPatche']['id']) ?></td>
            <td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
				<?php if ($patch['CuSecurityPatche']['publish_date']): ?>
					<?php echo h(date("Y.m.d", strtotime($patch['CuSecurityPatche']['publish_date']))) ?>
				<?php endif; ?>
			</td>
            <td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--title">
				<?php if ($patch['CuSecurityPatche']['url']): ?>
					<?php $this->BcBaser->link($patch['CuSecurityPatche']['title'], $patch['CuSecurityPatche']['url'], ['target' => '_blank']) ?>
				<?php else: ?>
					<?php echo h($patch['CuSecurityPatche']['title']); ?>
				<?php endif; ?>
			</td>
            <td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--select">
				<?php echo $patch['CuSecurityPatche']['version'] ? h($patch['CuSecurityPatche']['version'] . ' 以前') : '' ?>
			</td>
            <td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--select"><?php echo $patch['CuSecurityPatche']['done'] == 1 ? h('済') : '' ?></td>
            <td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--date">
                <?php echo h(date("Y.m.d", strtotime($patch['CuSecurityPatche']['created']))) ?><br>
                <?php echo h(date("Y.m.d", strtotime($patch['CuSecurityPatche']['modified']))) ?>
            </td>
            <td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--action">
				<?php $this->BcBaser->link('', ['action' => 'edit', $patch['CuSecurityPatche']['id']], ['title' => __d('baser', '編集'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']) ?>
				<?php $this->BcBaser->link('', ['action' => 'delete', $patch['CuSecurityPatche']['id']], ['title' => __d('baser', '削除'), 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg', 'onclick' => "return confirm('削除します。よろしいですか？')"]) ?>
            </td>
        </tr>
        <?php $count =+ 1 ?>
        <?php endforeach ?>
    </tbody>
</table>

<div class="bca-data-list__bottom">
	<div class="bca-data-list__sub">
		<!-- pagination -->
		<?php $this->BcBaser->element('pagination') ?>
		<!-- list-num -->
		<?php $this->BcBaser->element('list_num') ?>
	</div>
</div>
<?php if (!$auto):?>
    <section class="bca-actions">
        <div class="bca-actions__main">
            <a href="update"><button type="button" class="button bca-btn bca-actions__item">公式サイトより情報を更新</button></a>
        </div>
    </section>
<?php endif;?>
