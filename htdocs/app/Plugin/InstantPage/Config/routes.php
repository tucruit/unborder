<?php
Router::connect('/instant_pages/*', array('plugin' => 'instant_page', 'controller' => 'InstantPages', 'action' => 'index'));
Router::redirect('/mypage/users/edit/*', '/mypage/instant_page_users/edit/', array('status' => 302));
