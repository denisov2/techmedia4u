<?php



namespace api\controllers;

use api\models\Login;
use api\models\Register;
use Yii;
use common\models\User;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\ActiveController;


/**
 * User registration and login via API
 * Class UserController
 * @package api\controllers
 */
class UserController extends ActiveController
{
    public $modelClass = User::class;

    /**
     * @return array
     */
    public function behaviors()
    {
        return array_merge(parent::behaviors(), [
            'bearerAuth' => [
                'class' => HttpBearerAuth::className(),
                'except' => ['login', 'register'],
            ]
        ]);
    }

    /**
     * @return array
     */
    public function verbs()
    {
        $parent = parent::verbs();
        return array_merge([
            'register' => ['POST'],
            'login' => ['POST'],
            'me' => ['GET'],
        ], $parent);
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        unset($actions['create']);
        return $actions;
    }

    /**
     * @return Register|array|User|null
     */
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

    /**
     * @return Login|\api\models\Token|null
     */
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

    /**
     * @param string $action
     * @param null $model
     * @param array $params
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\web\ForbiddenHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        if (in_array($action, ['update', 'delete'])) {

            $user = Yii::$app->user->getIdentity();
            /* @var $user \common\models\User */
            if ($user->role != User::ROLE_ADMIN) {
                throw new \yii\web\ForbiddenHttpException(sprintf('Action %s aviable only for admins', $action));
            }
        }
    }
}
