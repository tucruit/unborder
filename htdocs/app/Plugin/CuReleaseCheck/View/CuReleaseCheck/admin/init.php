<?php if (isset($result)) include dirname(__FILE__) . DS . 'exec.php'; ?>

<div style="margin-bottom: 50px;">
	<table cellpadding="0" cellspacing="0" class="list-table">
		<tr>
			<th>処理対象</th>
			<th>実行</th>
		</tr>

		<?php foreach($initList as $init): ?>
		<tr>
			<td><?php echo h($init->title()); ?></td>
			<td>
				<form action="<?php echo $this->BcBaser->getUrl(array('action' => 'exec', get_class($init))); ?>" method="get">
					<input type="submit" class="button-small" value="実行" />
				</form>
			</td>
		</tr>
		<?php endforeach; ?>

	</table>
</div>
