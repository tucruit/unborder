<?php foreach($data['fields'] as $field): ?>
	<?php if($field['class'] == 'hidden') :  ?>
		<tr style="display: none;"><th></th><td><?php echo $field['control'] ?></td></tr>
	<?php else:?>
		<tr>
			<th><?php echo $field['head']; ?><?php echo $field['require'] ?'<span class="mod-form-hissuTag">必須</span>': '' ?></th>
			<td class="form4">
				<label for="<?php echo $field['field_element_id']?>">
					<?php
					echo $freezed ? '<p>' : '';
					echo $field['control'];
					echo $freezed ? '</p>' : '';
					?>
					</label>
				<?php if($field['error']): ?>
					<p class="error"><?php echo h($field['name']) ?>をご入力ください</p>
				<?php endif; ?>
				<?php // 注意書き
				if (!$freezed) {
					echo $field['attention']?  '<p class="error"><span>'. strip_tags($field['attention'], '<br>').'</span></p>' : '';
				}
				?>
			</td><!-- /form4 -->
		</tr>
	<?php endif;?>
<?php endforeach; ?>
