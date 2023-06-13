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
			<?php
			if (!$freezed) {
				echo $field['control'];
			} else {
				if (strpos($field['field_name'], 'password_') !== false) {
					$filedValueText = '*****'. '<span style="display: none;">'.  $field['control']. '</span>';
				} else {
					$filedValueText = $field['control'];
				}
				echo !$field['raw']['no_send'] ? $filedValueText : '<span style="display: none;">'.  $field['control']. '</span>';
			}
			?>
			<?php //echo $freezed ? ''. $field['control']. '' : $field['control'] ; ?>
			<?php
			// baserCMSの仕様上 invalidate のエラーメッセージは出力されないため、再セットが必要
			$erros = $this->validationErrors['MailMessage'];
			if (!empty($erros)) {
				foreach ($erros as $key => $error) {
					if (strpos($key, $field['field_name']) !== false) {
						$errorMassage = implode(' ', $error);
						// validationErrorsでは、グループチェックフィールドは必須入力がバグっているため、除外
						if (strpos($errorMassage, '1 1') === false ){
							$field['error'] = $errorMassage;
						}
					}
				}
			}
			?>
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
						echo '<p class="error">'. h($field['error']).'</p>';
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
