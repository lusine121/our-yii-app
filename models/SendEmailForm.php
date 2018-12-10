<?php
/**
 * Created by PhpStorm.
 * User: lusine
 * Date: 06.11.18
 * Time: 16:07
 */

namespace app\models;

use Yii;
use yii\base\Model;


class SendEmailForm extends Model
{
    public $email;

    public function rules()
    {
        return [
            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => Users::className(),
                'filter' => [
                    'status' => Users::STATUS_ACTIVE
                ],
                'message' => 'Данный емайл не зарегистрирован.'
            ],


        ];
    }

    public function attributeLabels()
    {
         return [
             'email' => 'Емайл'
         ];

    }

    public function sendEmail()
    {

        $user = Users::findOne([

            'status' => Users::STATUS_ACTIVE,
            'email' => $this->email
            ]);
        if($user):
        $user->generateSecretKey();
           if($user->save()):
              return Yii::$app->mailer->compose('resetPassword', ['user' => $user])
                ->setFrom([Yii::$app->params['supportEmail'] => Yii::$app->name.' (Оправлено роботом.'])
                ->setTo ($this->email)
                ->setSubject('Сброс пороля для '.Yii::$app->name)
                ->send();
           endif;
        endif;

        return false;

    }

}