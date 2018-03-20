<?php

namespace app\controllers;

use app\models\Model;
use app\models\Token;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use PHPUnit\Util\Log\JSON;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\JsonParser;

class SoapController extends Controller
{
    private $pythonServer = "http://192.168.0.103:5000";
    private $pythonPassword = "3p4tow345owh34";

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'wsdl'          => 'mongosoft\soapserver\Action',
            'createModel'   => 'mongosoft\soapserver\Action',
            'deleteModel'   => 'mongosoft\soapserver\Action',
            'test'          => 'mongosoft\soapserver\Action',
            'train'         => 'mongosoft\soapserver\Action',
            'trainArray'    => 'mongosoft\soapserver\Action',
            'testArray'     => 'mongosoft\soapserver\Action',
        ];
    }

    /**
     * @return bool
     */
    public function actionWsdl()
    {
        return true;
    }

    /**
     * @param string $token
     * @return int
     * @throws ForbiddenHttpException
     * @soap
     */
    public function actionCreateModel($token)
    {
        if (!Token::findIdentityByAccessToken($token))
            throw new ForbiddenHttpException('Invalid token');

        $model = new Model();
        $model->token = Token::findCurrentIdentity()->id;
        $model->save();

        return $model->id;
    }

    /**
     * @param string $token
     * @param int $modelId
     * @return bool
     * @throws ForbiddenHttpException
     * @throws \Exception
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     * @soap
     */
    public function actionDeleteModel($token, $modelId)
    {
        if (!Token::findIdentityByAccessToken($token))
            throw new ForbiddenHttpException('Invalid token');

        $model = Model::findOne($modelId);

        if (!$model || $model->token != Token::findCurrentIdentity()->id)
            throw new ForbiddenHttpException('Not your model');

        $model->delete();

        return true;
    }

    /**
     * @param string $token
     * @param int $modelId
     * @param string $image
     * @return string
     * @throws ForbiddenHttpException
     * @soap
     */
    public function actionTrain($token, $modelId, $image)
    {
        if (!Token::findIdentityByAccessToken($token))
            throw new ForbiddenHttpException('Invalid token');

        $model = Model::findOne($modelId);

        if (!$model)
            throw new ForbiddenHttpException('Invalid model specified');

        $model->trained_images++;
        $model->save();

        /** Call python train method */
        $client = new Client();

        /*$response = $client->post(
            "{$this->pythonServer}/train", [
                RequestOptions::JSON => [
                    'model' => $modelId,
                    'password' => $this->pythonPassword,
                    'image' => $image
                ]
            ]
        );*/

        return \yii\helpers\Json::encode([
            'message' => 'Model trained'
        ]);
    }

    /**
     * @param string $token
     * @param int $modelId
     * @param string[] $images
     * @return string
     * @throws ForbiddenHttpException
     * @soap
     */
    public function actionTrainArray($token, $modelId, $images)
    {
        if (!Token::findIdentityByAccessToken($token))
            throw new ForbiddenHttpException('Invalid token');

        $model = Model::findOne($modelId);

        if (!$model)
            throw new ForbiddenHttpException('Invalid model specified');

        $model->trained_images++;
        $model->save();

        /** Call python train method */
        $client = new Client();

        /*$response = $client->post(
            "{$this->pythonServer}/train", [
                RequestOptions::JSON => [
                    'model' => $modelId,
                    'password' => $this->pythonPassword,
                    'image' => $image
                ]
            ]
        );*/

        return \yii\helpers\Json::encode([
            'message' => 'Model trained'
        ]);
    }

    /**
     * @param string $token
     * @param int $modelId
     * @param string $image
     * @return string
     * @throws ForbiddenHttpException
     * @soap
     */
    public function actionTest($token, $modelId, $image)
    {
        if (!Token::findIdentityByAccessToken($token))
            throw new ForbiddenHttpException('Invalid token');

        $model = Model::findOne($modelId);

        if (!$model)
            throw new ForbiddenHttpException('Invalid model specified');

        /** Call python test method */
        $client = new Client();

        $response = $client->post(
            "{$this->pythonServer}/test", [
                RequestOptions::JSON => [
                    'model' => $modelId,
                    'password' => $this->pythonPassword,
                    'image' => $image
                ]
            ]
        );

        return \yii\helpers\Json::encode([
            'message' => 'Model tested',
            'value'  => json_decode($response->getBody()->getContents()),
        ]);
    }

    /**
     * @param string $token
     * @param int $modelId
     * @param string[] $images
     * @return string
     * @throws ForbiddenHttpException
     * @soap
     */
    public function actionTestArray($token, $modelId, $images)
    {
        if (!Token::findIdentityByAccessToken($token))
            throw new ForbiddenHttpException('Invalid token');

        $model = Model::findOne($modelId);

        if (!$model)
            throw new ForbiddenHttpException('Invalid model specified');

        $resultResponses = [];

        /** Call python test method */
        $client = new Client();

        foreach ($images as $image){
            $response = $client->post(
                "{$this->pythonServer}/test", [
                    RequestOptions::JSON => [
                        'model' => $modelId,
                        'password' => $this->pythonPassword,
                        'image' => $image
                    ]
                ]
            );

            $resultResponses[] = $response->getBody()->getContents();
        }


        return \yii\helpers\Json::encode([
            'message' => 'Model tested',
            'value'  => $resultResponses,
        ]);
    }
}