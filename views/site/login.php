<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model app\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>


<div class="users-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p class = "login-box-msg">*Пожалуйста, заполните следующие поля для входа:</p>
    <br />
    <?php if (Yii::$app->session->hasFlash('warning')): ?>
        <div class="alert alert-warning alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('warning') ?>
        </div>
    <?php endif; ?>


    <?php if (Yii::$app->session->hasFlash('error')): ?>
        <div class="alert alert-danger alert-dismissable">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">×</button>
            <?= Yii::$app->session->getFlash('error') ?>
        </div>
    <?php endif; ?>

    <?php $form = ActiveForm::begin() ?>

    <?= $form->field($model, 'username', ['options'=>[
        'tag' => 'div',
        'class' =>'form-group field-loginform-username has-feedback required'
    ],
        'template'=>'{input}<span class = "glyphicon glyphicon-user form-control-feedback"></span> 
                      {error}{hint}'
    ])->textInput(['placeholder' => 'Имя пользователя']) ?>

    <br />

    <?= $form->field($model, 'password', ['options'=>[
        'tag' => 'div',
        'class' =>'form-group field-loginform-password has-feedback required'
    ],
        'template'=>'{input}<span class = "glyphicon glyphicon-lock form-control-feedback"></span> 
                      {error}{hint}'
    ])->PasswordInput(['placeholder' => 'Пароль']) ?>


    <?= $form->field($model, 'rememberMe')->checkbox(
//            'template' => "<div class=\"col-lg-offset-1 col-lg-3\">{input} {label}</div>\n<div class=\"col-lg-8\">{error}</div>",
    ) ?>

    <div class="form-group">

        <?= Html::submitButton('Войти', ['class' => 'btn btn-success', 'name' => 'login-button']) ?>

    </div>
    <?= Html::a('Забыли пароль?', ['users/send-email']) ?>

    <!--    <a href = "/auth" class="btn btn-block btn-social btn-facebook" onclick="_gaq.push(['_trackEvent', 'btn-social', 'click', 'btn-facebook']);">-->
    <!--        <span class="fa fa-facebook"></span> Sign in with Facebook-->
    <!--    </a>-->

    <?php ActiveForm::end(); ?>

    <?php $this->endBody() ?>

</div>


