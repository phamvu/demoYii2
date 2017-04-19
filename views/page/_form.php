<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\PageList */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="page-list-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'name')->textarea(['rows' => 6]); ?>
    
    <div class="form-group">
    <?php
        if(!empty($flag) && $flag == 'create') echo Html::Button('Get PageID', ['class' => 'btn btn-info', 'id' => 'create_id']);                
    ?>
    </div>
    
    <?= $form->field($model, 'page_id')->textInput() ?>

    <?= $form->field($model, 'data_aquired_time')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
