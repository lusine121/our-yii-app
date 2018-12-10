<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
//use yii\jui\DatePicker;

/* @var $this yii\web\View */
/* @var $model app\models\Profile */
/* @var $form ActiveForm */
?>
<div class="users-profile">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'first_name') ?>
    <?= $form->field($model, 'second_name') ?>
    <br />

    <div class="form-group">
        <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
    </div>
    <?php ActiveForm::end(); ?>

</div>
