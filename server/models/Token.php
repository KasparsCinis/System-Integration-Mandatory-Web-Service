<?php

namespace app\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\filters\RateLimitInterface;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "token".
 *
 * @property int $id
 * @property string $token
 * @property int $allowance
 * @property int $allowance_updated_at
 * @property int $updated_at
 * @property int $created_at
 */
class Token extends ActiveRecord implements IdentityInterface, RateLimitInterface
{
    static $user;

    private $rateLimit = 10;

    public function getRateLimit($request, $action)
    {
        return [$this->rateLimit, 60];
    }

    public function loadAllowance($request, $action)
    {
        return [$this->allowance, $this->allowance_updated_at];
    }

    public function saveAllowance($request, $action, $allowance, $timestamp)
    {
        $this->allowance = $allowance;
        $this->allowance_updated_at = $timestamp;
        $this->save();
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        Token::$user = Token::find()
            ->where(['token' => $token])
            ->one();

        return Token::$user;
    }

    /**
     * {@inheritdoc}
     */
    public static function findIdentity($id)
    {
        return Token::find()
            ->where(['id' => $id])
            ->one();
    }

    /**
     * @return \app\models\Token
     */
    public static function findCurrentIdentity()
    {
       return Token::$user;
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->token;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($token)
    {
        return $this->token === $token;
    }

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'token';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['token'], 'required'],
            [['updated_at', 'created_at', 'allowance', 'allowance_updated_at'], 'integer'],
            [['token'], 'string', 'max' => 45],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'token' => 'Token',
            'updated_at' => 'Updated At',
            'created_at' => 'Created At',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::class
        ];
    }
}
