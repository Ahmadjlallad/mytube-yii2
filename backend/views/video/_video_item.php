<?php

use common\models\Video;
use yii\helpers\StringHelper;
use yii\helpers\Url;

/**
 * @var $model Video
 */
?>

<div class="media">
    <a href="<?= Url::to(['/video/update', 'video_id' => $model->video_id]) ?>">
        <div class="embed-responsive embed-responsive-16by9 mr-3" style="width: 140px">
            <video poster="<?= $model->getThumbnailLink() ?>" class="embed-responsive-item"
                   src="<?= $model->getVideoLink(); ?>"></video>
        </div>
    </a>
    <div class="media-body">
        <h6 class="mt-0"><?= $model->title ?></h6>
        <?= StringHelper::truncateWords($model->description, 10) ?>
    </div>
</div>