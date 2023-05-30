<?php
/**
 * [InstantInstantPage] InstantInstantPage 追加／編集
 */
$this->BcBaser->css('admin/ckeditor/editor', ['inline' => true]);
$this->BcBaser->js('InstantPage.admin/edit', false);
$users = isset($users) ? $users : $this->InstantPageUser->getUserList();
$InstantpageTemplateList = isset($InstantpageTemplateList) ? $InstantpageTemplateList : ['default', 'pop'];
$editorOptions = [];
$templates =[];
if (isset($user['InstantPageUser'])) {
	$this->request->data['InstantPage']['instant_page_user_id'] = $user['InstantPageUser']['id'];
}
?>
<div hidden="hidden">
	<div id="Action"><?php echo $this->request->action ?></div>
</div>

<?php
if ($this->action == 'admin_add') {
	echo $this->BcForm->create('InstantPage', ['type' => 'file', 'url' => ['action' => 'add'], 'id' => 'InstantPageForm']);
}  elseif ($this->action == 'admin_edit') {
	echo $this->BcForm->create('InstantPage', ['type' => 'file', 'url' => ['action' => 'edit', $this->BcForm->value('InstantPage.id'), 'id' => false], 'id' => 'InstantPageForm']);
}
?>
<?php echo $this->BcForm->input('InstantPage.mode', ['type' => 'hidden']) ?>
<?php echo $this->BcForm->input('InstantPage.id', ['type' => 'hidden']) ?>

<?php echo $this->BcFormTable->dispatchBefore() ?>

<div role="main" class="edit">
	<h1 class="hdnTxt">[ページタイトル]</h1>
	<div class="editInner">
		<!-- EDIT MAIN -->
		<div class="edit-main">
			<section>
				<h2 class="mod-hl-02">編集対象ページの公開ステータスにつきまして</h2>
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
				</p>

				<div hidden="hidden">
					<div id="Action"><?php echo $this->request->action ?></div>
				</div>
				<?php echo $this->BcFormTable->dispatchBefore() ?>
				<div class="bca-section bca-section-editor-area">
					<?php echo $this->BcForm->editor('InstantPage.contents', array_merge([
						'editor' => 'ckeditor',//$siteConfig['editor'],
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
										echo $this->BcForm->label('InstantPage.name', 'Url名（name）', ['class' => 'inputSet-header-name']);
										?>
										<!-- HELP -->
										<i class="subMenuBox-header-helpIcon">&thinsp;</i>
										<div class="subMenuBox-header-help">
											<ul class="mod-li-disc subMenuBox-header-help-list">
												<li>半角英数のみで入力してください</li>
												<li>同じ名前は仕様できません</li>
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
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
											</ul>
										</div>
										<!-- /HELP -->
									</div>
									<?php echo $this->BcForm->input('InstantPage.title', ['type' => 'textarea', 'div' => '', 'maxlength' => 5, 'class' => 'mod-form-input-textArea inputSet-input inputSet-input__textArea']) ?>　
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
										<label for="pageKeyWord" class="inputSet-header-title">サイト基本キーワード</label>
										<!-- HELP -->
										<i class="subMenuBox-header-helpIcon">&thinsp;</i>
										<div class="subMenuBox-header-help">
											<ul class="mod-li-disc subMenuBox-header-help-list">
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
											</ul>
										</div>
										<!-- /HELP -->
									</div>
									<textarea name="pageKeyWord" id="pageKeyWord" class="mod-form-input-textArea inputSet-input inputSet-input__textArea" maxlength="5"></textarea>
									<span class="inputSet-inputLength"><span class="nowLength">0</span><span class="maxLength">0</span></span>
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
										<label for="pageDescription" class="inputSet-header-title">サイト基本説明文</label>
										<!-- HELP -->
										<i class="subMenuBox-header-helpIcon">&thinsp;</i>
										<div class="subMenuBox-header-help">
											<ul class="mod-li-disc subMenuBox-header-help-list">
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
												<li>ヘルプ内容を記述ヘルプ内容を記述ヘルプ内容を記述</li>
											</ul>
										</div>
										<!-- /HELP -->
									</div>
									<textarea name="pageDescription" id="pageDescription" class="mod-form-input-textArea inputSet-input inputSet-input__textArea" maxlength="5"></textarea>
									<span class="inputSet-inputLength"><span class="nowLength">0</span><span class="maxLength">0</span></span>
								</div>
							</div>
						</div>
						<!-- /MENU BOX -->
						<!-- MENU BOX -->
						<div class="subMenuBox" id="subMenuGroupPageConfig-themeSelect">
							<span class="subMenuBox-title isMenuBtn">テーマの選択</span>
						</div>
						<!-- /MENU BOX -->
					</div>
					<!-- /MENU BOX GROUP (PAGE CONFIG) -->
					<!-- SAVE -->
					<div class="edit-sub-menu-save">
						<div class="mod-btn-square-03 edit-sub-menu-save-btn">
							<span class="btnInner">保存する</span>
							<input type="submit" name="BtnSave" id="BtnSave" value="保存">
						</div>
					</div>
					<!-- /SAVE -->
					<!-- OPERATION BTN -->
					<div class="edit-sub-menu-operationBtnGroup">
						<div class="mod-btn-square-03 edit-sub-menu-operationBtn isUndo">
							<span class="btnInner">操作を<br>1つ戻す</span>
							<input type="submit" name="BtnUndo" id="BtnUndo" value="操作を1つ戻す">
						</div>
						<div class="mod-btn-square-03 edit-sub-menu-operationBtn isRedo">
							<span class="btnInner">操作を<br>1つ進める</span>
							<input type="submit" name="BtnRedo" id="BtnRedo" value="操作を1つ進める">
						</div>
					</div>
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
							echo $this->BcForm->hidden('InstantPage.instant_page_users_id');
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
		<?php //$this->BcBaser->element('admin/theme_list') ?>
		<!-- /THEME LIST -->
	</div>
</div>
<?php echo $this->BcForm->end(); ?>

<script src="https://unpkg.com/@popperjs/core@2"></script>
<script src="https://unpkg.com/tippy.js@6"></script>
<script src="/my_page/js/ps_edit.js" type="module"></script>
<script src="/my_page/js/lib/scroll-hint/js/scroll-hint.min.js"></script>
<script src="/my_page/js/common_navigation.js"></script>
<script src="/my_page/js/common.js"></script>

