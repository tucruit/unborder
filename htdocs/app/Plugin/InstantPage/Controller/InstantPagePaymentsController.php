<?php

/**
 * Class InstantPagePaymentsController
 * 決済用コントローラー
 *
 * @package instant-page
 */
class InstantPagePaymentsController extends AppController {

	/**
	 * ControllerName
	 *
	 * @var string
	 */
	public $name = 'InstantPagePayments';

	/**
	 * Model
	 *
	 * @var array
	 */
	public $uses = [
		'InstantPage.InstantPage',
		'InstantPage.InstantPageTemplate',
		'InstantPage.InstantPageUser',
		'InstantPage.InstantPagePaymentLog',
		'User'
	];

	/**
	 * ヘルパー
	 *
	 * @var array
	 */
	public $helpers = [];

	/**
	 * コンポーネント
	 *
	 * @var array
	 * @deprecated useViewCache 5.0.0 since 4.0.0
	 */
	public $components = [
		'RequestHandler',
		'BcReplacePrefix',
		'BcAuth',
		'Cookie',
		'BcAuthConfigure',
		'BcContents' => [
			'type' => 'InstantPage.InstantPage', 'useForm' => true, 'useViewCache' => false
		]
	];

	/**
	 * 決済API利用情報
	 *
	 * @var array[]
	 * @access public
	 */
	public $paymentServer = [
		'demo' => [
			'merchant_id' => 53214,
			'connection_id' => 'test53214',
			'connection_pass' => '6Y8dQx347f1Z',
			'token_gen_key' => 'test_k54dffyZsc2GQ19SuIlxC1NJ',
			'token_get_key' => 'testCK_qY5snoNQsjXZN73hKlQItByb',
			'hash_gen_key' => 'goljEUm47aq',
			'url' => 'https://sandbox.paygent.co.jp/v/u/request',//UTF-8利用のURL
			'return_url' => 'http://localhost:8137/cmsadmin/instant_page/instant_page_payments/payment_result'
		],
		'prod' => [
			'merchant_id' => 62623,
			'connection_id' => 'pgynt62623',
			'connection_pass' => 'VYZhFOSEk2a7',
			'token_gen_key' => 'live_xZutCLkHOosrCwPKKzfbBFTv',
			'token_get_key' => 'ck_CvKAf44sfmIyxrUkZTFCfh48',
			'hash_gen_key' => '0KV0t7NEhH',
			'url' => 'https://link.paygent.co.jp/v/u/request',
			'return_url' => 'https://instantpage.jp/cmsadmin/instant_page/instant_page_payments/payment_result'
		]
	];

	/**
	 * beforeFilter
	 *
	 * @return void
	 */
	public function beforeFilter()
	{
		parent::beforeFilter();
		if (empty($this->siteConfigs['editor']) || $this->siteConfigs['editor'] === 'none') {
			return;
		}
		$this->helpers[] = $this->siteConfigs['editor'];
		$this->Security->validatePost = false;
		$this->Security->csrfCheck = false;
	}


	public function admin_index()
	{
		//後日必要？
	}


	/**
	 * 決済実施前の確認画面
	 *
	 * @param $planId int 決済するプランのID（価格が異なるため）
	 * @return void
	 */
	public function admin_payment($planId = null)
	{
		$this->mypage_payment($planId);
	}


	/**
	 * 決済実施後の処理
	 *
	 * @param $tradingId string 決済ID
	 * @return void
	 */
	public function admin_payment_result($tradingId)
	{
		$this->mypage_payment_result($tradingId);
	}


	/**
	 * [MY PAGE] 決済実施の確認画面
	 *
	 * @param $planId int 決済するプランのID（価格が異なるため）
	 * @return void
	 */
	public function mypage_payment($planId = null)
	{
		//ユーザー取得と現在のプラン確認
		$userData = BcUtil::loginUser();
		//---------------
		// 決済ボタン押下
		//---------------
		if(!empty($this->request->data)){
			if(!$this->isValidPayment($userData, $planId)){ //適格性チェック
				$this->notFound();//除外処理
			}
			//URL取得
			$response = $this->_getPaymentUrl($userData, $planId);
			//ログ作成
			$insert['InstantPagePaymentLog']['user_id'] = $userData['id'];
			$insert['InstantPagePaymentLog']['trading_id'] = $response["trading_id"];
			$insert['InstantPagePaymentLog']['plan_id'] = $planId;
			if($this->InstantPagePaymentLog->save($insert)){
				if($response["result"] != "1") {
					$this->redirect($response["url"]); //決済API
					exit;
				} else {
					var_dump($response);
					exit;
				}
			} else {
				echo 'ログの保存に失敗しました。管理者に連絡してください。';
				exit;
			}
		}
		//---------------
		// 初期表示（決済金額などの表示）
		//---------------
		$this->set('paymentPlan', $planId);
		$this->set('paymentPrice', $this->_getPaymentPrice($planId));
		$this->render('mypage'.DS.'payment');
	}


	/**
	 * [My Page] 決済実施後の処理
	 *
	 * @param $tradingId string 決済ID
	 * @return void
	 */
	public function mypage_payment_result($tradingId)
	{
		//除外処理
		if(empty($tradingId)){
			$this->redirect('/');
		}
		$tradingData = $this->InstantPagePaymentLog->find('first',['conditions' => [
			'InstantPagePaymentLog.trading_id' => $tradingId,
			'InstantPagePaymentLog.user_id' => $this->BcAuth->user('id'),
		]]);
		//除外処理
		if(empty($tradingData)){
			$this->redirect('/');
		} else {
			//ユーザーの有料フラグを入れる
			$myData = $this->InstantPageUser->findByUserId($this->BcAuth->user('id'));
			if(empty($myData)){
				$this->redirect('/');
			} else {
				$myData['InstantPageUser']['plan_id'] = $tradingData['InstantPagePaymentLog']['plan_id'];
				if($this->InstantPageUser->save($myData)){
					$this->setMessage('決済が完了しました。', false, true);
					$this->redirect('/cmsadmin/instant_page/instant_pages/');
				}
			}
		}
	}


	/**
	 * 決済前の適格性確認
	 *
	 * @param $user
	 * @param $planId
	 * @return bool
	 */
	private function isValidPayment($user, $planId){
		if(!$this->_isInstantPageUser($user)){ //ユーザーかどうか
			return false;
		}
		if(!$this->_isAppropriatePlan($user, $planId)){ //決済プランが的確かどうか
			return false;
		}
		return true;
	}


	/**
	 * プランIDから決済すべき金額を取得する
	 *
	 * @param $planId
	 * @return int
	 */
	private function _getPaymentPrice($planId)
	{
		$price = [1 => 0, 2 => 3190, 3 => 3800]; //月額料金
		return $price[$planId];
	}


	/**
	 * ユニークな決済IDを取得する
	 *
	 * @param $user
	 * @return string
	 */
	private function _getTradingId($user)
	{
		return 'user_'.$user['id'].'_'.date('Ymd_His');
	}


	/**
	 * 決済API情報を取得する
	 * （ドメインを見てデモと本番とを振り分ける）
	 *
	 * @return array
	 */
	private function _getPaymentServer()
	{
		if (strpos($_SERVER['HTTP_HOST'], 'instantpage.jp') !== false) { //本番ドメイン
			return $this->paymentServer['prod'];
		} else {
			return $this->paymentServer['demo'];
		}
	}


	/**
	 * 現在のプランと決済したいプランとの適格性を返す
	 * （現状と同じプランは決済させない）
	 *
	 * @param $user
	 * @param $planId
	 * @return bool
	 */
	private function _isAppropriatePlan($user, $planId)
	{
		//TODO:グレードダウンのスキームを確認する
		if((int)$planId <= (int)$user['InstantPageUser']['plan_id']){
			return false;
		} else {
			return true;
		}
	}


	/**
	 * インスタントページユーザーかどうかの確認
	 *
	 * @param $user
	 * @return false|void
	 */
	private function _isInstantPageUser($user){
		if($user['user_group_id'] != 4){ //4:利用者
			return false;
		} else {
			return true;
		}
	}


	/**
	 * 決済用URLの取得
	 * （決済APIへ情報を投げてから専用URLを取得する）
	 *
	 * @param $user
	 * @param $planId
	 * @return array
	 */
	private function _getPaymentUrl($user, $planId)
	{
		//接続情報
		$serverData = $this->_getPaymentServer();
		$url = $serverData['url'];
		//POST情報
		$data = $this->_getPostPaymentParame($user, $planId);
		$context = array(
			'http' => array(
				'method'  => 'POST',
				'header'  => implode("\r\n", array('Content-Type: application/x-www-form-urlencoded',)),
				'content' => http_build_query($data)
			)
		);
		$fp = fopen($url, 'r', false, stream_context_create($context));
		$header = stream_get_meta_data($fp);
		$html = stream_get_contents($fp);
		fclose($fp);
		//返り値は改行で区切られているので、とりあえずLFに統一したうえで、配列にする。
		$responseText = str_replace(array("\r\n", "\r", "\n"), "\n", $html);
		$textDatas = explode("\n", $responseText);
		//配列の中身は「=」でさらに区切られるので、再び配列にする。
		$res = [];
		foreach ($textDatas as $text){
			$str = explode('=', $text);
			//「=」だけで区切ると、URLに入っているパラメータまで区切ってしまう・・・。
			if(!empty($str[0])){
				$res[$str[0]] = $str[1];
				if(count($str) > 2){ //最初の＝以外に区切られてしまったものがある
					for($i=2;$i<count($str);$i++){
						$res[$str[0]] .= '='.$str[$i]; //もう一度つけなおす
					}
				}
			}
		}
		return $res;
	}


	/**
	 * 決済APIへ投げるためのPOSTデータ生成
	 *
	 * @param $user
	 * @param $planId
	 * @return array
	 */
	private function _getPostPaymentParame($user, $planId)
	{
		$serverData = $this->_getPaymentServer();
		$tradingId = $this->_getTradingId($user);
		$data = [
			'trading_id' => $tradingId,
			'payment_type' => '02',//カード決済指定
			'stock_card_mode' => 2,//カード情報の登録（継続課金のため）
			'id' => $this->_getPaymentPrice($planId),
			'seq_merchant_id' => $serverData['merchant_id'],
			'merchant_name' => 'インスタントページ',
			'payment_detail' => '月額利用料決済',
			'banner_url' => 'https://instantpage.jp/theme/instant-page/img/common/logo.svg',
			'return_url' => $serverData['return_url'].'/'.$tradingId,
			'customer_family_name' => $user['real_name_1'],
			'customer_name' => $user['real_name_2'],
			'customer_id' => $user['id'],
			'isbtob' => 1,
			'payment_term_day' => 5,
			'payment_class' => 0,
			'use_card_conf_number' => 0,
			'threedsecure_ryaku' => 0,
			'finish_disable' => 1,//完了画面を表示せず、リターンURLに戻ってくる（完了画面はこっちで出す）
		];
		//ハッシュ値は上のPOST用データをもとに生成されるので、ハッシュだけあとで作る。
		$data['hc'] = $this->_getPaymentHash($data);
		return $data;
	}


	/**
	 * ハッシュ生成
	 * （公式のサンプルプログラムから生成）
	 *
	 * @param $data
	 * @return string
	 */
	private function _getPaymentHash($data)
	{
		$serverData = $this->_getPaymentServer();
		//生成
		$trading_id = $data['trading_id'];
		$payment_type = $data['payment_type'];
		$fix_params = "";
		$id = $data['id'];
		$seq_merchant_id = $data['seq_merchant_id'];
		$payment_term_day = $data['payment_term_day'];
		$payment_term_min = "";
		$payment_class = "0";
		$use_card_conf_number = "0";
		$customer_id = $data['customer_id'];
		$threedsecure_ryaku = "0";
		$hash_key = $serverData["hash_gen_key"];
		// create hash hex string
		$org_str = $trading_id .
			$payment_type .
			$fix_params .
			$id .
			$seq_merchant_id .
			$payment_term_day .
			$payment_term_min .
			$payment_class .
			$use_card_conf_number .
			$customer_id .
			$threedsecure_ryaku .
			$hash_key;
		$hash_str = hash("sha256", $org_str);
		// create random string
		$rand_str = "";
		$rand_char = array('a', 'b', 'c', 'd', 'e', 'f', 'A', 'B', 'C', 'D', 'E', 'F', '0', '1', '2', '3', '4', '5', '6', '7', '8', '9');
		for ($i = 0; ($i < 20 && rand(1, 10) != 10); $i++) {
			$rand_str .= $rand_char[rand(0, count($rand_char) - 1)];
		}
		return $hash_str . $rand_str;
	}




}
