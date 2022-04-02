<?php

use common\models\Video;
use yii\grid\ActionColumn;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Videos';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="video-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create Video', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="table-responsive">
        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],

                [
                    'attribute' => 'title',
                    'content' => function (Video $model) {
                        return $this->render('_video_item', [
                            'model' => $model
                        ]);
                    }
                ],
//            formatting options
//            'description:ntext',
                'tags' => [
                    'attribute' => 'status',
                    'content' => function (Video $model) {
                        return $model->getStatusLabels()[$model->status];
                    }
                ],
                'created_at:datetime',
                'updated_at:datetime',
                //'status',
                //'has_thumbnail',
                //'video_name',
                //'created_at',
                [
                    'class' => ActionColumn::class,
                    'template' => '{delete}',
                    'urlCreator' => function ($action, Video $model, $key, $index, $column) {
                        return Url::toRoute([$action, 'video_id' => $model->video_id]);
                    }
                ],
            ],
        ]); ?>

    </div>
</div>
