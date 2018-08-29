<?php

namespace api\controllers;

use api\models\Login;
use api\models\Register;
use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;


class UserController extends ActiveController
{
    public $modelClass = User::class;


    public function behaviors()
    {
        /*
        $behaviors = parent::behaviors();
        $behaviors['authenticator']['except'] = ['login', 'register'];
        $behaviors['authenticator']['authMethods'] = [
            HttpBearerAuth::className(),
        ];
        return $behaviors;
        */

        return array_merge(parent::behaviors(), [
            'bearerAuth' => [
                'class' => HttpBearerAuth::className(),
                'except' => ['login', 'register'],
            ]
        ]);
    }

    public function verbs()
    {
        $parent = parent::verbs();
        return array_merge([
            'register' => ['POST'],
            'login' => ['POST'],
            'me' => ['GET'],
        ], $parent);
    }

    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    public function actionRegister()
    {
        $model = new Register();
        if ($model->load(Yii::$app->request->bodyParams, '')) {
            if ($model->validate()) {
                if ($user = $model->register()) {
                    return $user;
                }
            }
            return $model;
        }
        return ['result' => 'fail', 'error_message' => 'No registration data', 'model' => $model];
    }

    public function actionLogin()
    {
        $model = new Login();
        $model->load(Yii::$app->request->bodyParams, '');
        if ($token = $model->auth()) {
            return $token;
        } else {
            return $model;
        }
    }

    /**
     * Method for testing identity
     * @return null|\yii\web\IdentityInterface
     */
    public function actionMe()
    {
        return \Yii::$app->user->identity;
    }


    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete'])) {

            $user = Yii::$app->user->getIdentity();
            /* @var $user \common\models\User */
            if ($user->role != User::ROLE_ADMIN) {
                throw new \yii\web\ForbiddenHttpException(sprintf('Action %s aviable only for admins', $action));
            }



        }

        // throw new \yii\web\ForbiddenHttpException(sprintf('You can only %s articles that you\'ve created.', $action));
    }
}
