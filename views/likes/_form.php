<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\models\LikesDetailInPost */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="likes-detail-in-post-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'page_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'post_id')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'individual_name')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'individual_category')->textarea(['rows' => 6]) ?>

    <?= $form->field($model, 'individual_id')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? 'Create' : 'Update', ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
