<?php
class ToolbarColorChangerViewEventListener extends BcViewEventListener
{
	public $events = [
		'footer',
	];

	public function footer(CakeEvent $event)
	{
		$View = $event->subject();
		$backgroundColor = Configure::read('ToolbarColorChanger.background');
		if (!$backgroundColor) {
			return;
		}
		$event->data['out'] .= $View->element('ToolbarColorChanger.toolbar_style', compact('backgroundColor'));
		if (BcUtil::isAdminSystem()) {
			$event->data['out'] .= $View->element('ToolbarColorChanger.admin/footer_style', compact('backgroundColor'));
		}
	}
}
