<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LikesDetailInPostSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="likes-detail-in-post-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'page_id') ?>

    <?= $form->field($model, 'post_id') ?>

    <?= $form->field($model, 'individual_name') ?>

    <?= $form->field($model, 'individual_category') ?>

    <?= $form->field($model, 'individual_id') ?>

    <?php // echo $form->field($model, 'to_name') ?>

    <?php // echo $form->field($model, 'data_aquired_time') ?>

    <div class="form-group">
        <?= Html::submitButton('Search', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('Reset', ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
