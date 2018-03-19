<?php

namespace app\controllers;

use app\models\Token;
use Yii;
use yii\base\Security;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use yii\web\User;

class SiteController extends Controller
{
     /**
     * Displays token generation page.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index', [
            'token' => null
        ]);
    }

    /**
     * Generates a new token
     * @return string
     * @throws \yii\base\Exception
     */
    public function actionGenerateToken()
    {
        $token = new Token();
        $token->token = Yii::$app->getSecurity()->generateRandomString(15);
        $token->save();

        return $this->render('index', [
            'token' => $token->token
        ]);
    }
}
