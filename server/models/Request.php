<?php

namespace app\models;

use yii\base\Model;

/**
 * Class Request
 * 
 * 
 *
 * @package app\models
 */
class Request extends Model
{
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }


}