<?php
/* SVN FILE: $Id$ */
/**
 * [Sitemapxml] サイトマップXML生成
 *
 * PHP version 5
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2012, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2011 - 2012, Catchup, Inc.
 * @link			http://www.e-catchup.jp Catchup, Inc.
 * @package			sitemapxml.controllers
 * @since			Baser v 2.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			MIT lincense
 */

/**
 * サイトマップXMLクリエーターコントローラー
 *
 * @package	sitemap_xml.controllers
 */
class SitemapxmlController extends AppController {
/**
 * コントローラー名
 * 
 * @var string
 */
	public $name = 'Sitemapxml';
/**
 * モデル
 * 
 * @var array
 */
	public $uses = array('SearchIndex');
/**
 * コンポーネント
 *
 * @var array
 * @access public
 */
	public $components = array('BcAuth','Cookie','BcAuthConfigure');
/**
 * [ADMIN] サイトマップXML生成実行ページ
 */
	public function admin_index() {
		
		$path = WWW_ROOT . Configure::read('Sitemapxml.filename');	
		$dirWritable = false;
		$fileWritable = false;
		$robotsPath = WWW_ROOT . 'robots.txt';
		$robotsFileExists = false;
		$robotsInforExists = false;
		
		// ディレクトリ書き込み確認
		if (is_writable(dirname($path))) {
			$dirWritable = true;
		}
		// ファイル書き込み確認
		if (file_exists($path)) {
			if (is_writable($path)) {
				$fileWritable = true;
			}
		} elseif ($dirWritable) {
			$fileWritable = true;
		}
		
		// robots.txt 存在確認
		if (file_exists($robotsPath)) {
			$robotsFileExists = true;
			
			// robots.txt xml設定確認
			$fp = fopen($robotsPath, 'r');
			while ($line = fgets($fp)) {
				// Sitemap という文字と ファイル名が1行に存在する場合に記述があると判断
				if (strpos($line, 'Sitemap') !== false && strpos($line, Configure::read('Sitemapxml.filename')) !== false) {
					$robotsInforExists = true;
				}
			}
			fclose($fp);
		}
		
		
		
		
		if($fileWritable && 
			isset($this->data['Sitemapxml']['exec']) && 
			$this->data['Sitemapxml']['exec']) {
			$sitemap = $this->requestAction('/admin/sitemapxml/sitemapxml/create', array('return', $this->request->data));
			ClassRegistry::removeObject('View');
			$File = new File($path);
			$File->write($sitemap);
			$File->close();
			$this->setMessage('サイトマップの生成が完了しました。');
			chmod($path, 0666);
		}
		
		$this->set('path', $path);
		$this->set('fileWritable', $fileWritable);
		$this->set('dirWritable', $dirWritable);
		$this->set('robotsPath', $robotsPath);
		$this->set('robotsFileExists', $robotsFileExists);
		$this->set('robotsInforExists', $robotsInforExists);
		$this->pageTitle = 'サイトマップXML作成';
		$this->render('index');
		
	}
/**
 * [ADMIN] サイトマップXML生成処理
 */
	public function admin_create() {
		
		$this->layout = false;
		$datas = $this->SearchIndex->find('all', array('conditions' => array('SearchIndex.status' => true)));
		$this->set('datas', $datas);
		Configure::write('debug', 0);
		$this->render('sitemap');

	}
	
}