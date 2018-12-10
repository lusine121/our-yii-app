<?php
/**
 * Created by PhpStorm.
 * User: lusine
 * Date: 06.11.18
 * Time: 16:34
 */

namespace app\models;

use Yii;
use yii\base\Model;
use yii\base\InvalidParamException;



class ResetPasswordForm extends Model
{

    private $_user;

    public $oldPassword;
    public $newPassword;
    public $newPasswordConfirm;

    public $status;
    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;


    public static function tableName()
    {
        return 'users';
    }

    public function rules()
    {
        return[

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status','in','range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            [['oldPassword',  'newPassword', 'newPasswordConfirm'], 'required', 'on' => 'changePwd'],
            ['oldPassword', 'findPasswords', 'on' => 'changePwd'],
            [['newPassword', 'newPasswordConfirm'], 'string', 'min' => 3],
            [['newPassword', 'newPasswordConfirm'], 'filter', 'filter' => 'trim'],
            ['newPasswordConfirm', 'compare', 'compareAttribute' => 'newPassword', 'on'=>'changePwd', 'message' => 'Passwords do not match.'],


        ];
    }

    public function attributeLabels()
    {
        return[
            'oldPassword' => 'Старый пароль',
            'newPassword' => 'Новый пароль',
            'passwordConfirm' => 'Повторите пароль',

        ];
    }

    //matching the old password with your existing password.  hin password i validacian chi anum
    public function findPasswords($attribute, $params)
    {
        $user = Users::findOne(Yii::$app->user->id);
        if ($user->password != Yii::$app->security->generatePasswordHash($this->oldPassword))
            $this->addError($attribute, 'Current password is incorrect.');
    }


    public function _construct($key, $config = []) //полученный секретный код
    {
        if(empty($key) || !is_string($key)) //если ключ пустой

            throw new InvalidParamException('Ключ не может быть пустым.'); //вызываем исключение неверного параметра(ключа)
            $this->_user = Users::findBySecretKey($key); //находим обект пользователя по ключу

            if(!$this->_user) //если обект не найден
                throw new InvalidParamException('Не верный ключ.'); //вызываем исключение неверного параметра(ключа)

                parent::_construct($config);

    }


    public function resetPassword()
    {
        /* @var $user Users */

          $user = $this->_user;
          $user->setPassword($this->password);
          $user->removeSecretKey();
          return $user->save();
    }


}