<?php
/**
 * baserCMS :  Based Website Development Project <https://basercms.net>
 * Copyright (c) baserCMS Users Community <https://basercms.net/community/>
 *
 * @copyright       Copyright (c) baserCMS Users Community
 * @link            https://basercms.net baserCMS Project
 * @package         Baser.View
 * @since           baserCMS v 2.0.0
 * @license         https://basercms.net/license/index.html
 */

/**
 * [ADMIN] ユーザー一覧　ヘルプ
 */
?>


<p><?php echo __d('baser', 'ユーザー管理ではログインする事ができるユーザーの管理を行う事ができます。<br>新しいユーザーを登録するには一覧左上の「新規追加」ボタンをクリックします。') ?></p>
<p><?php echo sprintf(__d('baser', 'システム管理グループのユーザーでログインしている場合、一覧の %s より簡単に他のユーザーとして再ログインする事ができます（代理ログイン）。'), '<i class="bca-btn-icon" data-bca-btn-type="switch"></i>') ?></p>
<p><?php echo __d('baser', '元のユーザーに戻るには、画面上部のユーザー名部分から「元のユーザーに戻る」をクリックします。<br><small>※ 代理ログインの対象は、システム管理グループ以外のユーザーとなります。</small>') ?></p>
