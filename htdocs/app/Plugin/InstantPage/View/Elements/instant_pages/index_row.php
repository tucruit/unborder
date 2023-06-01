<?php
/**
 * [ADMIN] InstantPage一覧 行
 */
?>

<tr>
	<td class="bca-table-listup__tbody-td"><?php echo $data['InstantPage']['id'] ?></td>
	<td class="bca-table-listup__tbody-td">
		<?php $this->BcBaser->link($data['InstantPage']['name'], ['action' => 'edit', $data['InstantPage']['id']], ['escape' => true]) ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo str_replace('|', "<br>", h($data['InstantPage']['type'])) ; ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['InstantPage']['address']); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['InstantPage']['url']); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPage']['created']) ?><br>
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPage']['modified']) ?>
	</td>
	<td class="row-tools bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
		<?php
		$this->BcBaser->link('', ['action' => 'edit', $data['InstantPage']['id']], ['title' => __d('baser', '編集'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']);
		$this->BcBaser->link('', ['action' => 'ajax_delete', $data['InstantPage']['id']], ['title' => __d('baser', '削除'), 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg']) ?>
	</td>
</tr>
