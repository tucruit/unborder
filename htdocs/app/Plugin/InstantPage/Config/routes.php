<?php
$pageRoutes = configure::read('pageRoutes');
Router::connect('/instant_page/instant_page_users/login', ['plugin' => 'instant_page', 'controller' => 'instant_page_users', 'action' => 'login']);
Router::connect($pageRoutes.'*', ['plugin' => 'instant_page', 'controller' => 'instant_pages', 'action' => 'detail']);
// Router::redirect('/mypage/users/edit/*', '/mypage/instant_page_users/edit/', array('status' => 302));
// Router::redirect('/users/login', '/instant_page/instant_page_users/login', array('status' => 302));
