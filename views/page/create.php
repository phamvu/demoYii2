<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\PageList */

$this->title = 'Create Page List';
$this->params['breadcrumbs'][] = ['label' => 'Page Lists', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="page-list-create" id="form_create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model, 'flag' => 'create'
    ]) ?>
    <?= Html::a('Back to List', ['index'], ['class' => 'btn btn-warning']) ?> 
</div>
<?php
$link = Url::to(['api/getidbyname'], true);
$script = <<< JS
    $(document).ready(function(){
        $('#create_id').on('click',function(){
            if(!$('#create_id').hasClass('disabled')){
                var btnSend = $(this);
                btnSend.addClass('disabled');
                $.ajax({
                    url: '$link',
                    type: 'post',
                    data: $('#form_create form').serializeArray(),
                    success: function (data) {console.log(data);
                        btnSend.removeClass('disabled');
                        if(data.code == 100){
                            $('#pagelist-name').val(data.result.name);
                            $('#pagelist-page_id').val(data.result.id);
                        }
                    }
                });
            }
        });
    });
JS;
$this->registerJs($script, \yii\web\View::POS_END);
?>