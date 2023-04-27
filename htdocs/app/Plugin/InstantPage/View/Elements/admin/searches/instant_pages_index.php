<?php
/**
 * [InstantPage] ユーザー管理
 *
 */
?>
<?php /*<?php */?>
<script>
$(function(){
		// まずは対象のセレクトボックスの内容一覧を取得する
		var dataList = {};
		selectObject = $('#InstantPageId').children();
		for(i = 0; i < selectObject.length; i++) {
			targetObject = selectObject.eq(i);
			dataList[targetObject.val()] = targetObject.text();
		}
		console.log(dataList);
		// テキストエリアが変更された時の動きを登録
		$('#InstantPageName').change(function(){
			console.log($(this).val());
			targetString = $(this).val();
			// 絞込開始
			if(targetString == '') {
				// 絞込文字列が空の時は全部表示する。
				$('#InstantPageId > option').remove();
				for ( var key in dataList ) {
					text = dataList[key];
					$('#InstantPageId').append($('<option>').html(text).val(key));
				}
			} else {
				// 絞込文字列が設定されているときは部分一致するもののみを表示する
				$('#InstantPageId > option').remove();
				for ( var key in dataList ) {
					text = dataList[key];
					if(text.indexOf(targetString) != -1) {
						$('#InstantPageId').append($('<option>').html(text).val(key));
					}
				}
			}
		});
});
</script>

<?php echo $this->BcForm->create('InstantPage', array('url' => array('action' => 'index'))) ?>
<p>
	<span>
		<?php
		echo $this->BcForm->label('InstantPage.name', '企業名');
		echo "&nbsp;";
		echo $this->BcForm->input('InstantPage.name', ['type' => 'text', 'size' => '30']);
		?>
	</span>
	<span>
		<?php
		//echo $this->BcForm->label('InstantPageUser.partner_id',  '企業名');
		echo $this->BcForm->input('InstantPage.id', ['type' => 'select', 'options' => $partners, 'escape' => true, 'empty' => __d('baser', '指定なし')]);
		?>
	</span>
</p>

<p>
	<span>
		<?php echo $this->BcForm->label('InstantPage.prefecture_id', '都道府県') ?>
		<?php echo $this->BcForm->input('InstantPage.prefecture_id', ['type' => 'select', 'options' => $this->BcText->prefList(), 'escape' => true, 'empty' => __d('baser', '指定なし')]) ?>
	</span>　
	<?php echo $this->BcSearchBox->dispatchShowField() ?>
</p>
<div class="button bca-search__btns">
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', '検索'), "javascript:void(0)", ['id' => 'BtnSearchSubmit', 'class' => 'bca-btn', 'data-bca-btn-type' => 'search']) ?></div>
	<div class="bca-search__btns-item"><?php $this->BcBaser->link(__d('baser', 'クリア'), "javascript:void(0)", ['id' => 'BtnSearchClear', 'class' => 'bca-btn', 'data-bca-btn-type' => 'clear']) ?></div>
</div>
<?php echo $this->BcForm->end() ?>
