<?php

use common\models\User;
use yii\helpers\Url;

/** @var $channel User */
?>
<a data-pjax="1" data-method="post" href="<?= Url::to(['channel/subscribe', 'username' => $channel->username]) ?>"
   class="btn <?= $channel->isSubscribed(Yii::$app->user->id) ?'btn-secondary' : 'btn-danger' ?>" >
    Subscribe
    <i class="fa-solid fa-bell"></i>
</a>
<?=$channel->getSubscribers()->count() ?>
