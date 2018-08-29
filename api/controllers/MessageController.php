<?php

namespace api\controllers;

use api\models\Login;
use api\models\Register;
use common\models\Message;
use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class MessageController extends ActiveController
{
    public $modelClass = Message::class;


    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    /**
     * @return array
     */
    public function verbs()
    {
        $parent = parent::verbs();
        return array_merge([
            'send_message' => ['POST'],
            'send' => ['POST'],
            'get_messages' => ['GET'],
            'get' => ['GET'],
        ], $parent);
    }

    /**
     * @return array|Message
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     */
    public function actionSend()
    {
        $user = Yii::$app->user->getIdentity();
        /* @var $user User */
        $data = Yii::$app->getRequest()->getBodyParams();

        if (empty($data['email']) || empty($data['title']) || empty($data['text'])) {
            return [
                'success' => false,
                'error' => 'Email, title and text required'
            ];
        }
        $email = $data['email'];

        $receiver = User::findByEmail($email);
        if (empty($receiver)) {
            return [
                'success' => false,
                'error' => 'User for receiving message not found with email ' . $email,
            ];
        }

        $model = new Message();
        $model->sender_id = $user->id;
        $model->receiver_id = $receiver->id;
        $model->status = Message::STATUS_SENT;
        $model->title = $data['title'];
        $model->text = $data['text'];


        if ($model->save()) {
            return $model;
        } else {
            return [
                'success' => false,
                'errors' => $model->errors
            ];
        }
    }

    /**
     * @param $type
     * @param null $status
     * @return array|\yii\db\ActiveRecord[]
     * @throws Exception
     * @throws \Exception
     * @throws \Throwable
     */
    public function actionGet($type, $status = null)
    {

        $user = Yii::$app->user->getIdentity();
        $messages = [];

        if ($type == Message::TYPE_SEND) {
            //getting messages send by user
            $messagesQuery = Message::find()
                ->where(['sender_id' => $user->getId()])
                ->andFilterWhere(['status' => $status]);
            $messages = $messagesQuery->all();
        } elseif ($type == Message::TYPE_RECEIVED) {
            //getting messages received by user
            $messagesQuery = Message::find()
                ->where(['receiver_id' => $user->getId()])
                ->andFilterWhere(['status' => $status]);


            $messages = $messagesQuery->all();
        } else {
            throw new Exception("Wrong messageType $type");
        }

        foreach ($messages as $message) {
            /* @var $message Message */
            if($message->status == Message::STATUS_SENT) {
                $message->status = Message::STATUS_DELIVERED;
                $message->save();
            }
        }

        return $messages;


    }

    /**
     * @param $id
     * @return null|static
     * @throws NotFoundHttpException
     * @throws \yii\web\ForbiddenHttpException
     */
    public function actionView($id)
    {
        $model = Message::findOne($id);
        if (empty($model)) {
            throw new  NotFoundHttpException('Message not found with id ' . $id);
        }
        $this->checkAccess('view', $model);

        if ($model->status != Message::STATUS_VIEWED) {
            $model->status = Message::STATUS_VIEWED;
            $model->save();
        }
        return $model;
    }

    public function actions()
    {
        $actions = parent::actions();

        unset($actions['create']);
        unset($actions['update']);
        unset($actions['index']);
        unset($actions['view']);
        return $actions;
    }


    public function checkAccess($action, $model = null, $params = [])
    {
        if ($action == 'delete') {
            /* @var $model Message */
            if (Yii::$app->getUser()->getId() != $model->sender_id) {
                throw new \yii\web\ForbiddenHttpException(sprintf('You can only delete messages that you\'ve created'));
            }
        }

        if ($action == 'view') {
            /* @var $model Message */
            if (Yii::$app->getUser()->getId() != $model->sender_id && Yii::$app->getUser()->getId() != $model->receiver_id) {
                throw new \yii\web\ForbiddenHttpException(sprintf('You can only view messages that you\'ve created or sent for you'));
            }
        }
    }
}
