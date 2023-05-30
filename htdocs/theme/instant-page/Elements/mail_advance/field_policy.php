<?php foreach($data['fields'] as $field): ?>
	<p<?php echo $freezed ? ' style="display: none;"' : '' ?>>
		<label for="<?php echo $field['field_element_id']?>"><?php echo strip_tags($field['control'], '<input><label>'); ?></label>
	</p>
	<?php if($field['error']): ?>
		<p class="error"><?php echo h($field['name']) ?>が必要です</p>
	<?php endif; ?>
<?php endforeach; ?>
