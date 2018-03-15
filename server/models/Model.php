<?php

namespace app\models;

use Yii;
use yii\filters\RateLimitInterface;

/**
 * This is the model class for table "model".
 *
 * @property int $id
 * @property int $trained_images
 * @property int $created_at
 * @property int $updated_at
 */
class Model extends \yii\db\ActiveRecord implements RateLimitInterface
{
    public function getRateLimit($request, $action)
    {
        return [$this->rateLimit, 1]; // $rateLimit requests per second
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
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'model';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['trained_images', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'trained_images' => 'Trained Images',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
