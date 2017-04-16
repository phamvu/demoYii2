<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use kartik\grid\GridView;

/* @var $this yii\web\View */
/* @var $model app\models\PageList */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => 'Page Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-list-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Back', Yii::$app->homeUrl, ['class' => 'btn']) ?>
        <?= Html::a('Update', ['update', 'id' => $model->page_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->page_id], [
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
            'name:ntext',
            'data_aquired_time',
        ],
    ]) ?>
	
    <p>
        <?= Html::a('Generate Site: '. $model->page_id, ['api/create', 'PAGE_ID'=> $model->page_id],['class'=>'btn btn-success'])?>
    </p>

	<div class="body-content">
         <?=GridView::widget($dataPost)?>
    </div>
</div>
