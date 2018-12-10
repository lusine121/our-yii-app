<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\web\IdentityInterface;
use yii\helpers\Security;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

//use yii\db\ActiveRecord;

/**
 * This is the model class for table "users".
 *
 * @property int $id
 * @property string $email
 * @property string $username
 * @property string $password_hash
 * @property string $authKey
 * @property string $secret_key
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property Profile $profile

 */

class Users extends ActiveRecord implements IdentityInterface
{

    const STATUS_DELETED = 0;
    const STATUS_NOT_ACTIVE = 1;
    const STATUS_ACTIVE = 10;

    public $password;

    public static function tableName()
    {
        return 'users';
    }


    public function rules()
    {
        return [
            [['username', 'email', 'status'], 'required'],
            [['username', 'email', 'password'], 'filter', 'filter' => 'trim'],
            ['email', 'email'],
            ['username', 'string', 'min' => 2, 'message' => 'Имя пользователя должен иметь минимум 2 символа.'],
            [['username'], 'string', 'max' => 20],
            ['username', 'unique', 'message' => 'Это имя уже занято.'],
            [['email', 'password_hash', 'secret_key'], 'string', 'max' => 255],
            ['secret_key', 'unique'],
            ['email', 'unique', 'message' => 'Эта почта уже зарегистрирована.'],
            [['auth_key'], 'string', 'max' => 50],
            ['password', 'required', 'on' => 'create'],

            // ['status', 'default', 'value' => self::STATUS_ACTIVE],
            // ['status','in','range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

//

        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => 'Ник',
            'email' => 'Email',
            'password' => 'Password Hash',
            'auth_key' => 'Auth Key',
            'secret_key' => 'Secret Key',
            'status' => 'Статус',
            'created_at' => 'Дата создания',
            'updated_at' => 'Дата изменения',
        ];
    }



    /*Связи */
    public function getProfile(){

        return $this->hasOne(Profile::className(), ['user_id' => 'id']);

    }

    public function getUser()

    {

        if ($this->username === false) {

            $this->username = Users::findByUsername($this->username);

        }


        return $this->username;

    }



    /* Поведения */
    public function behaviors()
    {
        return [
//            TimestampBehavior::className()
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => new Expression('NOW()'),
            ],
        ];
    }


    /* Поиск */

    /** Находит пользователя по имени и возвращает объект найденного пользователя.
     *  Вызываеться из модели LoginForm.
     */
    public static function findByUsername($username)
    {
        return static::findOne([
            'username' => $username
        ]);
    }


    public static function findByEmail($email)
    {
        return static::findOne([
            'email' => $email
        ]);
    }



    public static function isSecretKeyExpire($key){

        if(empty($key))
            return false;
        $expire = Yii::$app->params['secretKeyExpire'];
        $parts = explode('_', $key);
        $timestamp = (int) end($parts);
        return $timestamp + $expire >= time();

    }

    public static function findBySecretKey($key)
    {
        if(!static::isSecretKeyExpire($key))
            return null;
        return static::findOne([
            'secret_key' => $key
        ]);
    }


    public function generateSecretKey()
    {
        $this->secret_key = Yii::$app->security->generateRandomString(). '_'. time();
    }


    public function removeSecretKey()
    {
        $this->secret_key = null;
    }


    /* helpers */

    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }


    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }


    public static function hashPassword($password)
    {// Function to create password hash
        return  Yii::$app->security->generatePasswordHash ($password);
    }


    public function generateAuthKey()
    {
        $this -> auth_key = Yii::$app->security->generateRandomString();
    }


    //authKey i heshavorum
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($this->isNewRecord) {
                $this -> auth_key = \Yii::$app->security->generateRandomString();
            }
            return true;
        }
        return false;
    }


    /* Аутентификация пользователя */
    public static function findIdentity($id)
    {
        return static::findOne([
        'id' => $id,
        'status' => self::STATUS_ACTIVE
        ]);
    }


    public static function findIdentityByAccessToken($token, $type = null)
    {

        return static::findOne(['access_token' => $token]);
    }


    public function getId()
    {
        return $this -> id;
    }


    public function getAuthKey()
    {
        return $this -> auth_key;
    }


    public function validateAuthKey($authKey)
    {
        return $this -> auth_key===$authKey;
    }


}
