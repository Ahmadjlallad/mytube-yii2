<?php

use common\models\Video;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * @var $model Video
 */
?>
<div class="card m-3" style="width: 13rem;">
    <a href="<?= Url::to(['/video/view', 'video_id' => $model->video_id]) ?>">
        <div class="embed-responsive embed-responsive-16by9 mb-3">
            <video poster="<?= $model->getThumbnailLink() ?>" class="embed-responsive-item"
                   src="<?= $model->getVideoLink(); ?>"></video>
        </div>
    </a>
    <div class="card-body p-1">
        <h6 class="card-title"><?= StringHelper::truncateWords($model->title, 5) ?></h6>
        <p class="text-muted card-text m-0"><?= $model->createdBy->username ?></p>
        <p class="text-muted card-text m-0"><?= $model->getViews()->count() .
            Yii::$app->formatter->asRelativeTime($model->created_at) ?></p>
    </div>
</div>
