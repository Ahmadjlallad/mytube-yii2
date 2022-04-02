<?php

namespace frontend\controllers;

use common\models\Video;
use common\models\VideoLike;
use common\models\VideoView;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\HttpException;

class VideoController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['like', 'dislike'],
                'rules' => [
                    ['allow' => true, 'roles' => ['@']
                    ]
                ]
            ],
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'like' => ['post'],
                    'dislike' => ['post'],
                ]
            ]

        ]);
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider(
            [
                'query' => Video::find()
                    ->published(),
                'sort' => ['defaultOrder' => ['created_at' => SORT_DESC]],
            ]
        );
        return $this->render('index', ['dataProvider' => $dataProvider]);
    }

    /**
     * @return string
     * @throws HttpException
     */
    public function actionView($video_id)
    {
        $this->layout = 'auth';
        $video = $this->findVideo($video_id);
        $videoView = new VideoView();
        $videoView->video_id = $video_id;
        $videoView->user_id = Yii::$app->user->id;
        $videoView->created_at = time();
        $videoView->save();
        return $this->render('view', [
            'model' => $video,
        ]);
    }

    /**
     * @throws HttpException
     */
    public function actionLike($video_id)
    {
        $video = $this->findVideo($video_id);
        $videoLike = new VideoLike();
        $videoLike->video_id = $video->video_id;
        $videoLike->user_id = Yii::$app->user->id;
        $videoLike->created_at = time();
        $videoLike->save();
    }

    /**
     * @throws HttpException
     */
    protected function findVideo($video_id)
    {
        $video = Video::findOne($video_id);
        if (!$video) {
            throw new HttpException("video dose not exit");
        }
        return $video;
    }
}