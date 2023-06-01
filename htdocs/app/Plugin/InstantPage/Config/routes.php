<?php
$pageRoutes = configure::read('pageRoutes');
Router::connect('/instant_pages/', ['plugin' => 'instant_page', 'controller' => 'instant_page_users', 'action' => 'login']);
Router::connect($pageRoutes.'*', ['plugin' => 'instant_page', 'controller' => 'instant_pages', 'action' => 'detail']);
Router::redirect('/mypage/users/edit/*', '/mypage/instant_page_users/edit/', array('status' => 302));
