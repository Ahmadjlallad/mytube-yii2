<?php

use common\models\User;
use yii\web\View;
use yii\widgets\Pjax;


/* @var $this View */
/* @var $channel User */
?>

<div class="jumbotron">
    <h1 class="display-4"><?= $channel->username ?></h1>
    <hr class="my-4">
    <?php Pjax::begin() ?>
    <?= $this->render('_subscribe', ['channel' => $channel]) ?>
    <?php Pjax::end() ?>
</div>
