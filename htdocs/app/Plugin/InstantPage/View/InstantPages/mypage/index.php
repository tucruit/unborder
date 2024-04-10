<?php
/**
 * [ADMIN] インスタントページ設定一覧
 */
$pageRoutes = configure::read('pageRoutes');
$userUrl = isset($user['name']) ? h($user['name']) : '';
// 無料プランの場合は1件以上登録できない。優良プランの場合は5件まで
// TODO:初回のLP登録後にこの画面に戻ってくると無料プランでもボタンが表示される場合がある？確認対応する。
$limit = $user['InstantPageUser']['plan_id'] == 1 ? 1 : 5;
$no = $limit;

// 申請送信済みのインスタントページをメール・メッセージから取得（userUrlを含む）
if (ClassRegistry::isKeySet('InstantPage.DomeinMessage')) {
	$DomeinMessageModel = ClassRegistry::getObject('InstantPage.DomeinMessage');
} else {
	$DomeinMessageModel = ClassRegistry::init('InstantPage.DomeinMessage');
}
$urls = $DomeinMessageModel->find('list', ['fields' => 'urlname', 'conditions' => ['DomeinMessage.urlname LIKE' => '%'.$userUrl.'%']]);
?>
<div role="main" class="myPage">
	<h1 class="mod-hl-pageTitle">マイページ</h1>
	<div class="l-container l-contentsContainer myPageInner">
		<?php if (empty($datas) || count($datas) < intval($limit)) :?>
			<div class="myPage-btnGroup">
				<button class="mod-btn-square-01 myPage-btnGroup-lpNew" id="subMenuGroupPageConfig-themeSelect" style="border: none;outline: none;">
					<span class="btnInner">LP新規作成</span>
				</button>
				<?php /*
				<a href="#" class="mod-btn-square-02 myPage-btnGroup-fileUpload" disabled=”disabled”>
					<span class="btnInner">ファイルアップロード（準備中）</span>
				</a>
				*/?>
			</div>
		<?php endif;?>
		<div class="js-scrollable myPage-siteTableWrap">
			<table class="myPage-siteTable">
				<thead>
					<tr>
						<th>タイトル</th>
						<th>状態</th>
						<th>利用テーマ</th>
						<th>最終更新日</th>
						<th>独自ドメイン</th><?php /**/?>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($datas)):?>
						<?php foreach ($datas as $data) :?>
							<?php //無料プランの場合は1件以上表示できない。優良プランの場合は5件まで
							if ($no <= 0) continue;
							$no --;
							// インスタントページURL
							$pageUrl = $pageRoutes. $userUrl. '/' . $data['InstantPage']['name'];
							?>
							<tr>
								<td><?php $this->BcBaser->link($data['InstantPage']['title'], ['action' => 'edit', $data['InstantPage']['id']]) ?></td>
								<td><?php
								if ($this->InstantPage->allowPublish($data) && $userUrl) { //公開状態であれば 公開ページヘのリンク
									$this->BcBaser->link('公開中', $pageUrl, ['title' => __d('baser', '確認'), 'target' => '_blank']);
								} else { // 非公開であればボタンを押せなくする
									echo '非公開';
								}
								?></td>
								<td><?php echo h($data['InstantPageTemplate']['name']); ?></td>
								<td><?php echo date('Y年m月d日', strtotime($data['InstantPage']['modified']))?></td>
								<td>
									<?php if (in_array($pageUrl, $urls) == false):?>
										<?php // 申請フォームの初期値作成
										$linkUrlName = 'index/name:'. base64UrlsafeEncode($user['real_name_1']. ' '. $user['real_name_1']);
										$linkUrlName .= '/email:'. base64UrlsafeEncode($user['email']);
										$linkUrlName .= '/urlname:'. base64UrlsafeEncode($pageUrl);
										?>
										<a href="<?php echo '/domain/'. $linkUrlName?>" class="myPage-siteTable-applicationStatus">
											<span class="btnInner"><?php echo '申請' ?></span>
										</a>
									<?php else:?>
										<a href="#" class="myPage-siteTable-applicationStatus isApplying">
											<span class="btnInner">申請済</span>
										</a>
								<?php endif;/**/?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>

		<?php
		$user = $this->Session->read('Auth');
		$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
		?>
		<?php if($instantPageUser['plan_id'] == 1): ?>
			<p class="textCenter marginTop40">
				あなたも有料会員になりませんか？ 有料会員になれば作成できるLPの数が増えます！
			</p>
			<a class="mod-btn-01 marginTop30" href="/cmsadmin/instant_page/instant_page_payments/payment/2">
				有料会員になる
			</a>
		<?php elseif($instantPageUser['plan_id'] == 2): ?>
			<a class="mod-btn-01 marginTop30" href="/cmsadmin/instant_page/instant_page_payments/payment/3">
				プランアップする
			</a>
		<?php endif; ?>
	</div>
</div>

<script>
	$(function(){

		/**
		 * EDIT THEME SELECT MENU OPEN
		 **/
		$('#subMenuGroupPageConfig-themeSelect').on('click', function () {
			$('#edit-themeListWrap').fadeIn();
			$(this).addClass('isModalOpen');
		});

		/**
		 * EDIT THEME SELECT MENU CLOSE
		 **/
		$('.edit-themeList-footer-closeBtn').on('click', function () {
			$('#edit-themeListWrap').fadeOut();
			$('#subMenuGroupPageConfig-themeSelect').removeClass('isModalOpen');
		});
	});
</script>


<div class="edit">
	<div class="edit-themeListWrap" id="edit-themeListWrap" style="width: 100%;right: auto;">
		<div class="edit-themeList">
			<div class="edit-themeList-header">
				<div class="edit-themeList-header-hl">
					テンプレート
				</div>
				<p class="edit-themeList-header-txt">
					任意のテンプレートを選択すれば簡単に新規LPを作成できます。
				</p>
			</div>
			<div class="edit-themeList-body">
			<?php if(!empty($templateCategories)): ?>
				<?php foreach ($templateCategories as $templateCategory): ?>
						<div>
							<div class="myPage-btnGroup">
								<a href="/cmsadmin/instant_page/instant_pages/add" class="mod-btn-square-01 myPage-btnGroup-lpNew" style="margin: 15px auto;max-width: 90%">
									<span class="btnInner">テンプレートを使わずに始める</span>
								</a>
							</div>
						</div>
						<div class="edit-themeList-body-themeContainer">
									<!-- BOX -->
									<div class="themeBox">
										<span class="themeBox-title"><?php echo $templateCategory['InstantPageTemplateCategory']['name'] ?></span>
										<span class="themeBox-description">
											<?php nl2br($templateCategory['InstantPageTemplateCategory']['description']) ?>
										</span>
										<div class="themeBox-img">
											<?php if(!empty($templateCategory['InstantPageTemplateCategory']['image_1'])): ?>
												<img src="/img/instant_page_template_category/<?php echo $templateCategory['InstantPageTemplateCategory']['image_1'] ?>" alt="<?php echo $templateCategory['InstantPageTemplateCategory']['name'] ?> サムネイル" class="imgFit">
											<?php else: ?>
												<?php
												$this->BcBaser->img('admin/no-screenshot.png', ['alt' =>'NOイメージ', 'class' => 'imgFit']);
												?>
											<?php endif ?>
										</div>
										<a class="themeBox-btn themeBox-btn__apply" data-template="" href="/cmsadmin/instant_page/instant_pages/add/<?php echo $templateCategory['InstantPageTemplateCategory']['id'] ?>">
											<span class="btnInner">使用する</span>
										</a>
										<!-- <a href="#" target="_blank" rel="noopener noreferrer" class="themeBox-btn themeBox-btn__preview">
											<span class="btnInner">サンプルプレビュー</span>
										</a> -->
									</div>
									<!-- /BOX -->
						</div>

				<?php endforeach; ?>
			<?php endif ?>
			</div>
			<div class="edit-themeList-footer">
				<div class="edit-themeList-footer-closeBtn">
					<span class="btnInner">閉じる</span>
				</div>
			</div>
		</div>
	</div>
</div>
