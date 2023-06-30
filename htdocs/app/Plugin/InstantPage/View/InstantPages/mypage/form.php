<?php
/**
 * [InstantInstantPage] InstantInstantPage 追加／編集
 */
$userNames = isset($userNames) ? $userNames : [];
$userName = $userNames[$this->BcForm->value('InstantPage.instant_page_users_id')];
$url = '/lp/' . $userName. '/'.  $this->BcForm->value('InstantPage.name');
$fullUrl = $this->BcBaser->getContentsUrl($url, true);
$this->BcBaser->css('admin/ckeditor/editor', ['inline' => true]);
$this->BcBaser->js('InstantPage.admin/edit', false);
$this->BcBaser->js('InstantPage.admin/form', false, [
	'id' => 'AdminInstantPagesEditScript',
	'data-fullurl' => $fullUrl,
	'data-previewurl' => $this->BcBaser->getContentsUrl($url, false, false, true),
]);
$this->BcBaser->css('admin/ckeditor/editor', ['inline' => true]);
$this->BcBaser->js('InstantPage.admin/edit', false);
$users = isset($users) ? $users : $this->InstantPageUser->getUserList();
$InstantpageTemplateList = isset($InstantpageTemplateList) ? $InstantpageTemplateList : [1 => 'default', 2 =>'pop'];
$editorOptions = [];
$templates =[];
$instantPageUserIdValue = [];
if (!isset($user['InstantPageUser'])) {
	$user['InstantPageUser'] = $this->InstantPage->getInstantPageUser($user['id']);
	$this->request->data['InstantPage']['instant_page_user_id'] = $user['InstantPageUser']['id'];
	$instantPageUserIdValue = ['value' => $user['InstantPageUser']['id']];
}
?>
<div hidden="hidden">
	<div id="Action"><?php echo $this->request->action ?></div>
</div>

<?php if ($this->action == 'admin_add'): ?>
	<?php echo $this->BcForm->create('InstantPage', ['type' => 'file', 'url' => ['controller' => 'instant_pages', 'action' => 'add'], 'id' => 'PageForm']) ?>
<?php elseif ($this->action == 'admin_edit'): ?>
	<?php echo $this->BcForm->create('InstantPage', ['type' => 'file', 'url' => ['controller' => 'instant_pages', 'action' => 'edit', $this->BcForm->value('InstantPage.id'), 'id' => false], 'id' => 'PageForm']) ?>
<?php endif; ?>

<?php echo $this->BcForm->input('InstantPage.mode', ['type' => 'hidden']) ?>
<?php echo $this->BcForm->input('InstantPage.id', ['type' => 'hidden']) ?>
<?php echo $this->BcForm->hidden('InstantPage.instant_page_users_id', $instantPageUserIdValue)  ?>
<?php echo $this->BcForm->unlockField('InstantPage.status'); ?>
<?php echo $this->BcFormTable->dispatchBefore() ?>

<div role="main" class="edit">
	<h1 class="hdnTxt">[ページタイトル]</h1>
	<div class="editInner">
		<!-- EDIT MAIN -->
		<div class="edit-main">
			<section>
				<!-- <h2 class="mod-hl-02">編集対象ページの公開ステータスにつきまして</h2>
				<p class="marginTop30">
					#selfStatusValue(input type="hidden")で保持する仕様にしています。<br>
					当ページ表示時に、公開ステータスを上記要素のvalueへ入れていただきますようお願いします。<br>
					（JS側で公開非公開切り替えボタン（.edit-sub-menu-status-btn）の表示をコントロールしています）<br>
					また、JS側で公開非公開切替えボタン（.edit-sub-menu-status-btn）クリック時、ボタンの表示切替処理と、#selfStatusValueのvalueへ公開ステータスの値を入れかえを行っています。
				</p>
				<ul class="mod-li-disc">
					<li>公開：1</li>
					<li>非公開：0</li>
				</ul>
				<p>
				</p> -->

				<div hidden="hidden">
					<div id="Action"><?php echo $this->request->action ?></div>
				</div>
				<?php echo $this->BcFormTable->dispatchBefore() ?>
				<div class="bca-section bca-section-editor-area">
					<?php echo $this->BcForm->editor('InstantPage.contents', array_merge([
						'editor' => $siteConfig['editor'],//'ckeditor',
						'editorUseDraft' => true,
						'editorDraftField' => 'draft',
						'editorWidth' => 'auto',
						'editorHeight' => '480px',
						'editorEnterBr' => @$siteConfig['editor_enter_br']
					], $editorOptions)); ?>
					<?php echo $this->BcForm->error('InstantPage.contents') ?>
					<?php echo $this->BcForm->error('InstantPage.draft') ?>
				</div>

			</section>
		</div>
		<!-- /EDIT MAIN -->
		<!-- EDIT MENU -->
		<aside class="edit-sub">
			<div class="edit-subInner">
				<!-- EDIT MENU OPEN/CLOSE BTN -->
				<div class="edit-sub-menuOpenBtn">
					<span class="hdnTxt edit-sub-menuOpenBtn-txt">TEXT</span>
				</div>
				<!-- /EDIT MENU OPEN/CLOSE BTN -->
				<!-- EDIT SUB MENU -->
				<div class="edit-sub-menu">
					<!-- STATUS -->
					<div class="edit-sub-menu-status">
						<div class="mod-btn-square-02 edit-sub-menu-status-btn">
							<span class="btnInner"><span class="isMainTxt">公開する</span><small class="isSubTxt">（現在下書中）</small></span>
						</div>
						<?php echo $this->BcForm->label('InstantPage.status', __d('baser', '公開状態'), ['style' => 'display:none;']) ?>
						<?php echo $this->BcForm->input('InstantPage.status', [
							'type' => 'hidden', 'class' => 'edit-sub-menu-status-value']) //#InstantPageStatus ?>
						<?php echo $this->BcForm->error('InstantPage.status') ?>
					</div>
					<!-- /STATUS -->
					<?php  /*
					<!-- STATUS -->
					<div class="edit-sub-menu-status">
						<div class="mod-btn-square-02 edit-sub-menu-status-btn">
							<?php echo $this->BcForm->label('InstantPage.status', __d('baser', '公開状態')) ?>
							<span class="btnInner"><span class="isMainTxt">公開する</span><small class="isSubTxt">（現在下書中）</small></span>
						</div>
						<?php echo $this->BcForm->input('InstantPage.status', [
							'type' => 'radio', 'options' => [0 => __d('baser', '公開しない'), 1 => __d('baser', '公開する')], 'clase' => 'edit-sub-menu-status-value']) ?>
						<?php echo $this->BcForm->error('InstantPage.status') ?>
					</div>
					<!-- /STATUS -->
					*/?>
					<!-- MENU BOX GROUP (PAGE CONFIG) -->
					<div class="edit-sub-menu-menuGroup" id="subMenuGroupPageConfig">
						<span class="edit-sub-menu-menuGroup-title">基本設定</span>
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupPageConfig-title">
							<span class="subMenuBox-title">このページのUrl用name</span>
							<div class="subMenuBox-inputBlock">
								<div class="subMenuBox-inputBlock-inputSet">
									<div class="inputSet-header withHelp">
										<?php
										echo $this->BcForm->label('InstantPage.name', 'Url名（name）', ['class' => 'inputSet-header-name nameCheck']);
										?>
										<!-- HELP -->
										<i class="subMenuBox-header-helpIcon">&thinsp;</i>
										<div class="subMenuBox-header-help">
											<ul class="mod-li-disc subMenuBox-header-help-list">
												<li>半角英数のみで入力してください</li>
												<li>同じ名前は使用できません</li>
											</ul>
										</div>
										<!-- /HELP -->
									</div>
									<?php echo $this->BcForm->input('InstantPage.name', ['type' => 'textarea', 'div' => '', 'maxlength' => 5, 'class' => 'mod-form-input-textArea inputSet-input inputSet-input__textArea']) ?>　
									<span class="inputSet-inputLength"><span class="nowLength">0</span><span class="maxLength">0</span></span>
									<?php echo $this->BcForm->error('InstantPage.name') ?>
								</div>
							</div>
						</div>
						<!-- /MENU BOX -->

						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupPageConfig-title">
							<span class="subMenuBox-title">このページのタイトル</span>
							<div class="subMenuBox-inputBlock">
								<div class="subMenuBox-inputBlock-inputSet">
									<div class="inputSet-header withHelp">
										<?php
										echo $this->BcForm->label('InstantPage.title', 'ページ名（title）', ['class' => 'inputSet-header-title']);
										?>
										<!-- HELP -->
										<i class="subMenuBox-header-helpIcon">&thinsp;</i>
										<div class="subMenuBox-header-help">
											<ul class="mod-li-disc subMenuBox-header-help-list">
												<li>LPのタイトルを入力してください。</li>
											</ul>
										</div>
										<!-- /HELP -->
									</div>
									<?php
									echo $this->BcForm->input('InstantPage.title', [
										'type' => 'textarea',
										'div' => '',
										'maxlength' => 255,
										'class' => 'mod-form-input-textArea inputSet-input inputSet-input__textArea'
									]);
									?>　
									<span class="inputSet-inputLength"><span class="nowLength">0</span><span class="maxLength">0</span></span>
									<?php echo $this->BcForm->error('InstantPage.title') ?>
								</div>
							</div>
						</div>
						<!-- /MENU BOX -->
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupPageConfig-keyWord">
							<span class="subMenuBox-title">キーワード</span>
							<div class="subMenuBox-inputBlock">
								<div class="subMenuBox-inputBlock-inputSet">
									<div class="inputSet-header withHelp">
										<?php echo $this->BcForm->label('InstantPage.page_key_word', 'サイト基本キーワード', ['class' => 'inputSet-header-title']) ?>
										<!-- HELP -->
										<i class="subMenuBox-header-helpIcon">&thinsp;</i>
										<div class="subMenuBox-header-help">
											<ul class="mod-li-disc subMenuBox-header-help-list">
												<li>このLPを表現するキーワードを半角カンマ（,）で入力してください。</li>
											</ul>
										</div>
										<!-- /HELP -->
									</div>
									<?php echo $this->BcForm->input('InstantPage.page_key_word', [
										'type' => 'textarea',
										'div' => '',
										'maxlength' => 255,
										'class' => 'mod-form-input-textArea inputSet-input inputSet-input__textArea'
									]); ?>
									<span class="inputSet-inputLength"><span class="nowLength">0</span><span class="maxLength">0</span></span>
									<?php echo $this->BcForm->error('InstantPage.page_key_word') ?>
								</div>
							</div>
						</div>
						<!-- /MENU BOX -->
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupPageConfig-description">
							<span class="subMenuBox-title">説明文</span>
							<div class="subMenuBox-inputBlock">
								<div class="subMenuBox-inputBlock-inputSet">
									<div class="inputSet-header withHelp">
										<?php echo $this->BcForm->label('InstantPage.page_description', 'サイト基本説明文', ['class' => 'inputSet-header-title']) ?>
										<!-- HELP -->
										<i class="subMenuBox-header-helpIcon">&thinsp;</i>
										<div class="subMenuBox-header-help">
											<ul class="mod-li-disc subMenuBox-header-help-list">
												<li>このLPを説明する文章を入力してください。</li>
											</ul>
										</div>
										<!-- /HELP -->
									</div>
									<?php echo $this->BcForm->input('InstantPage.page_description', [
										'type' => 'textarea',
										'div' => '',
										'maxlength' => 255,
										'class' => 'mod-form-input-textArea inputSet-input inputSet-input__textArea'
									]); ?>
									<span class="inputSet-inputLength"><span class="nowLength">0</span><span class="maxLength">0</span></span>
									<?php echo $this->BcForm->error('InstantPage.page_description') ?>
								</div>
							</div>
						</div>
						<!-- /MENU BOX -->
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupPageConfig-themeSelect">
							<span class="subMenuBox-title isMenuBtn"><?php echo $this->BcForm->label('InstantPage.instant_page_template_id', 'テーマの選択') ?></span>
							<?php echo $this->BcForm->input('InstantPage.instant_page_template_id', ['type' => 'hidden']) ?>
						</div>
						<!-- /MENU BOX -->
					</div>
					<!-- /MENU BOX GROUP (PAGE CONFIG) -->
					<!-- プレビュー -->
					<div class="edit-sub-menu-save">
						<div class="mod-btn-square-03 edit-sub-menu-preview-btn">
							<span class="btnInner">プレビュー</span>
							<?php echo $this->BcForm->button(__d('baser', 'プレビュー'),
								[
									'id' => 'BtnPreview',
									'div' => false,
									'class' => 'button bca-btn bca-actions__item',
									'data-bca-btn-type' => 'preview',
								]) ?>
						</div>
					</div>
					<!-- SAVE -->
					<div class="edit-sub-menu-save">
						<div class="mod-btn-square-03 edit-sub-menu-save-btn">
							<span class="btnInner">保存する</span>
							<?php echo $this->BcForm->button(__d('baser', '保存'),
						[
							'type' => 'submit',
							'id' => 'BtnSave',
							'div' => false,
							'class' => 'button bca-btn bca-actions__item',
							'data-bca-btn-type' => 'save',
							'data-bca-btn-size' => 'lg',
							'data-bca-btn-width' => 'lg',
						]) ?>
						</div>
					</div>
					<!-- /SAVE -->
					<!-- OPERATION BTN -->
					<!-- <div class="edit-sub-menu-operationBtnGroup">
						<div class="mod-btn-square-03 edit-sub-menu-operationBtn isUndo">
							<span class="btnInner">操作を<br>1つ戻す</span>
							<input type="submit" name="BtnUndo" id="BtnUndo" value="操作を1つ戻す">
						</div>
						<div class="mod-btn-square-03 edit-sub-menu-operationBtn isRedo">
							<span class="btnInner">操作を<br>1つ進める</span>
							<input type="submit" name="BtnRedo" id="BtnRedo" value="操作を1つ進める">
						</div>
					</div> -->
					<!-- OPERATION BTN -->
					<!-- MENU BOX GROUP (LINK) -->
					<div class="edit-sub-menu-menuGroup" id="subMenuGroupLink">
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupLink-manual">
							<a href="#" class="subMenuBox-title isLinkBtn">マニュアルを見る</a>
						</div>
						<!-- /MENU BOX -->
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupLink-myPage">
							<?php echo $this->BcHtml->link('マイページに戻る', ['controller' => 'instant_pages', 'action' => 'index'], [
								'class' => 'subMenuBox-title isLinkBtn'
							]) ?>
						</div>
						<!-- /MENU BOX -->
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupLink-myEdit">
							<?php
							$userName = h($this->BcText->arrayValue($this->BcForm->value('InstantPage.instant_page_users_id'), $users, ['class' => 'subMenuBox-title isLinkBtn']));
							echo $this->BcHtml->link($userName. ' の登録情報', [
								'controller' => 'instant_page_users',
								'action' => 'edit',
								$user['InstantPageUser']['id']
							], ['class' => 'subMenuBox-title isLinkBtn']);
							?>
						</div>
						<!-- /MENU BOX -->
					</div>
					<!-- /MENU BOX GROUP (LINK) -->
				</div>
				<!-- /EDIT SUB MENU -->
			</div>
		</aside>
		<!-- /EDIT MENU -->
		<!-- THEME LIST -->
		<?php
		$InstantpageTemplateList = isset($InstantpageTemplateList) ? $InstantpageTemplateList : [];
		?>
		<div class="edit-themeListWrap" id="edit-themeListWrap">
			<div class="edit-themeList">
				<div class="edit-themeList-header">
					<div class="edit-themeList-header-hl">テーマ一覧</div>
					<p class="edit-themeList-header-txt">
						任意のテーマの「適用する」ボタンをクリックするとテーマが適用されます。<br>保存すると表示が切り替わります。<!-- 「サンプルプレビュー」をクリックしていただくとサンプル画面がご確認いただけます。 -->
					</p>
				</div>
				<div class="edit-themeList-body">
					<div class="edit-themeList-body-themeContainer">
						<?php if (!empty($InstantpageTemplateList)) :?>
							<?php foreach ($InstantpageTemplateList as $key => $template) :?>
								<?php
								// configを読み込んで、タイトル・ディスクリプション・screenshotをセット
								$title = $template;
								$description = '';
								$thnmb = 'admin/no-screenshot.png';
								if (isset($themedatas[$template])) {
									if ($themedatas[$template]['title']) { //タイトル
										$title = $themedatas[$template]['title'];
									}
									if ($themedatas[$template]['description']) { //ディスクリプション
										$description = mb_strimwidth($themedatas[$template]['description'], 0, 160, '...', 'utf8');
									}
									if ($themedatas[$template]['screenshot']) { // screenshot
										$thnmb = $this->BcBaser->getUrl('/theme/'.$template. '/screenshot.png');
									}
								} else {
									// テーマ内にscreenshot.pngがあれば、それを表示
									$screenshotPath = $this->BcBaser->getUrl('/theme/'.$template. '/screenshot.png');
									$path = WWW_ROOT . 'theme';
									$thnmb = file_exists($path . DS . $template . DS . 'screenshot.png') ? $screenshotPath : 'admin/no-screenshot.png';
								}
								?>
								<!-- BOX -->
								<div class="themeBox">
									<span class="themeBox-title"><?php echo h($title) ?></span>
									<?php echo $description ? '<span class="themeBox-description">'. nl2br(h($description)). '</span>' :''?>
									<div class="themeBox-img">
										<?php
										$this->BcBaser->img($thnmb, ['alt' => h($template). '適用イメージ', 'class' => 'imgFit']);
										?>
									</div>
									<div class="themeBox-btn themeBox-btn__apply" data-template="<?php echo $key ?>">
										<span class="btnInner">適用する</span>
									</div>
									<!-- <a href="#" target="_blank" rel="noopener noreferrer" class="themeBox-btn themeBox-btn__preview">
										<span class="btnInner">サンプルプレビュー</span>
									</a> -->
								</div>
								<!-- /BOX -->
							<?php endforeach; ?>
						<?php else: ?>
							<p>現在選択できるテーマはございません。</p>
						<?php endif; ?>
					</div>
				</div>
				<div class="edit-themeList-footer">
					<div class="edit-themeList-footer-closeBtn">
						<span class="btnInner">閉じる</span>
					</div>
				</div>
			</div>
		</div>
		<!-- /THEME LIST -->
	</div>
</div>
<?php echo $this->BcForm->end(); ?>

<script src="https://unpkg.com/@popperjs/core@2.11.8/dist/umd/popper.min.js"></script>
<script src="https://unpkg.com/tippy.js@6.3.7/dist/tippy-bundle.umd.min.js"></script>
<script src="/my_page/js/ps_edit.js" type="module"></script>
<script src="/my_page/js/lib/scroll-hint/js/scroll-hint.min.js"></script>
<?php $this->BcBaser->js(array('InstantPage.instant_pages'), true); ?>
