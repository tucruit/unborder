<?php
/**
 * テーマヘルパー
 *
 */
class ThemeMailHelper extends AppHelper {
	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = array(
		'BcHtml',
		'BcBaser',
		'Mail',
		'Mailform',
		'Mailfield',
	);

	public $freezed = false;


	public function create($option){

		$options = ['type' => 'file', 'novalidate' => 'novalidate', 'accept-charset' => 'utf-8'];

		if(!$this->freezed){
			$options['url'] = $this->BcBaser->getContentsUrl(null, false, null, false) . 'confirm';
		}
		else{
			$options['url'] = $this->BcBaser->getContentsUrl(null, false, null, false) . 'submit';
		}

		$options = array_merge($options, $option);

		echo $this->Mailform->create('MailMessage', $options);

	}

	public function freeze(){
		$this->Mailform->freeze();
		$this->freezed = true;
	}


	// フォーム説明文の出力
	// $this->freezedの状態によって出力する文字列が変化します。
	public function description($message = '以下の内容で間違いがなければ、「送信する」ボタンを押してください。') {
		if(!$this->freezed){
			$this->Mail->description();
		}else{
			echo $message;

		}
	}


	// フィールドの出力
	public function mailForm($mailFields, $options){

		if (empty($mailFields)) {
			return true;
		}

		if(empty($options['templates']['default'])){
			echo "メールフィールドテンプレートが見つかりませんでした。";
			return true;
		}

		$output = [];


		// データ整形
		foreach($mailFields as $key => $row){
			$field = $row['MailField'];

			// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
			// フィールド値凡例
			// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
		    // id 				: 内部ID
			// mail_content_id 	: メールフォームID
			// no 				: レコードNO
			// name 			: フォーム名称
			// field_name 		: フォームname値
			// type 			: フォームtype値
			// head 			: 項目名
			// attention 		: 注意書き
			// before_attachment : 前見出し
			// after_attachment : 後見出し
			// source 			: 未使用
			// size 			: フォームsize値
			// rows 			: フォームrows値
			// maxlength 		: フォームmax値
			// options 			: フォーム選択値
			// class 			: フォームclass
			// separator 		: 未使用
			// default_value 	: フォームvalue値
			// description 		: 説明文
			// group_field 		: group_name
			// group_valid 		: group_check
			// valid 			: VALID_NOT_EMPTY
			// valid_ex 		:
			// auto_convert 	:
			// not_empty 		: true
			// use_field 		: true
			// no_send 			: false
			// sort 			: 1
			// created 			: 2018-04-24 22:09:06
			// modified 		: 2018-04-24 22:12:42
			// ━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━


			$error = '';
			$isGroupValidComplate = in_array('VALID_GROUP_COMPLATE', explode(',', $field['valid_ex']));
			if(!$isGroupValidComplate) {
				$error .= $this->Mailform->error("MailMessage." . $field['field_name']);
			}
			if ($this->Mailform->isGroupLastField($mailFields, $field)) {
				if($isGroupValidComplate) {
					$groupValidErrors = $this->Mailform->getGroupValidErrors($mailFields, $field['group_valid']);
					if ($groupValidErrors) {
						foreach($groupValidErrors as $groupValidError) {
							$error .= $groupValidError;
						}
					}
				}
				$error .= $this->Mailform->error("MailMessage." . $field['group_valid'] . "_not_same", __("入力データが一致していません。"));
				$error .= $this->Mailform->error("MailMessage." . $field['group_valid'] . "_not_complate", __("入力データが不完全です。"));
			}


			// typeの設定
			$type = $field['type'];

			if($type == 'multi_check'){
				$type = 'checkbox';
			}elseif($type == 'autozip'){
				$type = 'text';
			}


			// controlの設定
			$control = $this->Mailform->control($field['type'], "MailMessage." . $field['field_name'] . "", $this->Mailfield->getOptions($field), $this->Mailfield->getAttributes($field));

			// baserCMSのフォームだと自動でラッパーが付いてくるので分解して保持しておく
			$controls = [];

			if($type == 'checkbox' || $type == 'radio'){
				preg_match_all("/<input(.*?)>/", $control, $inputs, PREG_PATTERN_ORDER);
				preg_match_all("/<label(.*?)>(.*?)<\/label>/", $control, $labels);

				// inputの0番目はhiddenなのでラベル側をunshiftしておく
				array_unshift($labels[2], '');

				foreach($inputs[0] as $key => $input){
					$controls[$key] = [
						'input' => $input,
						'label' => $labels[2][$key]
					];
				}
			}else{
				$controls = [0 => ['input' => $control, 'label' => $field['name']]];
			}


			$outputTmp = [
				'field_element_id'  => 'MailMessage' . Inflector::camelize($field['field_name']),
				'head' 				=> $field['head'],
				'name' 				=> $field['name'],
				'field_name' 		=> $field['field_name'],
				'require' 			=> $field['not_empty'],
				'attention' 		=> !$this->freezed? $field['attention'] : '',
				'type' 				=> $type,
				'class' 			=> $field['class'],
				'before_attachment' => $field['before_attachment'],
				'control' 			=> $control,
				'controls' 		=> $controls,
				'description' 		=> !$this->freezed? $field['description'] : '',
				'after_attachment' 	=> $field['after_attachment'] ,
				'error' 			=> !$this->freezed? strip_tags($error) : '',
				'raw' 				=> $field,
			];




			// グループフィールドの場合はデータをマージ
			if(!empty($field['group_field'])){

				$existsKey = null;

				foreach($output as $k => $v){
					if($v['group_name'] == $field['group_field']){
						$existsKey = $k;
						break;
					}
				}

				if(is_numeric($existsKey)){
					$output[$existsKey]['fields'][] = $outputTmp;
				}else{
					$output[] = [
						'group_name' => $field['group_field'],
						'fields' => [
							$outputTmp
						]
					];
				}

			}else{
				$output[] = [
					'group_name' => null,
					'fields' => [
						$outputTmp
					]
				];
			}
		}

		// blockStartとblockEnd に対応
		$iteration = 0;
		$blockStart = empty($options['blockStart']) ? 0 : $options['blockStart'];
		$blockEnd = empty($options['blockEnd']) ? 0 : $options['blockEnd'];

		//テンプレートを選択して出力
		foreach($output as $data){
			$iteration ++;
			// blockStartとblockEndの間に入らなければスキップ
			if ($blockStart > $iteration || ($blockEnd != 0 && $blockEnd < $iteration)) {
				continue;
			}

			// フィールドがhiddenだったらテンプレートを適応しない
			if($data['fields'][0]['type'] == 'hidden'){
				echo '<span style="display:none;">' . $data['fields'][0]['control'] . '</span>';
				continue;
			}

			// 標準テンプレートで初期化
			$template = $options['templates']['default'];

			// シングルフィールドかつテンプレートが定義されている場合はセット
			if(empty($data['group_name']) && !empty($options['templates']['single'])){
				$template = $options['templates']['single'];
			}

			// グループフィールドかつグループ用テンプレートが定義されている場合はセット
			if(!empty($data['group_name']) && !empty($options['templates']['group'])){
				$template = $options['templates']['group'];
			}

			// カスタムテンプレートが定義されている場合はセット
			if(isset($options['templates']['custom_' . $data['fields'][0]['field_name']])){
				$template = $options['templates']['custom_' . $data['fields'][0]['field_name']];
			}

			// カスタムフィールドテンプレートのロードは、グループ名でも可能
			if(!empty($data['group_name']) && isset($options['templates']['custom_' . $data['group_name']])){
				$template = $options['templates']['custom_' . $data['group_name']];
			}

			$this->BcBaser->element($template, ['data' => $data]);

		}

	}


	// 確認画面への遷移ボタンの出力
	// フォームが入力状態の場合のみボタンが表示されます。
	public function confirmBtn($label = '確認する', $option = []){

		if($this->freezed) return '';

		$options = ['type' => 'submit', 'div' => false, 'id' => 'BtnMessageConfirm'];
		$options = array_merge($options, $option);

		echo $this->Mailform->button($label, $options);
	}

	// 入力画面への遷移ボタンの出力
	// フォームが確認状態の場合のみボタンが表示されます。
	public function backBtn($label = '戻る', $option = []){

		if(!$this->freezed) return '';

		$options = ['type' => 'submit', 'div' => false, 'id' => 'BtnMessageBack'];
		$options = array_merge($options, $option);

		echo $this->Mailform->button($label, $options);

	}

	// 送信完了画面への遷移ボタンの出力
	// フォームが確認状態の場合のみボタンが表示されます。
	public function submitBtn($label = '送信する', $option = []){

		if(!$this->freezed) return '';

		$options = ['type' => 'submit', 'div' => false, 'id' => 'BtnMessageSubmit'];
		$options = array_merge($options, $option);

		echo $this->Mailform->button($label, $options);

	}


}
