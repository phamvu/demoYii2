<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\PageList */

$this->title = 'Create Page List';
$this->params['breadcrumbs'][] = ['label' => 'Page Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-list-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
    <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-warning']) ?> 
</div>
