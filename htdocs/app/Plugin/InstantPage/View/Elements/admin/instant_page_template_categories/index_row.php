<?php
/**
 * [ADMIN] InstantPageTemplateCategory一覧行
 */
?>
<tr>
	<td class="bca-table-listup__tbody-td"><?php echo $data['InstantPageTemplateCategory']['id'] ?></td>
	<td class="bca-table-listup__tbody-td">
		<?php if ($data['InstantPageTemplateCategory']['image_1']) : ?>
			<img src="/img/instant_page_template_category/<?php echo $data['InstantPageTemplateCategory']['image_1'] ?>" alt="サムネイル" style="max-width: 100px;">
		<?php endif ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php
		echo $data['InstantPageTemplateCategory']['name'];
		?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php
		if (isset($themedatas[$data['InstantPageTemplateCategory']['name']]) && $themedatas[$data['InstantPageTemplateCategory']['name']]['description']) {
			echo nl2br(h(mb_strimwidth($themedatas[$data['InstantPageTemplateCategory']['name']]['description'], 0, 160, '...', 'utf8')));
		}
		?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPageTemplateCategory']['created']) ?><br>
		<?php echo $this->BcTime->format('Y-m-d H:i:s', $data['InstantPageTemplateCategory']['modified']) ?>
	</td>

	<td class="row-tools bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
		<?php
		$this->BcBaser->link('', ['action' => 'edit', $data['InstantPageTemplateCategory']['id']], ['title' => __d('baser', '編集'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']);
		$this->BcBaser->link('', ['action' => 'ajax_delete', $data['InstantPageTemplateCategory']['id']], ['title' => __d('baser', '削除'), 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg']);
		?>
	</td>
</tr>
