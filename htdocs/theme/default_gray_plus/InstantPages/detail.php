<?php
/*
 * インスタントページ公開テーマ
 */
$this->BcBaser->setTitle(strip_tags($data['InstantPage']['title']));
$this->BcBaser->setDescription(strip_tags($data['InstantPage']['page_description']));
$this->BcBaser->setKeywords(strip_tags($data['InstantPage']['page_key_word']));
?>

<div class="l-container">
<?php
echo $data['InstantPage']['contents'];
?>
</div>