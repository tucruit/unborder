<?php if (isset($result)) include dirname(__FILE__) . DS . 'check.php'; ?>


<div style="margin-bottom: 50px;">
	<table cellpadding="0" cellspacing="0" class="list-table">
		<tr>
			<th>チェック対象</th>
			<th>実行</th>
		</tr>

		<?php foreach($testList as $test): ?>
		<tr>
			<td><?php echo h($test->title()); ?></td>
			<td>
				<form action="<?php echo $this->BcBaser->getUrl(array('action' => 'check', get_class($test))); ?>" method="get">
					<input type="submit" class="button-small" value="実行" />
				</form>
			</td>
		</tr>
		<?php endforeach; ?>

	</table>
</div>

