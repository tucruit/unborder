<?php
/**
 * メールフォーム
 */
$this->BcBaser->css('admin/jquery-ui/jquery-ui.min', array('inline' => true));
$this->BcBaser->js(array('admin/vendors/jquery-ui-1.11.4.min', 'admin/vendors/i18n/ui.datepicker-ja'), false);
?>
<section>
	<div class="c-page-sub__content-detail">
		<div class="c-content-main">
			<div class="cc-form-description">
				<?php $this->Mail->description() ?>
			</div>

			<?php $this->BcBaser->flash() ?>
			<?php $this->BcBaser->element('mail_form') ?>

		</div>
	</div>
</section>