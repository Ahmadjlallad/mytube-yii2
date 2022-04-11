<?php

namespace frontend\controllers;

use common\models\Video;
use common\models\VideoLike;
use common\models\VideoView;
use Yii;
use yii\data\ActiveDataProvider;
use yii\db\StaleObjectException;
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
     * @throws StaleObjectException
     */
    public function actionLike($video_id)
    {
        $user_id = Yii::$app->user->id;
        $video = $this->findVideo($video_id);
        $videoLikeDislike = VideoLike::find()->userIdVideoId($user_id, $video_id)->one();
        if (!$videoLikeDislike) {
            $this->saveLikeDislike($video->video_id, VideoLike::TYPE_LIKE, $user_id);
            // render without change the original view
        } elseif ($videoLikeDislike->type == VideoLike::TYPE_LIKE) {
            $videoLikeDislike->delete();
        } else {
            $videoLikeDislike->delete();
            $this->saveLikeDislike($video->video_id, VideoLike::TYPE_LIKE, $user_id);
        }
        return $this->renderAjax('_button', [
            'model' => $video
        ]);
    }

    /**
     * @throws StaleObjectException
     * @throws HttpException
     */
    public function actionDislike($video_id)
    {
        $user_id = Yii::$app->user->id;
        $video = $this->findVideo($video_id);
        $videoLikeDislike = VideoLike::find()->userIdVideoId($user_id, $video_id)->one();
        if (!$videoLikeDislike) {
            $this->saveLikeDislike($video->video_id, VideoLike::TYPE_DISLIKE, $user_id);
            // render without change the original view
        } elseif ($videoLikeDislike->type == VideoLike::TYPE_DISLIKE) {
            $videoLikeDislike->delete();
        } else {
            $videoLikeDislike->delete();
            $this->saveLikeDislike($video->video_id, VideoLike::TYPE_DISLIKE, $user_id);
        }
        return $this->renderAjax('_button', [
            'model' => $video
        ]);
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

    /**
     *
     * @param string $video_id
     * @param $type int
     * @param string $user_id
     */
    protected function saveLikeDislike($video_id, $type, $user_id)
    {
        $videoLikeDislike = new VideoLike();
        $videoLikeDislike->video_id = $video_id;
        $videoLikeDislike->user_id = $user_id;
        $videoLikeDislike->created_at = time();
        $videoLikeDislike->type = $type;
        $videoLikeDislike->save();
    }
}