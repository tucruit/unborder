<?php
/* SVN FILE: $Id$ */
/**
 * [Sitemapxml] サイトマップXML出力ページ
 *
 * PHP version 5
 *
 * baserCMS :  Based Website Development Project <http://basercms.net>
 * Copyright 2008 - 2012, baserCMS Users Community <http://sites.google.com/site/baserusers/>
 *
 * @copyright		Copyright 2011 - 2012, Catchup, Inc.
 * @link			http://www.e-catchup.jp Catchup, Inc.
 * @package			sitemapxml.views
 * @since			Baser v 2.0.0
 * @version			$Revision$
 * @modifiedby		$LastChangedBy$
 * @lastmodified	$Date$
 * @license			MIT lincense
 */
?>

<p>検索インデックスのデータを元にサイトマップXMLを生成します。</p>
<p>出力先のパス：<?php echo $path ?></p>
<p>※ ファイル名は、/Config/setting.php で変更できます。</p>

<?php if($fileWritable && $dirWritable): ?>
<?php echo $this->BcForm->create('Sitemapxml', array('url' => array('controller' => 'sitemapxml', 'action' => 'index'))) ?>
<?php echo $this->BcForm->hidden('Sitemapxml.exec', array('value' => true)) ?>
<div class="submit">
	<?php echo $this->BcForm->submit('生成実行', array('div' => false, 'class' => 'button bca-btn')) ?>
</div>
<?php endif ?>

<?php if(!$dirWritable): ?>
	<div class="message"><?php echo h(dirname($path)) ?> に書込権限を与えてください。</div>
<?php endif ?>
<?php if(!$fileWritable): ?>
	<div class="message"><?php echo h($path) ?> に書込権限を与えてください。</div>
<?php endif ?>

<?php if(!$robotsFileExists): ?>
	<div class="message"><?php echo h($robotsPath) ?> を生成してください。</div>
<?php endif ?>
<?php if(!$robotsInforExists): ?>
	<div class="message">
		<?php echo h($robotsPath) ?> にSitemap情報を記述してください。<br />
		例) Sitemap: <?php echo h(Configure::read('BcEnv.siteUrl') . Configure::read('Sitemapxml.filename')) ?>
	</div>
<?php endif ?>

<?php echo $this->BcForm->end() ?>