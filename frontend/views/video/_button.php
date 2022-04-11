<?php
/** @var $model Video */

use common\models\Video;
use yii\helpers\Url;

?>
<a data-pjax="1"
   href="<?= Url::to(['/video/like', 'video_id' => $model->video_id]) ?>"
   data-method="post"
   class="btn btn-sm <?= $model->isLikedBy(Yii::$app->user->id) ? 'btn-outline-primary' : 'btn-outline-secondary' ?>">
    <i class="fas fa-thumbs-up"> <?= $model->getLikes()->count() ?></i>
</a>
<a data-pjax="1"
   href="<?= Url::to(['/video/dislike', 'video_id' => $model->video_id]) ?>"
   data-method="post"
   class="btn btn-sm <?= $model->isDislikedBy(Yii::$app->user->id) ? 'btn-outline-primary' : 'btn-outline-secondary' ?>"><i
            class="fas fa-thumbs-down"><?= $model->getDislikes()->count() ?></i></a>
