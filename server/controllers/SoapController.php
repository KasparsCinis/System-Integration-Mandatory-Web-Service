<?php

namespace app\controllers;

use PHPUnit\Util\Log\JSON;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\JsonParser;

class SoapController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'test' => 'mongosoft\soapserver\Action',
            'train' => 'mongosoft\soapserver\Action',
        ];
    }

    /**
     * @return array
     * @soap
     */
    public function actionTrain()
    {
        return [];
    }

    /**
     * @param string $wababa
     * @return string
     * @soap
     */
    public function actionTest($wababa)
    {
        return \yii\helpers\Json::encode([]);
    }
}