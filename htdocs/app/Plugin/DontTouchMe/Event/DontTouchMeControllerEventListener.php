<?php
class DontTouchMeControllerEventListener extends BcControllerEventListener {
    public $events = array(
		'Contents.startup',
		'Pages.startup',
    );
	
	private $denyMessage = '対象コンテンツは操作が禁止されています';
	
	// コンテンツ編集
	public function contentsStartup(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}
		$checkActionList = array('admin_ajax_change_status', 'admin_ajax_delete', 'admin_ajax_move', 'admin_delete');
		$controller = $event->subject();
		
		if (in_array($controller->request->params['action'], $checkActionList)) {
			$checkAction = Inflector::camelize('check_content_' . $controller->request->params['action']);
			return $this->$checkAction($controller);
		}
	}
	
	private function checkContentAdminAjaxChangeStatus($controller) {
		if (empty($controller->request->data['contentId'])) {
			return;
		}
		if (in_array($controller->request->data['contentId'], Configure::read('DontTouchMe.Contents'))) {
			echo $this->denyMessage;
			exit;
		}
	}
	private function checkContentAdminAjaxDelete($controller) {
		if (empty($controller->request->data['contentId'])) {
			return;
		}
		if (in_array($controller->request->data['contentId'], Configure::read('DontTouchMe.Contents'))) {
			$controller->ajaxError(500, $this->denyMessage);
			exit;
		}
	}
	private function checkContentAdminAjaxMove($controller) {
		// moveの場合は currentId より取得する （not contentId）;
		if (empty($controller->request->data['currentId'])) {
			return;
		}
		if (in_array($controller->request->data['currentId'], Configure::read('DontTouchMe.Contents'))) {
			$controller->ajaxError(500, $this->denyMessage);
			exit;
		}
	}
	private function checkContentAdminDelete($controller) {
		// deleteの場合は [Content][Id] より取得する （not contentId）;
		if (empty($controller->request->data['Content']['id'])) {
			return;
		}
		if (in_array($controller->request->data['Content']['id'], Configure::read('DontTouchMe.Contents'))) {
			$controller->BcMessage->set($this->denyMessage, true, true);
			$controller->redirect(array('prefix' => 'admin', 'controller' => 'contents', 'action' => 'index'));
			exit;
		}
	}
	
	public function pagesStartup(CakeEvent $event) {
		if (!BcUtil::isAdminSystem()) {
			return;
		}
		$checkActionList = array('admin_edit');
		$controller = $event->subject();
		// data送信時のみ検証
		if (!empty($controller->request->data)) {
			if (in_array($controller->request->params['action'], $checkActionList)) {
				$checkAction = Inflector::camelize('check_page_' . $controller->request->params['action']);
				return $this->$checkAction($controller);
			}
		}
	}
	
	private function checkPageAdminEdit($controller) {
		if (empty($controller->request->data['Content']['id'])) {
			return;
		}
		if (in_array($controller->request->data['Content']['id'], Configure::read('DontTouchMe.Contents'))) {
			$controller->BcMessage->set($this->denyMessage, true, true);
			$controller->redirect(array('action' => 'edit', $controller->request->data['Page']['id']));
			exit;
		}
	}

}