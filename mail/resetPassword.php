<?php

use yii\helpers\Html;

echo 'Привет '.Html::encode($user->username).'_ ';
echo Html::a('Для смены пароля перейдите по этой ссылке.',
    Yii::$app->urlManager->createAbsoluteUrl(
        [

        '/users/reset-password',
        'key' => $user->secret_key

        ]
    ));





