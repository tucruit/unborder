<?php
/**
 * [ADMIN] インスタントページ設定一覧
 */
$pageRoutes = configure::read('pageRoutes');
$userUrl = isset($user['name']) ? h($user['name']) : '';
// 無料プランの場合は1件以上登録できない。優良プランの場合は5件まで
$limit = $user['InstantPageUser']['plan_id'] == 1 ? 1 : 5;
?>
<div role="main" class="myPage">
	<h1 class="mod-hl-pageTitle">マイページ</h1>
	<div class="l-container l-contentsContainer myPageInner">
		<?php if (empty($datas) || count($datas) < intval($limit)) :?>
			<div class="myPage-btnGroup">
				<a href="/cmsadmin/instant_page/instant_pages/add" class="mod-btn-square-01 myPage-btnGroup-lpNew">
					<span class="btnInner">LP新規作成</span>
				</a>
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
						<?php /*<th>独自ドメイン</th>*/?>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($datas)):?>
						<?php foreach ($datas as $data) :?>
							<?php //無料プランの場合は1件以上表示できない。優良プランの場合は5件まで
							if ($limit <= 0) continue;
							$limit --;
							?>
							<tr>
								<td><?php $this->BcBaser->link($data['InstantPage']['title'], ['action' => 'edit', $data['InstantPage']['id']]) ?></td>
								<td><?php
								if ($this->InstantPage->allowPublish($data) && $userUrl) { //公開状態であれば 公開ページヘのリンク
									$this->BcBaser->link('公開中', $pageRoutes. $userUrl. '/' . $data['InstantPage']['name'], ['title' => __d('baser', '確認'), 'target' => '_blank']);
								} else { // 非公開であればボタンを押せなくする
									echo '非公開';
								}
								?></td>
								<td><?php echo h($data['InstantPageTemplate']['name']); ?></td>
								<td><?php echo date('Y年m月d日', strtotime($data['InstantPage']['modified']))?></td>
								<?php /*<td>
									<a href="#" class="myPage-siteTable-applicationStatus">
										<span class="btnInner"><?php echo '準備中'//'申請' ?></span>
									</a>
									<a href="#" class="myPage-siteTable-applicationStatus isApplying">
										<span class="btnInner">申請中</span>
									</a>
								</td>*/?>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
