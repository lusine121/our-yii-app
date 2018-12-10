<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;


/* @var $this yii\web\View */
/* @var $model app\models\Users */

$this->title = 'Регистрация пользователя';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="users-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'password')->passwordInput(['maxlength' => true]) ?>


    <div class="form-group">
        <?= Html::submitButton('Регистрация', ['class' => 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

    <?php
    if($model->scenario === 'emailActivation'):
        ?>
        <i>*На указанный E-mail будет отправлено письмо для активации аккаунта.</i>
    <?php
    endif;
    ?>

</div>
</div>
