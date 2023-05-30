<?php
/**
 * [Config] ルーティング
 *
 * @copyright		Copyright, Catchup, Inc.
 * @link			https://catchup.co.jp
 * @package			OptionalLink
 */
/**
 * オプショナルリンクで生成したURLにアクセスした際に、公開期間制限に掛かっているかを判定する
 */
Router::connect('/files/optionallink/*', array('plugin' => 'optional_link', 'controller' => 'optional_links', 'action' => 'view_limited_file'));
