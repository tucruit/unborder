<?php
/**
 * [ADMIN] InstantPage一覧　行
 */
?>

<tr<?php $this->BcListTable->rowClass($this->InstantPage->allowPublish($data), $data) ?>>
	<td class="bca-table-listup__tbody-td"><?php echo $data['InstantPage']['id'] ?></td>
	<td class="bca-table-listup__tbody-td">
		<?php $this->BcBaser->link($data['InstantPage']['title'], ['action' => 'edit', $data['InstantPage']['id']], ['escape' => true]) ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['InstantPage']['name']); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['InstantPage']['instant_page_users_id']); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['InstantPage']['template']); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['InstantPage']['status']); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPage']['created']) ?><br>
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPage']['modified']) ?>
	</td>

	<?php echo $this->BcListTable->dispatchShowRow($data) ?>

	<td class="row-tools bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
		<?php
		$this->BcBaser->link('', ['action' => 'ajax_unpublish', $data['InstantPage']['id']], ['title' => __d('baser', '非公開'), 'class' => 'btn-unpublish bca-btn-icon', 'data-bca-btn-type' => 'unpublish', 'data-bca-btn-size' => 'lg']);
		$this->BcBaser->link('', ['action' => 'ajax_publish', $data['InstantPage']['id']], ['title' => __d('baser', '公開'), 'class' => 'btn-publish bca-btn-icon', 'data-bca-btn-type' => 'publish', 'data-bca-btn-size' => 'lg']);
		?>
		<?php /*if ($this->Blog->allowPublish($data)): //公開状態であれば 公開ページヘのリンク ?>
			<?php $this->BcBaser->link('', $this->request->params['Content']['url'] . '/archives/' . $data['InstantPage']['no'], ['title' => __d('baser', '確認'), 'target' => '_blank', 'class' => 'bca-btn-icon', 'data-bca-btn-type' => 'preview', 'data-bca-btn-size' => 'lg']); ?>
		<?php else: // 非公開であればボタンを押せなくする ?>
			<a title="確認" class="btn bca-btn-icon" data-bca-btn-type="preview" data-bca-btn-size="lg"
			   data-bca-btn-status="gray"></a>
		<?php endif */?>

		<?php
		$this->BcBaser->link('', ['action' => 'edit', $data['InstantPage']['id']], ['title' => __d('baser', '編集'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']);
		$this->BcBaser->link('', ['action' => 'ajax_delete', $data['InstantPage']['id']], ['title' => __d('baser', '削除'), 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg']) ?>
	</td>
</tr>
