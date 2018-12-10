<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "profile".
 *
 * @property int $user_id
 * @property string $avatar
 * @property string $first_name
 * @property string $last_name
 * @property string $birthday
 * @property int $gender
 *
 * @property Users $user
 */
class Profile extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */

    public $first_name;
    public $last_name;
    public $birthday;
    public $gender;

    public static function tableName()
    {
        return 'profile';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
//            ['birthday', 'date', 'format' => 'yyyy-M-dd'],
            [['gender'], 'integer'],
            [['avatar'], 'string', 'max' => 255],
            [['first_name', 'second_name'], 'string', 'max' => 32],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => Users::className(), 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'user_id' => 'User ID',
            'avatar' => 'Аватар',
            'first_name' => 'Имя',
            'second_name' => 'Фамилия',
            'birthday' => 'Дата рождения',
            'gender' => 'Пол',
        ];
    }


//    public function getGenderLabel()
//
//    {
//        return $this->gender == 1 ? 'male' : 'female';
//    }


    /**
     * @return \yii\db\ActiveQuery
     */

    public function getUser()
    {
        return $this->hasOne(Users::className(), ['id' => 'user_id']);
    }


    public function updateProfile()
    {

        $profile = ($profile = Profile::findOne(Yii::$app->user->id)) ? $profile : new Profile();
        $profile->user_id = Yii::$app->user->id;
        $profile->first_name = $this->first_name;
        $profile->second_name = $this->second_name;
//        $profile->birthday = $this->birthday;
//        $profile->gender = $this->gender;

        if($profile->save()):
            $user = $this->user ? $this->user : Users::findOne(Yii::$app->user->id);
            $username = Yii::$app->request->post('Users')['username'];
            $user->username = isset($username) ? $username : $user->username;
            return $user->save() ? true : false;
        endif;
        return false;

    }

}
