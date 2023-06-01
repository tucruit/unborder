<?php
/**
 * [ADMIN] インスタントページ設定一覧
 */
?>
<div role="main" class="myPage">
	<h1 class="mod-hl-pageTitle">マイページ</h1>
	<div class="l-container l-contentsContainer myPageInner">
		<div class="myPage-btnGroup">
			<a href="/cmsadmin/instant_page/instant_pages/add" class="mod-btn-square-01 myPage-btnGroup-lpNew">
				<span class="btnInner">LP新規作成</span>
			</a>
			<a href="#" class="mod-btn-square-02 myPage-btnGroup-fileUpload" disabled=”disabled”>
				<span class="btnInner">ファイルアップロード（準備中）</span>
			</a>
		</div>
		<div class="js-scrollable myPage-siteTableWrap">
			<table class="myPage-siteTable">
				<thead>
					<tr>
						<th>タイトル</th>
						<th>状態</th>
						<th>利用テーマ</th>
						<th>最終更新日</th>
						<th>独自ドメイン</th>
					</tr>
				</thead>
				<tbody>
					<?php if (!empty($datas)):?>
						<?php foreach ($datas as $data) :?>
							<tr>
								<td><?php $this->BcBaser->link($data['InstantPage']['title'], ['action' => 'edit', $data['InstantPage']['id']]) ?></td>
								<td><?php echo $data['InstantPage']['status'] ? '公開中' : ''?></td>
								<td><?php echo h($data['InstantPage']['template']) ?></td>
								<td><?php echo date('Y年m月d日', strtotime($data['InstantPage']['modified']))?></td>
								<td>
									<a href="#" class="myPage-siteTable-applicationStatus">
										<span class="btnInner"><?php echo '準備中'//'申請' ?></span>
									</a>
									<?php /*
									<a href="#" class="myPage-siteTable-applicationStatus isApplying">
										<span class="btnInner">申請中</span>
									</a>
									*/?>
								</td>
							</tr>
						<?php endforeach; ?>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
	</div>
</div>
