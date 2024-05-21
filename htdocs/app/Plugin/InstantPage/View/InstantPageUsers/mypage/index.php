<?php
$this->layout = 'mypage_login';
echo '<pre>';
print_r($_SESSION['Auth']['InstantPageUser']);
echo '</pre>';
p($this->request->data);
?>
<h2>マイページダッシュボード</h2>
