<?php
/** @var Video $model */

use common\models\Video;
use yii\bootstrap4\Html;
use yii\widgets\Pjax;

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
                <?php Pjax::begin(); ?>
                <?= $this->render('_button', [
                    'model' => $model
                ]) ?>
                <?php Pjax::end() ?>
            </div>
        </div>
        <div>
            <p><?= Html::a($model->createdBy->username, [
                    '/channel/view', 'username' => $model->createdBy->username
                ]) ?></p>
            <p><?= Html::encode($model->description) ?></p>
        </div>
    </div>
    <div class="col-sm-4">

    </div>
</div>
