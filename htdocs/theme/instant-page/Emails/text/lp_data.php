<?php
$group_field = null;
foreach ($mailFields as $field) {
	$field = $field['MailField'];
	if ($field['type'] != 'hidden' && $field['use_field'] && isset($message[$field['field_name']]) && ($group_field != $field['group_field'] || (!$group_field && !$field['group_field']))) {
?>

［<?php echo $field['head']; ?>］　
<?php
	}
	// 説明文はメール本文内に不要なケースがあるためコメントアウト
	// if ($field['type'] != 'file' && !empty($field['before_attachment']) && isset($message[$field['field_name']])) {
	//	echo " " . $field['before_attachment'];
	// }
	if ($field['type'] != 'hidden' && isset($message[$field['field_name']]) && !$field['no_send'] && $field['use_field']) {
		if($field['type'] != 'file') {
			$body = $this->Maildata->control($field['type'], $message[$field['field_name']], $this->Mailfield->getOptions($field));


			// 1行が1000byteを超えた際に本文が文字化けするため、適度な長さで改行を挿入する処理
			// RFC 2822に従い78文字（全角39文字）で改行
			// http://www.puni.net/~mimori/rfc/rfc2822.txt
			$line = mb_split("\n", $body);
			$body_tmp = NULL;
			$line_length = 0;

			$part_length = 39;

			for ($i = 0; $i < count($line); $i++) {
    			$line_length = strlen($line[$i]);
    			$one_line = NULL;
				// ASCII文字のみであれば、最大制限文字数の2倍の文字数までを許可する
    			if ($line_length > ($part_length * 2)) {
        			$mb_length = mb_strlen($line[$i]);
					// メール全体の行数を求める
        			if (($mb_length % $part_length) == 0) {
            			$loop_cnt = $mb_length / $part_length;
			        } else {
            			$loop_cnt = ceil(mb_strlen($line[$i]) / $part_length);
			        }
        			$start_num = 0;
					// 1行ごとに制限文字数内で分解して改行コードを挿入する
					for ($j = 1; $j <= $loop_cnt; $j++) {
						// 制限文字数単位で改行コード挿入
						$one_line .= mb_substr($line[$i], $start_num, $part_length) . PHP_EOL;
						$start_num = $part_length * $j;
					}
				} else {
					$one_line = $line[$i] . PHP_EOL;
				}
				$body_tmp .= $one_line;
			}
			$body = $body_tmp;
			// 改行処理ここまで

			// パスワードを伏せ字にする
			if (strpos($field['field_name'], 'password_') !== false) {
				$body = str_repeat('*', strlen($body));
			}

			echo $body;


		} else {
			if($message[$field['field_name']]) {
				echo '添付あり';
			} else {
				echo '添付なし';
			}
		}
	}
	// 説明文はメール本文内に不要なケースがあるためコメントアウト
	// if ($field['type'] != 'file' && !empty($field['after_attachment']) && isset($message[$field['field_name']])) {
	//	echo " " . $field['after_attachment'];
	// }
	$group_field = $field['group_field'];
}
?>　
