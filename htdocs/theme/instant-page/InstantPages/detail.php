<?php
/*
 * インスタントページ公開テーマ
 */
$this->BcBaser->setTitle(strip_tags($data['InstantPage']['title']));
$this->BcBaser->setDescription(strip_tags($data['InstantPage']['page_description']));
$this->BcBaser->setKeywords(strip_tags($data['InstantPage']['page_key_word']));
?>

<div style="width: 100%;max-width: 1200px;margin: 0 auto;padding: 0 15px;">
<?php echo $data['InstantPage']['contents'] ?>
</div>
