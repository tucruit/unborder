/**
 * [ADMIN] OptionalLink
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
$(function () {
	changeOptionalView();
	changeBlogPostDetail();
	// status の切り替えイベント
	$(".optionallink-status").change(function(){
		changeOptionalView();
		changeBlogPostDetail();
	});
	// status の値に応じたフォーム表示切替え
	function changeOptionalView(){
		var optionalLinkStatusVal = $(".optionallink-status:checked").val();
		$urlArea      = $("#OptionalLinkName").parents('.section');
		$fileArea     = $("#OptionalLinkFile").parents('.section');
		$urlArea.slideUp();
		$fileArea.slideUp();
		
		// ステータス値により表示切替え
		switch (optionalLinkStatusVal) {
			case '0':
				break;

			case '1':
				$urlArea.slideDown();
				optionalLinkNolinkChengeHandler();
				break;

			case '2':
				$fileArea.slideDown();
				break;
		}
	}

	// URL・ファイルを選択した際、本文を非表示・プレビューを無効にする
	function changeBlogPostDetail() {
		var optionalLinkStatusVal = $(".optionallink-status:checked").val();
		$previewButton = $("#BtnPreview");
		$blogPostDetailSection = $("#BlogPostDetailTmp, #BlogPostDetailDraft, #BlogPostDetail").parent();
		$optionalLinkPreviewNotice = $("#OptionalLinkPreviewNotice");

		switch (optionalLinkStatusVal) {
			case '0':
				$blogPostDetailSection.show();
				$previewButton.prop("disabled", false);
				$optionalLinkPreviewNotice.remove();
				break;
			case '1':
			case '2':
			case '3':
				$blogPostDetailSection.hide();
				$previewButton.prop("disabled", true);
				if (!$optionalLinkPreviewNotice.length) {
					$('#BlogPostForm .submit').after('<div id="OptionalLinkPreviewNotice">※オプショナルリンクが利用されているためプレビューは利用できません。</div>');
				}
				break;
		}
	}

	// nolink の切り替えイベント
	$('#OptionalLinkNolink').click(function () {
		optionalLinkNolinkChengeHandler();
	});
	// nolink 値による切り替え動作
	function optionalLinkNolinkChengeHandler() {
		var judge = $('#OptionalLinkNolink').prop('checked');
		if (!judge) {
			$('#OptionalLinkName').attr('readonly', false);
			$('#OptionalLinkName').css('background-color', '');
			$('#OptionalLinkBlank').attr('disabled', false);
			$('label[for="OptionalLinkBlank"]').css('color', '');
		} else {
			$('#OptionalLinkName').attr('readonly', true);
			$('#OptionalLinkName').css('background-color', '#CCC');
			$('#OptionalLinkBlank').attr('disabled', true);
			$('label[for="OptionalLinkBlank"]').css('color', '#CCC');
		}
		// $('#OptionalLinkNolink').attr('disabled', false);
		// $('label[for="OptionalLinkNolink"]').css('color', '');
	}
	
	/**
	 * 公開期間クリアボタンを押下した際に、ダイアログを表示して期間指定をクリアする
	 */
	$("#BtnClearOptionalLinkPublish").click(function() {
		var dialogId = '#' + $(this).attr('id') + 'Dialog';
		var dialogTitle = $(dialogId).find('.dialog-property').find('h3').html();
		var dialogWidth = $(dialogId).find('.dialog-property').find('.width').html();
		// TODO ボタン用のテキストを動的に設定する
		//var dialogBtnCancel = $(dialogId).find('.dialog-property').find('.btn-cancel').html();
		//var dialogBtnOk = $(dialogId).find('.dialog-property').find('.btn-ok').html();
		$(dialogId).dialog({
			modal: true,
			title: dialogTitle,
			width: dialogWidth,
			buttons: {
				'キャンセル': function() {
					$(this).dialog("close");
				},
				'OK': function() {
					$('#OptionalLinkPublishBeginDate').val('');		// フォーム表示値
					$('#OptionalLinkPublishBeginTime').val('');		// フォーム表示値
					$('#OptionalLinkPublishBegin').val('');			// フォームhiddenの実体値
					$('#OptionalLinkPublishEndDate').val('');		// フォーム表示値
					$('#OptionalLinkPublishEndTime').val('');		// フォーム表示値
					$('#OptionalLinkPublishEnd').val('');			// フォームhiddenの実体値
					$(this).dialog("close");
				}
			}
		});
		return false;
	});
	
	/**
	 * ファイルに画像を登録した際、表示画像が150pxより大きい場合は小さくして表示する
	 */
	resizeUploadImage();
	function resizeUploadImage() {
		$imageFile = $('#OptionalLinkTable .upload-file img');
		$imageFile.addClass('optional-thumbnail');
		
		imgHeight = '';
		imgHeight = $imageFile.height();
		if (imgHeight) {
			if (imgHeight > 200) {
				$imageFile.attr({'height': 200});
			}
		}
	}
});
