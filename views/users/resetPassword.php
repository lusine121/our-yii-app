<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\ResetPasswordForm */
/* @var $form ActiveForm */
?>
<div class="users-resetPassword">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'oldPassword')->passwordInput() ?>
    <?= $form->field($model, 'newPassword')->passwordInput() ?>
    <?= $form->field($model, 'newPasswordConfirm')->passwordInput() ?>


    <div class="form-group">
        <?= Html::submitButton('Изменить', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
