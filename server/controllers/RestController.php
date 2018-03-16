<?php
namespace app\controllers;

use app\models\Model;
use yii\filters\auth\CompositeAuth;
use yii\filters\auth\HttpBasicAuth;
use yii\filters\auth\HttpBearerAuth;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

class RestController extends ActiveController
{
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

        /** @todo: Call python train method */


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

        $model->trained_images++;
        $model->save();

        /** @todo: Call python test method */


        return [
            'status' => 201,
            'message' => 'Model tested',
            'result'  => 'Result...'
        ];
    }
}