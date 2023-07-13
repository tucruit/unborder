<?php
/**
 * [ADMIN] InstantPageTemplate一覧行
 */
?>
<tr>
	<td class="bca-table-listup__tbody-td"><?php echo $data['InstantPageTemplate']['id'] ?></td>
	<td class="bca-table-listup__tbody-td">
		<?php
		if (isset($themedatas[$data['InstantPageTemplate']['name']]) && $themedatas[$data['InstantPageTemplate']['name']]['screenshot']) {
			$this->BcBaser->img('/theme/' . $data['InstantPageTemplate']['name'] . '/screenshot.png', ['alt' => $data['InstantPageTemplate']['name'], 'width' => '100px']);
		}
		?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php
		if (isset($themedatas[$data['InstantPageTemplate']['name']]) && $themedatas[$data['InstantPageTemplate']['name']]['title']) {
			echo h($themedatas[$data['InstantPageTemplate']['name']]['title']);
		}
		?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php
		if (isset($themedatas[$data['InstantPageTemplate']['name']]) && $themedatas[$data['InstantPageTemplate']['name']]['description']) {
			echo nl2br(h(mb_strimwidth($themedatas[$data['InstantPageTemplate']['name']]['description'], 0, 160, '...', 'utf8')));
		}
		?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php
		echo h($data['InstantPageTemplate']['name']);
		//$this->BcBaser->link($data['InstantPageTemplate']['name'], ['action' => 'edit', $data['InstantPageTemplate']['id']], ['escape' => true]);
		?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo isset($data['User']) ? h($data['User']['real_name_1']. ' '. $data['User']['real_name_2']) : ''; ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo isset($data['InstantPage']) ? count($data['InstantPage']) : ''; ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPageTemplate']['created']) ?><br>
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPageTemplate']['modified']) ?>
	</td>

	<?php echo $this->BcListTable->dispatchShowRow($data) ?>

	<td class="row-tools bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
		<?php
		if ($data['InstantPageTemplate']['id'] != 1) { // テーマが0件にならないように「default_gray」だけは残しておく
			$this->BcBaser->link('', ['action' => 'edit', $data['InstantPageTemplate']['id']], ['title' => __d('baser', '作成者変更'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']);
			$this->BcBaser->link('', ['action' => 'ajax_delete', $data['InstantPageTemplate']['id']], ['title' => __d('baser', '削除'), 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg']);
		}
		?>
	</td>
</tr>
