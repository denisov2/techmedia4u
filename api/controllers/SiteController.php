<?php

namespace api\controllers;

use Yii;

use yii\rest\Controller;


class SiteController extends Controller
{
    /**
     * General info about API
     * @return array
     */
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