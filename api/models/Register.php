<?php
/**
 * Created by PhpStorm.
 * User: denisov
 * Date: 29.08.2018
 * Time: 2:08
 */

namespace api\models;

use common\models\User;
use yii\base\Model;

/**
 * Register via api model
 */
class Register extends Model
{
    public $email;
    public $password;
    public $phone_number;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            [
                'email',
                'unique',
                'targetClass' => '\common\models\User',
                'message' => 'This email address has already been taken.'
            ],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['phone_number', 'required'],
            ['phone_number', 'string', 'min' => 12],
        ];
    }

    /**
     * @return User|null
     */
    public function register()
    {
        if (!$this->validate()) {
            return null;
        }



        $user = new User();
        $user->status = User::STATUS_ACTIVE;
        $user->role = User::ROLE_USER;
        $user->email = $this->email;
        $user->phone_number = $this->phone_number;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if($user->save()) {
            return $user;
        } else {
            return null;
        }

        
    }
}