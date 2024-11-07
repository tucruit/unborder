<div class="cc-form-fieldset" id="Group<?php echo $data['fields'][0]['field_element_id']?>">
	<fieldset>
		<legend class="cc-form-fieldset-heading">
			<span><?php echo $data['fields'][0]['head']; ?></span>
			<?php if($data['fields'][0]['require']): ?><span class="required">必須</span><?php endif; ?>
		</legend>

		<?php if($data['fields'][0]['attention']): ?>
		<div class="cc-form-fieldset-attention">
			<div class="mail-attention"><?php echo $data['fields'][0]['attention']; ?></div>
		</div>
		<?php endif; ?>

		<div class="cc-form-fieldset-body">
			<?php foreach($data['fields'] as $field): ?>
			<div class="mail-field" id="<?php echo $field['field_element_id']?>" data-type="<?php echo $field['type']; ?>">
				<?php if($field['before_attachment']): ?>
				<label class="mail-before-attachment" for="<?php echo $field['field_element_id']?>"><?php echo $field['before_attachment']?></label>
				<?php endif; ?>


				<?php // controlの出力、input-typeによって調え直しが必要; ?>
				<?php if($field['type'] == 'radio'): ?>
				<div class="mail-group-radio">
					<span>
						<?php echo str_replace('&nbsp;&nbsp;', '</span><span>', $field['control']); ?>
					</span>
				</div>
				<?php elseif($field['type'] == 'multi_check'): ?>
					<?php echo str_replace('&nbsp;', '', $field['control']); ?>
				<?php else: ?>
				<span class="mail-input">
					<?php echo $field['control']; ?>
				</span>
				<?php endif; ?>


				<?php if($field['after_attachment']): ?>
					<?php if($field['before_attachment']): ?>
					<span class="mail-after-attachment"><?php echo $field['after_attachment']?></span>
					<?php else: ?>
					<label class="mail-after-attachment" for="<?php echo $field['field_element_id']?>"><?php echo $field['after_attachment']?></label>
					<?php endif; ?>
				<?php endif; ?>

				<?php if($field['description']): ?>
				<div class="mail-description"><?php echo $field['description']?></div>
				<?php endif; ?>

				<?php if($field['error']): ?>
					<div class="error-message" role="alert"><?php echo $data['error'];?></div>
				<?php endif; ?>
			</div>
			<?php endforeach; ?>
		</div>
	</fieldset>
</div>
