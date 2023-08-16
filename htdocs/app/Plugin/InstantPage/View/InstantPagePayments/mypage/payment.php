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
						<?php echo $paymentPrice ?>円
					</td>
					<td>
						<?php echo $this->InstantPage->getPlanName($user['InstantPageUser']['plan_id']) ?>
					</td>
				</tr>
				</tbody>
			</table>
			</div>
			<button type="submit" class="mod-btn-01" style="margin-top: 40px;">決済画面へ進む</button>
			<?php echo $this->BcForm->hidden('Payment', ['value' => 1]) ?>
			<?php echo $this->BcForm->end() ?>
		</section>
	</div>
</div>


