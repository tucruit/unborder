<?php
class OptionalLinkAllTest extends CakeTestSuite {

/**
 * Suite define the tests for this suite
 *
 * @return CakeTestSuite
 */
	public static function suite() {
		$suite = new CakeTestSuite('OptionalLink All Tests');
		
		$selfFileName = __FILE__;
		$basePath = __DIR__;
		$fileList = [];
		
		$iterator = new RecursiveDirectoryIterator($basePath);
		$iterator = new RecursiveIteratorIterator($iterator);
		foreach ($iterator as $fileinfo) {
			if ($fileinfo->isFile() && 
				preg_match("/Test.php\z/", $fileinfo->getFilename()) === 1 &&
				$fileinfo->getFilename() !== $selfFileName ) {
					
				$suite->addTestFile($fileinfo->getPathname());
			}
		}
		
		// プラグインの有効化
		$plugin = new Plugin();
		$enable = $plugin->find('first', [
			'conditions' => [
				'Plugin.status' => true,
				'Plugin.name' => 'OptionalLink'
			],
			'order' => 'Plugin.priority'
		]);
		
		// var_dump($enable);exit;
		
		return $suite;
	}

}
