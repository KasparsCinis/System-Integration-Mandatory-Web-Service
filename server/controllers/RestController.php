<?php
namespace app\controllers;

use app\models\Model;
use GuzzleHttp\Client;
use GuzzleHttp\RequestOptions;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class RestController extends ActiveController
{
    private $pythonServer = "http://192.168.0.103:5000";
    private $pythonPassword = "3p4tow345owh34";
    public $modelClass = 'app\models\Model';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => QueryParamAuth::class,
        ];
        $behaviors['rateLimiter']['enableRateLimitHeaders'] = false;
        return $behaviors;
    }


    public function actionTrain($id)
    {
        $model = Model::findOne($id);
        $inputImage = \Yii::$app->request->post('image');

        if (!$model)
            throw new NotFoundHttpException('Model not found');
        if (!$inputImage)
            throw new NotFoundHttpException('No Image specified');

        $model->trained_images++;
        $model->save();

        /** Call python train method */
        $client = new Client();

        $response = $client->request(
            'POST',
            "{$this->pythonServer}/test",
            [
                'form_params' => [
                    'key1' => 'value1',
                    'key2' => 'value2'
                ]
            ]
        );

        return [
            'status' => 201,
            'message' => 'Model trained'
        ];
    }

    public function actionTest($id)
    {
        $model = Model::findOne($id);
        $inputImage = \Yii::$app->request->post('image');

        if (!$model)
            throw new NotFoundHttpException('Model not found');
        if (!$inputImage)
            throw new NotFoundHttpException('No Image specified');

        //$image = file_get_contents($inputImage);
//var_dump($image);
        $model->trained_images++;
        $model->save();

        /** Call python test method */
        $client = new Client();

        $response = $client->post(
            "{$this->pythonServer}/test", [
                RequestOptions::JSON => [
                    'model' => $id,
                    'password' => $this->pythonPassword,
                    'image' => $inputImage
                ]
            ]
        );

        return [
            'message' => 'Model tested',
            'value'  => json_decode($response->getBody()->getContents()),
        ];
    }
}