<?php

namespace common\tests\unit\models;

use common\models\User;
use Yii;
use api\models\Login;
use api\models\Register;
use common\fixtures\UserFixture;

/**
 * Login form test
 */
class RegisterModelTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    public function testRegisterApiModel() {

        $model = new Register([
            'email' => 'test.man@example.com',
            'password' => 'jhsd7s8rhyf7isehfis',
            'phone_number' => '654564564523423424',
        ]);

        $user = $model->register();
        expect('User should be created' , $user)->isInstanceOf(User::class);


        // phone number is to short
        $failedModel = new Register([
            'email' => 'test.man@example.com',
            'password' => 'jhsd7s8rhyf7isehfis',
            'phone_number' => '65456456',
        ]);

        $user = $model->register();
        expect('User should be created' , get_class($user))->notEquals(User::class);

    }

}
