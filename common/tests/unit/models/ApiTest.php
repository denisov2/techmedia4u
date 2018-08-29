<?php

namespace common\tests\unit\models;

use api\models\Token;
use common\models\User;
use Yii;
use api\models\Login;
use api\models\Register;
use common\fixtures\UserFixture;
use yii\httpclient\Client;


/**
 * Login form test
 */
class ApiTest extends \Codeception\Test\Unit
{
    /**
     * @var \common\tests\UnitTester
     */
    protected $tester;


    const API_ENDPOINT = 'http://techmedia4u.local/api';



    public function testApiInfo() {

        $url = self::API_ENDPOINT;

        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('get')
            //->addHeaders([])
            ->setUrl($url)
            ->setData([])
            ->send();

        expect('Api info should not be empty' , $response->data)->notEmpty();

    }




}
