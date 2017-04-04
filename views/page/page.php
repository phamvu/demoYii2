<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PageList */
/* @var $form ActiveForm */
?>
<div class="page">

    <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'page_id') ?>
        <?= $form->field($model, 'name') ?>
        <?= $form->field($model, 'data_aquired_time') ?>
    
        <div class="form-group">
            <?= Html::submitButton('Submit', ['class' => 'btn btn-primary']) ?>
        </div>
    <?php ActiveForm::end(); ?>

</div><!-- page -->
