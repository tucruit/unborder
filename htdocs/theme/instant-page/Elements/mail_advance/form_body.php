<?php
$this->Mail->token();
$this->BcBaser->js(array('admin/vendors/ajaxzip3', 'InstantPage.instant_page_users'), false);
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js" charset="UTF-8"></script>
<script src="https://code.jquery.com/ui/1.11.4/jquery-ui.js"></script>

<script type="text/javascript">
window.addEventListener('DOMContentLoaded', (event) => {
	$(function() {
		$(".form-submit").click(function() {
			var mode = $(this).attr('id').replace('BtnMessage', '');
			console.log(mode);
			$("#MailMessageMode").val(mode);
			$(this).prop('disabled', true); //ボタンを無効化する
			$('form.cc-form').submit(); //フォームを送信する
			return false;
		});
	});
});
</script>

	<?php // フォーム開始タグ
	 if (!$freezed) {
		$options = [
			'url' 				=> $this->BcBaser->getContentsUrl(null, false, null, false) . 'confirm',
			'type' 				=> 'file',
			'novalidate' 		=> 'novalidate',
			'accept-charset' 	=> 'utf-8',
			'class' 			=> 'cc-form signup-form'
		];
	 } else {
	 	$options = [
	 		'url' => $this->BcBaser->getContentsUrl(null, false, null, false) . 'submit',
	 		'type' => 'file',
	 		'novalidate' => 'novalidate',
	 		'accept-charset' => 'utf-8',
	 		'class' => 'cc-form signup-form'
	 	];
	 }

	echo $this->Mailform->create('MailMessage', $options);

	$this->Mailform->unlockField('MailMessage.mode');
	echo $this->Mailform->hidden('MailMessage.mode');
	$this->Mailform->unlockField('MailMessage.password_1');
	$this->Mailform->unlockField('MailMessage.password_2');
	$this->Mailform->unlockField('MailMessage.token');
	$this->Mailform->unlockField('MailMessage.token_limit');
	$this->Mailform->unlockField('MailMessage.token_access');
	$this->Mailform->unlockField('MailMessage.referer');
	?>
	<table class="mod-table-form signup-form-table">
		<tbody>
			<?php
			$options['blockStart'] = 1;
			// $options['blockEnd'] = 6;
			$options = [
					'templates' => [
					'default' => 'mail_advance/field_default', 		// default : 標準テンプレート
					'single' => 'mail_advance/field_single', 	// single : 全てのグループではないフィールドに適応する
					'group' => 'mail_advance/field_group',		// group : 全てのグループフィールドに適応する
				]
			];
			$this->ThemeMail->mailForm($mailFields, $options);
			?>
		</tbody>
	</table>
	<div class="mod-btnContainer signup-form-submit">
		<?php if (!$freezed) : // 入力 ?>
			<div class="mod-btn-01 signup-form-submit-send">
				<span class="btnInner">登録する</span>
				<?php echo $this->Mailform->submit('入力内容を確認する', ['div' => false, 'id' => 'BtnMessageConfirm', 'class' => 'bgt-btn form-submit button']); ?>
			</div>
		<?php  else: // 確認画面?>
			<div class="mod-btn-01 signup-form-submit-send">
				<span class="btnInner">内容を修正する</span>
			<?php
			echo $this->Mailform->submit('内容を修正する', ['type' => 'button', 'div' => false, 'id' => 'BtnMessageBack', 'class' => 'form-submit hback button']);
			?>
		</div>
			<div class="mod-btn-01 signup-form-submit-send">
			<span class="btnInner">送信する</span>
			<?php
			echo $this->Mailform->submit('送信', ['div' => false, 'id' => 'BtnMessageSubmit', 'class' => 'form-submit button']);
			?><br>
		</div>
		<?php endif;  //確認画面:end?>
	</div><!-- /btn -->
<?php  echo $this->Mailform->end(); ?>
