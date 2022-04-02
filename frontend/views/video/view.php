<?php
/** @var Video $model */

use common\models\Video;
use yii\helpers\Url;
use yii\widgets\Pjax;
\yii\web\YiiAsset::register($this)
?>
<div class="row">
    <div class="col-sm-8">
        <div class="embed-responsive embed-responsive-16by9 mb-3">
            <video controls poster="<?= $model->getThumbnailLink() ?>" class="embed-responsive-item"
                   src="<?= $model->getVideoLink(); ?>"></video>
        </div>
        <h6><?= $model->title ?></h6>
        <div class="d-flex justify-content-between align-items-center">
            <p class="text-muted"><?= $model->getViews()->count() . ' views' . ' • ' . Yii::$app->formatter->asDate($model->created_at); ?></p>
            <div>
                <!--allow us to user ajax and don't refresh the page
                               and the return from it will replace the element
                               -->
                <?php Pjax::begin();?>
                <a
                   href="<?= Url::to(['/video/like', 'video_id' => $model->video_id]) ?>"
                   data-method="post"
                   class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-thumbs-up"></i>
                </a>
                <a data-pjax="1"
                   href="<?= Url::to(['/video/dislike', 'video_id' => $model->video_id]) ?>"
                   data-method="post"
                   class="btn btn-sm btn-outline-secondary"><i class="fas fa-thumbs-down"></i> 1</a>
                <?php Pjax::end() ?>
            </div>
        </div>
    </div>
    <div class="col-sm-4">

    </div>
</div>
