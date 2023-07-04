<?php
/**
 * [ADMIN] ユーザー一覧　行
 */
if(!isset($data['InstantPageUser'])) {
	$data['InstantPageUser'] = $data;
}
$planIds =  Configure::read('InstantPage.plan_id');
?>

<tr>
	<td class="bca-table-listup__tbody-td bca-table-listup__tbody-td--select"><?php // 選択 ?>
		<?php
		if ($this->BcBaser->isAdminUser()) {
			echo $this->BcForm->input('ListTool.batch_targets.' . $data['InstantPageUser']['id'], ['type' => 'checkbox', 'label'=> '<span class="bca-visually-hidden">' . __d('baser', 'チェックする') . '</span>', 'class' => 'batch-targets bca-checkbox__input', 'value' => $data['InstantPageUser']['id']]);
		}
		?>
	</td>

	<td class="bca-table-listup__tbody-td"><?php echo $data['InstantPageUser']['id'] ?></td>
	<td class="bca-table-listup__tbody-td"><?php echo $data['InstantPageUser']['company'] ?></td>
	<td class="bca-table-listup__tbody-td"><?php echo h($data['User']['name']) ?></td>
	<td class="bca-table-listup__tbody-td"><?php $this->BcBaser->link($data['User']['real_name_1']. ' '. $data['User']['real_name_2'], ['action' => 'edit', $data['InstantPageUser']['id']], ['escape' => true]) ?></td>
	<td class="bca-table-listup__tbody-td"><?php echo h($data['User']['email']) ?></td>
	<td class="bca-table-listup__tbody-td"><?php echo $planIds[$data['InstantPageUser']['plan_id']]; ?></td>
	<td class="bca-table-listup__tbody-td"><?php echo $this->BcText->arrayValue($data['InstantPageUser']['prefecture_id'], $this->BcText->prefList()) ?></td>
	<?php echo $this->BcListTable->dispatchShowRow($data) ?>
	<td class="bca-table-listup__tbody-td"><?php echo $this->BcTime->format('Y-m-d', $data['InstantPageUser']['created']) ?><br>
		<?php echo $this->BcTime->format('Y-m-d', $data['InstantPageUser']['modified']) ?></td>
		<td class="row-tools bca-table-listup__tbody-td bca-table-listup__tbody-td--actions">
			<?php $this->BcBaser->link('', ['action' => 'edit', $data['InstantPageUser']['id']], ['title' => __d('baser', '編集'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']) ?>
			<?php $this->BcBaser->link('', ['action' => 'ajax_delete', $data['InstantPageUser']['id']], ['title' => __d('baser', '削除'), 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg']) ?>
		<?php /*// 代理ログイン
		// http://instant-page.localhost:8888/cmsadmin/users/ajax_agent_login/4
		// http://instant-page.localhost:8888/cmsadmin/users/ajax_agent_login/4
		if (!$this->BcBaser->isAdminUser($data['User']['user_group_id'])) {
			$this->BcBaser->link('', '/cmsadmin/users/ajax_agent_login/'. $data['User']['id'],
				[
					'title' => __d('baser', 'ログイン'),
					'class' => 'btn-login bca-btn-icon',
					'data-bca-btn-type' => 'switch',
					'data-bca-btn-size' => 'lg'
				]);
		}
		*/?>
	</td>
</tr>
