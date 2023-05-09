                                          <?php echo date('Y-m-d H:i:s') ?>
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━
　　　　　　◆◇　<?php echo __d('baser', 'パスワードの再設定')?>　◇◆
━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━

このアカウントのユーザー名は: <?php echo $user['InstantPageUser']['name']; ?>

続行する場合は、このリンクをたどってパスワードを再設定できます:

<?php echo Router::url(array('controller' => 'InstantPageUsers', 'action' => 'verify', $token), true); ?>

このアクションを開始しなかった場合は、サポートに連絡してください。

