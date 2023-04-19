<?php $breakpointMax = Configure::read('Banner.breakpointMax') ?>

<h2>ブレークポイント設定</h2>
<?php echo $this->BcForm->create('BannerBreakpoint') ?>
<div class="section">
	<table cellpadding="0" cellspacing="0" id="FormTable" class="form-table bca-form-table">
		<thead class="bca-table-listup__thead">
			<tr>
				<th class="bca-table-listup__thead-th" style="width: 20px !important">ID</th>
				<th class="bca-table-listup__thead-th" style="width: 30px !important">有効</th>
				<th class="bca-table-listup__thead-th" style="width: 70px !important">ブレークポイント名</th>
				<th class="bca-table-listup__thead-th" style="width: auto !important">メディア属性</th>
			</tr>
		</thead>
		<tbody>
			<?php for ($i = 0; $i < $breakpointMax; $i++): ?>
				<?php $id = $i + 1 ?>
				<tr>
					<td class="col-input bca-form-table__input" style="text-align: center;"><?php echo $id ?></td>
					<td class="col-input bca-form-table__input" style="text-align: center;">
						<label style="padding: 10px;">
							<?php echo $this->BcForm->input('BannerBreakpoint.' . $i . '.status', ['type' => 'checkbox', 'label' => '<span class="bca-visually-hidden">' . __d('baser', 'チェックする') . '</span>', 'class' => 'batch-targets bca-checkbox__input']) ?>
							<?php echo $this->BcForm->input('BannerBreakpoint.' . $i . '.id', ['type' => 'hidden', 'value' => $id]) ?>
						</label>
					</td>
					<td class="col-input bca-form-table__input">
						<?php echo $this->BcForm->input('BannerBreakpoint.' . $i . '.name', ['type' => 'text', 'width' => '100']) ?>
						<?php echo $this->BcForm->error('BannerBreakpoint.' . $i . '.name') ?>
					</td>
					<td class="col-input bca-form-table__input">
						<?php echo $this->BcForm->input('BannerBreakpoint.' . $i . '.media_script', ['type' => 'text', 'class' => 'full-width bca-textbox__input', 'size' => '40']) ?>
						<?php echo $this->BcForm->error('BannerBreakpoint.' . $i . '.media_script') ?>
					</td>
				</tr>
			<?php endfor ?>
		</tbody>
	</table>
	<div class="submit bca-actions">
		<?php echo $this->BcForm->submit(__d('baser', '保存'), ['div' => false, 'class' => 'button bca-btn', 'data-bca-btn-type' => 'save', 'data-bca-btn-size' => 'lg', 'data-bca-btn-width' => 'lg', 'id' => 'BtnSave']) ?>
	</div>
</div>
<?php echo $this->BcForm->end() ?>
