<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\LikesDetailInPost */

$this->title = $model->page_id;
$this->params['breadcrumbs'][] = ['label' => 'Likes Detail In Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="likes-detail-in-post-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'page_id' => $model->page_id, 'post_id' => $model->post_id, 'individual_id' => $model->individual_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'page_id' => $model->page_id, 'post_id' => $model->post_id, 'individual_id' => $model->individual_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'page_id',
            'post_id',
            'individual_name:ntext',
            'individual_category:ntext',
            'individual_id',
            'to_name:ntext',
            'data_aquired_time',
        ],
    ]) ?>

</div>
