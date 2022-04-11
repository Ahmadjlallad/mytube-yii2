<?php

namespace frontend\controllers;

use common\models\Subscriber;
use common\models\User;
use Yii;
use yii\db\StaleObjectException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ChannelController extends Controller
{
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['subscribe', 'unsubscribe'],
                'rules' => [
                    ['allow' => true,
                    'roles' => ['@']]
                ]
            ],
            'verb' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'action' => [
                        'subscribe' => ['post'],
                        'unsubscribe' => ['post']
                    ]
                ]
            ]
        ]);
    }

    /**
     * @throws NotFoundHttpException
     */
    public function actionView($username)
    {
        $channel = $this->findChannel($username);
        return $this->render('view', [
            'channel' => $channel
        ]);
    }

    /**
     * @throws NotFoundHttpException
     * @throws StaleObjectException
     */
    public function actionSubscribe($username)
    {

        $userId = Yii::$app->user->id;
        $channel = $this->findChannel($username);

        $subscribe = $channel->isSubscribed($userId);
        if (!$subscribe) {
            $subscribe = new Subscriber();
            $subscribe->channel_id = $channel->id;
            $subscribe->user_id = $userId;
            $subscribe->save();
        } else {
            $subscribe->delete();
        }
        return $this->renderAjax('_subscribe', ['channel' => $channel]);
    }

    /**
     * @param $username
     * @return User
     * @throws NotFoundHttpException
     */
    public function findChannel($username)
    {
        $channel = User::findByUsername($username);
        if (!$channel) {
            throw new NotFoundHttpException('channel dose not exist');
        }
        return $channel;
    }
}