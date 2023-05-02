<?php
/*
 * グループフィールド
 */
?>
<tr>
	<th><?php echo $data['fields'][0]['head']; ?><?php echo $data['fields'][0]['require'] ?'<span class="mod-form-hissuTag">必須</span>': '' ?></th>
	<td>
		<?php
		$groupClass = $data['fields'][0]['raw']['group_field'] == 'addresses' ? 'inputWrap__address' : 'inputWrap__name';
		?>
		<div class="<?php echo $groupClass ?>">
		<?php foreach($data['fields'] as $field): ?>
			<?php echo $freezed ? ''. $field['control']. '' : $field['control'] ; ?>
			<?php if($field['error']): ?>
				<?php
				switch ($field['name']) {
					case 'セイ':
						echo '<p class="error">姓のフリガナをご入力ください</p>';
						break;
					case 'メイ':
						echo '<p class="error">名のフリガナをご入力ください</p>';
						break;

					default:
						echo '<p class="error">'. h($field['name']).'をご入力ください</p>';
						# code...
						break;
				}
				?>
			<?php endif; ?>
			<?php // 注意書き
			if (!$freezed) {
				echo $field['attention']?  '<p class="error"><span>'. strip_tags($field['attention'], '<br>').'</span></p>' : '';
			}
			?>
		<?php endforeach; ?>
		</div>
	</td>
</tr>
