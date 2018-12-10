<?php

use yii\helpers\Html;

echo 'Привет '.Html::encode($user->username).'.';
echo Html::a('Для активации аккаунта перейдите по этой ссылке.',
    Yii::$app->urlManager->createAbsoluteUrl(
        [
            '/users/activate-account',
            'key' => $user->secret_key
        ]
    ));