<?php
/**
 * [PUBLISH] メールフォーム
 */
$here = $this->request->here;
$mailForm = $this->BcContents->getContentByEntityId(3, 'MailContent');
$query = $this->request->query;
?>
<?php if (!empty($query) && strpos($query['status'], 'thanks') !== false) :?>

<section class="c-page-home__questionnaire" id="surveyIn">
	<div class="contact isSubmit">
		<div class="l-subContentsContainer sub-container contactInner">
			<h2 class="mod-hl-01 contact-hl">お問い合わせフォーム</h2>
			<div class="contact-form">
				<p class="contact-form-thanksMsg">
					お問い合わせ頂きありがとうございました。<br>
				確認次第、ご連絡させて頂きます。			</p>
			</div>
			<p class="contact-form-thanksMsg">
				<a href="<?php echo h($this->request->here) ?>#surveyIn">もう一度送信する</a>
			</p>
		</div>
	</div>
</section>
<?php elseif ($mailForm['Content']['status']) :?>
	<script>
		$(function () {
			$.ajax({
				cache: false,
				type: 'GET',
				datatype:'html',
				url: "<?php echo $mailForm['Content']['url'] ?>?url=<?php echo $here ?>",
				data: 'author=<?php echo $data['InstantPageUser']['id'] ?>',
				success: function(result) {
					$('#surveyIn').html(result);
					if ("efo" in window) {
						window.efo();
					}
				},
				error: function(XMLHttpRequest, textStatus, errorThrown) {
					console.log('Error : ' + errorThrown);
				}
			});
		});
	</script>
	<div id="survey" style="display: block; margin-top:-100px; padding-top:100px; height: 0;"> </div>
	<section class="c-page-home__questionnaire" id="surveyIn"></section>
<?php endif;?>
