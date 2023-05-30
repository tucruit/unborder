<?php
$version = $this->BurgerEditor->getMajorVersionOfSystem();
if ($version < 4) {
	$this->BcBaser->js('admin/jquery.upload-1.0.0.min');
} else {
	$this->BcBaser->js('admin/vendors/jquery.upload-1.0.0.min');
}
