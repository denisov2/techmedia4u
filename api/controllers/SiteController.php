<?php
/**
 * Created by PhpStorm.
 * User: denisov
 * Date: 29.08.2018
 * Time: 0:59
 */

namespace api\controllers;

use Yii;

use yii\rest\Controller;


class SiteController extends Controller
{
    public function actionIndex()
    {
        return [
            'Aplication' => Yii::$app->name,
            'Api version' => '1.0',
            'Company' => 'DEMO TechMedia4U быстрорастущее креативное digital агентство, которое специализируется на '
                .'предоставлении комплекса услуг по созданию бренда и позиционировании компании онлайн, предоставляя '
                .'конкурентные преимущества в сегодняшней жесткой среде бизнеса.',
        ];
    }
}