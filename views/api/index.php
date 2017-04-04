<?php
use kartik\grid\GridView;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = 'My Yii Application';
?>
<link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
<div class="site-index">

    <div class="body-content">
         <?=GridView::widget($dataPost)?>
    </div>
    <div>
        <p><?php //Html::a('Generate Site: '.$pageId,['api/create'],['class'=>'btn btn-lg btn-success'])?></p>
    </div>
</div>
