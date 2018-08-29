<?php

namespace api\models;

use common\models\User;
use yii\base\Model;
/**
 * Model for login via API
 */
class Login extends Model
{
    /**
     * @var
     */
    public $email;

    /**
     * @var
     */
    public $password;

    /**
     * @var
     */
    private $_user;
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // username, password, phone_number are required
            [['email', 'password'], 'required'],
            [['email'], 'email'],
            
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }
    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }
    /**
     * @return Token|null
     */
    public function auth()
    {
        if ($this->validate()) {

            $user = User::findByEmail($this->email);
            $token = new Token();
            $token->user_id = $user->id;
            $token->token = $user->getJWT();
            $date = new \DateTime();
            $date->modify('+10 min');
            $token->expired_at = $date->getTimestamp();
            return $token->save() ? $token : null;
        } else {
            return null;
        }
    }
    /**
     * Finds user by [[username]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }
        return $this->_user;
    }
}