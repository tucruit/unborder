<?php
class OptionalLinkConfigTest extends BaserTestCase {
	public function setUp() {
		parent::setUp();
		$this->OptionalLinkConfig = new OptionalLinkConfig();
	}

	public function tearDown() {
		parent::tearDown();
	}
	
	public function testGetDefaultValue() {
		$defaultValut = $this->OptionalLinkConfig->getDefaultValue();
		$this->assertArrayHasKey('OptionalLinkConfig', $defaultValut);
		$this->assertArrayHasKey('status', $defaultValut['OptionalLinkConfig']);
	}

	
	
}
