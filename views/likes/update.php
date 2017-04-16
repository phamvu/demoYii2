<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\LikesDetailInPost */

$this->title = 'Update Likes Detail In Post: ' . $model->page_id;
$this->params['breadcrumbs'][] = ['label' => 'Likes Detail In Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->page_id, 'url' => ['view', 'page_id' => $model->page_id, 'post_id' => $model->post_id, 'individual_id' => $model->individual_id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="likes-detail-in-post-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
