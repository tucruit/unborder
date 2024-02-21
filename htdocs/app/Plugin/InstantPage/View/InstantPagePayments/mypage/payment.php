<?php
$user = $this->Session->read('Auth');
$instantPageUser = !empty($user['Admin']) ? $this->Theme->getInstantPageUser($user['Admin']['id']) : [];
?>
<div role="main" class="registrationInfo">
	<h1 class="mod-hl-pageTitle">有料会員登録</h1>
	<div class="l-container l-contentsContainer registrationInfoInner">
		<section class="registrationInfo-form">
			<h2 class="mod-hl-01 registrationInfo-form-hl">決済確認画面</h2>

			<?php echo $this->BcForm->create() ?>
			<div class="js-scrollable myPage-siteTableWrap">
				<table class="myPage-siteTable">
				<thead>
				<tr>
					<th style="font-weight: bold">決済プラン</th>
					<th style="font-weight: bold">金額</th>
					<th>現在のプラン</th>
				</tr>
				</thead>
				<tbody>
				<tr>
					<td>
						<?php echo $this->InstantPage->getPlanName($paymentPlan) ?>
					</td>
					<td style="color:#F00;">
						月額 <?php echo $paymentPrice ?>円（税込）
					</td>
					<td>
						<?php echo $this->InstantPage->getPlanName($instantPageUser['plan_id']) ?>
					</td>
				</tr>
				</tbody>
			</table>
			</div>
			<?php if($instantPageUser['plan_id'] == 1): ?>
			<button type="submit" class="mod-btn-01" style="margin-top: 40px;">決済画面へ進む</button>
			<?php else: ?>
				<p class="marginTop40 textCenter">
					ありがとうございます。お客様は現在有料プランです。
				</p>
				<?php if($instantPageUser['plan_id'] == 3 && $paymentPlan == 2): ?>
					<button type="submit" class="mod-btn-01" style="margin-top: 40px;">決済画面へ進む（プランダウン）</button>
				<?php endif; ?>
				<?php if($instantPageUser['plan_id'] == 2): ?>
					<button type="submit" class="mod-btn-01" style="margin-top: 40px;">決済画面へ進む（プランアップ）</button>
				<?php endif; ?>
			<?php endif ?>
			<?php echo $this->BcForm->hidden('Payment', ['value' => 1]) ?>
			<?php echo $this->BcForm->end() ?>
		</section>
	</div>
</div>


