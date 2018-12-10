<?php
/**
 * Created by PhpStorm.
 * User: 123456
 * Date: 27.11.2018
 * Time: 21:44
 */

namespace app\models;

use yii\base\Model;
use Yii;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $status;

    private $_user = false;

    public function rules()
    {
        return[
            [['username', 'password'], 'required', 'on' => 'default'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],

        ];
    }

    public function validatePassword($attribute)
    {
        if(!$this->hasErrors()):
            $user = $this->getUser();
            if(!$user || !$user->validatePassword($this->password)):
                $this->addError($attribute, 'Неправильное имя пользователя или пароль.');
            endif;
        endif;
    }


    public function getUser()
    {
        if($this->_user === false):
            $this->_user = Users::findByUsername($this->username);
        endif;

        return $this->_user;
    }


    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'password' => 'Пароль',
            'rememberMe' => 'Запомнить',
        ];

    }


    public function login()
    {
        if($this->validate()):
            $this->status = ($user = $this->getUser()) ? $user->status : Users::STATUS_NOT_ACTIVE;
            if($this->status === Users::STATUS_ACTIVE):
                return Yii::$app->user->login($user, $this->rememberMe ? 3600*24*30:0);
            else:
                return false;
            endif;
        else:
            return false;
        endif;
    }


}