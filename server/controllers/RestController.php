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

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
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

        /*$response = $client->post(
            "{$this->pythonServer}/train", [
                RequestOptions::JSON => [
                    'model' => $id,
                    'password' => $this->pythonPassword,
                    'image' => $inputImage
                ]
            ]
        );*/

        return [
            'message' => 'Model trained'
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionTest($id)
    {
        $model = Model::findOne($id);
        $inputImage = \Yii::$app->request->post('image');

        if (!$model)
            throw new NotFoundHttpException('Model not found');
        if (!$inputImage)
            throw new NotFoundHttpException('No Image specified');

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

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionTestArray($id)
    {
        $model = Model::findOne($id);
        $images = \Yii::$app->request->post('images');

        if (!$model)
            throw new NotFoundHttpException('Model not found');
        if (!$images)
            throw new NotFoundHttpException('No Image specified');

        $resultResponses = [];

        /** Call python test method */
        $client = new Client();

        foreach ($images as $image){
            $response = $client->post(
                "{$this->pythonServer}/test", [
                    RequestOptions::JSON => [
                        'model' => $id,
                        'password' => $this->pythonPassword,
                        'image' => $image
                    ]
                ]
            );

            $resultResponses[] = json_decode($response->getBody()->getContents());
        }


        return [
            'message' => 'Model tested',
            'value'  => $resultResponses,
        ];
    }

    /**
     * @param $id
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionTrainArray($id)
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

        /*$response = $client->post(
            "{$this->pythonServer}/train", [
                RequestOptions::JSON => [
                    'model' => $id,
                    'password' => $this->pythonPassword,
                    'image' => $inputImage
                ]
            ]
        );*/

        return [
            'message' => 'Model trained'
        ];
    }
}
