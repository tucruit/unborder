<?php
/**
 * [View] Gtm コンテナID入力
 * [index] gtm/gtm/index
 */
?>
<?php echo $this->BcForm->create('SiteConfig') ?>

<!-- form -->
<section class="bca-section" data-bca-section-type="form-group">
		<h2>Google Tag Manager コンテナID設定</h2>
		<table cellpadding="0" cellspacing="0" class="form-table section bca-form-table">
				<tr>
					<th class="col-head bca-form-table__label"><?php echo $this->BcForm->label('Gtm.key', 'コンテナID') ?></th>
					<td class="col-input bca-form-table__input">
						<?php echo $this->BcForm->input('Gtm.key', [
				          'type' => 'text',
				          'size' => 80,
				          'maxlength' => 255,
				          'autofocus' => true,
				          'data-input-text-size' => 'full-counter',
				          'placeholder' => 'GTM-',
				          'counter' => true
						]); ?>
						<br><span>GTM-はあってもなくても大丈夫です。</span>
						<i class="bca-icon--question-circle btn help bca-help"></i>

				<div id="helptextBlank" class="helptext">
					<ul>
						<li>Google Tag Manager コンテナID設定をペーストします。</li>
						<li><a href="https://tagmanager.google.com/#/home" target="_blank">タグ マネージャー</a>にて取得してください。</li>
						<li><?php $this->BcBaser->img('Gtm.gtm.jpg', array('width' => '280px')); ?></li>
					</ul>
				</div>

						<?php echo $this->BcForm->error('Page.title') ?>
					</td>
				</tr>
		</table>
</section>
<section class="bca-actions">
	<div class="bca-actions__main">
		<?php echo $this->BcForm->submit(__d('baser', '保存'), [
			'div' => false,
			'type' => 'submit',
			'class' => 'button bca-btn',
			'data-bca-btn-type' => 'save',
			'data-bca-btn-size' => 'lg',
			'data-bca-btn-width' => 'lg',
			'id' => 'BtnSave'
		]) ?>
	</div>
</section>
<?php echo $this->BcForm->end(); ?>
