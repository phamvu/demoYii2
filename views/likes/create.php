<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\LikesDetailInPost */

$this->title = 'Create Likes Detail In Post';
$this->params['breadcrumbs'][] = ['label' => 'Likes Detail In Posts', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="likes-detail-in-post-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
