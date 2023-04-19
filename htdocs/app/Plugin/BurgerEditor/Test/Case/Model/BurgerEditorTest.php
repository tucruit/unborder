<?php
class BurgerEditorTest extends BaserTestCase {
	public function setUp() {
		parent::setUp();
		$this->BurgerEditor = ClassRegistry::init('BurgerEditor.BurgerEditor');
	}

	public function tearDown() {
		unset($this->BurgerEditor);
		parent::tearDown();
	}

	public function testGetBasePath() {
		$result = $this->BurgerEditor->getBasePath();
		$expects = APP . 'Plugin/BurgerEditor/';
		$this->assertEquals($expects, $result, 'ベースとなるパスが間違っています。');
	}

	public function testBlockPath() {
		$result = $this->BurgerEditor->getBlockPath();
		$expects = APP . 'Plugin/BurgerEditor/Addon/Block/';
		$this->assertEquals($expects, $result, 'ブロックフォルダのパスが間違っています。');
	}
}
