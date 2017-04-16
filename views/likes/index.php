<?php

use yii\helpers\Html;
use kartik\grid\GridView;
/* @var $this yii\web\View */
/* @var $searchModel app\models\LikesDetailInPostSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Likes Detail In Posts';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="likes-detail-in-post-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>
    <?php /*GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'page_id',
            'post_id',
            'individual_name:ntext',
            'individual_category:ntext',
            'individual_id',
            // 'to_name:ntext',
            // 'data_aquired_time',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); */?>
    <?=GridView::widget($dataPost)?>
</div>
