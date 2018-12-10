<?php
/**
 * Created by PhpStorm.
 * User: lusine
 * Date: 07.11.18
 * Time: 12:53
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;




class RegisterForm extends Model
{
    public $username;
    public $email;
    public $password;
    public $status;
    public $password_hash;



    public function rules()
    {
        return [
            [['username', 'email', 'password'],'filter', 'filter' => 'trim'],
            [['username', 'email', 'password'],'required'],
            ['username', 'string', 'min' => 2, 'max' => 30],
            ['password', 'string', 'min' => 3, 'max' => 255],
            ['password_hash', 'unique',
                'targetClass' => Users::className(),
                'message' => 'This password already exists.'],
            ['username', 'unique',
                'targetClass' => Users::className(),
                'message' => 'This username already exists.'],

            ['email', 'email'],
            ['email', 'unique',
                'targetClass' => Users::className(),
                'message' => 'This email already exists.'],
            ['status', 'default', 'value' => Users::STATUS_ACTIVE, 'on' => 'default'],
            ['status', 'in', 'range' =>[
                Users::STATUS_NOT_ACTIVE,
                Users::STATUS_ACTIVE
            ]],
            ['status', 'default', 'value' => Users::STATUS_NOT_ACTIVE, 'on' => 'emailActivation'],

            ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Имя пользователя',
            'email' => 'Эл. почта',
            'password' => 'Пароль'
        ];
    }


    public function register()

    {

        $user = new Users();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->status = $this->status;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        if($this->scenario === 'emailActivation')
            $user->generateSecretKey();

        return $user->save() ? $user : null;
    }

    public function sendActivationEmail($user)
    {
        return Yii::$app->mailer->compose('activationEmail', ['user' => $user])
            ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (Отправлено роботом.).'])
            ->setTo($this->email)
            ->setSubject('Активация для '.Yii::$app->name)
            ->send();
    }


}