<?php
Router::connect('/instant_pages/', ['plugin' => 'instant_page', 'controller' => 'instant_page_users', 'action' => 'login']);
Router::redirect('/mypage/users/edit/*', '/mypage/instant_page_users/edit/', array('status' => 302));
