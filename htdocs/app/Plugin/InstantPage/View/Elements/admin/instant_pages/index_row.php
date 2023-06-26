<?php
/**
 * [ADMIN] InstantPage一覧行
 */
$pageRoutes = configure::read('pageRoutes');
//p($data);
$userNmaes = Hash::combine($users, '{n}.InstantPageUser.id', '{n}.User.name');
$userRealNmaes = Hash::combine($users, '{n}.InstantPageUser.id', '{n}.User.real_name_1');
$instantPageUsersId = $data['InstantPage']['instant_page_users_id'];
$userUrl = isset($userNmaes[$instantPageUsersId]) ? h($userNmaes[$instantPageUsersId]) : '';
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
		<?php echo isset($userRealNmaes[$instantPageUsersId]) ? h($userRealNmaes[$instantPageUsersId]) : h($instantPageUsersId); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo h($data['InstantPageTemplate']['name']); ?>
	</td>
	<td class="bca-table-listup__tbody-td">
		<?php echo $data['InstantPage']['status'] ? '公開' : '非公開'; ?>
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
		<?php if ($this->InstantPage->allowPublish($data) && $userUrl): //公開状態であれば 公開ページヘのリンク ?>
			<?php

			$this->BcBaser->link('', $pageRoutes. $userUrl. '/' . $data['InstantPage']['name'], ['title' => __d('baser', '確認'), 'target' => '_blank', 'class' => 'bca-btn-icon', 'data-bca-btn-type' => 'preview', 'data-bca-btn-size' => 'lg']);
			?>
		<?php else: // 非公開であればボタンを押せなくする ?>
			<a title="確認" class="btn bca-btn-icon" data-bca-btn-type="preview" data-bca-btn-size="lg"
			   data-bca-btn-status="gray"></a>
		<?php endif ?>

		<?php
		$this->BcBaser->link('', ['action' => 'edit', $data['InstantPage']['id']], ['title' => __d('baser', '編集'), 'class' => ' bca-btn-icon', 'data-bca-btn-type' => 'edit', 'data-bca-btn-size' => 'lg']);
		$this->BcBaser->link('', ['action' => 'ajax_delete', $data['InstantPage']['id']], ['title' => __d('baser', '削除'), 'class' => 'btn-delete bca-btn-icon', 'data-bca-btn-type' => 'delete', 'data-bca-btn-size' => 'lg']) ?>
	</td>
</tr>
