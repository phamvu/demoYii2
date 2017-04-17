<?php

namespace app\controllers;

use Yii;
use app\models\LikesDetailInPost;
use app\models\LikesDetailInPostSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use kartik\grid\GridView;
/**
 * LikesController implements the CRUD actions for LikesDetailInPost model.
 */
class LikesController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    /**
     * Lists all LikesDetailInPost models.
     * @return mixed
     */
    public function actionIndex()
    {
        $str_query = Yii::$app->request->get('LikesDetailInPostSearch')['post_id'];
        $id = explode('_', $str_query)[0];
                
        $searchModel = new LikesDetailInPostSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        $exportConfig = [GridView::CSV => ['label' => 'Save as CSV', 'filename'=> Yii::$app->params['FILENAME_LIKE'].'_'.$id.'_'.date('Ymd')]];
        $post = $searchModel->attributes();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'dataPost'=> [
                    'exportConfig'=>$exportConfig,
                    'toolbar' => ['{export}','{toggleData}'],
                    'containerOptions'=>['style'=>'overflow: autofffff'],
                    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                    'dataProvider'=> $dataProvider,
                    'filterModel' => $post,
                    'columns' => $post,
                    'responsive'=>true,
                    'hover'=>true,
                     'toolbar'=> [
                        [
                            'content'=> '',
                            'options' => ['class' => 'btn-group-sm pull-left']
                        ],
                        '{export}',
                    ],
                    'panel'=>[
                        'type'=>GridView::TYPE_PRIMARY,
                    ]
                ],
        ]);
    }

    /**
     * Displays a single LikesDetailInPost model.
     * @param string $page_id
     * @param string $post_id
     * @param string $individual_id
     * @return mixed
     */
    public function actionView($page_id, $post_id, $individual_id)
    {
        return $this->render('view', [
            'model' => $this->findModel($page_id, $post_id, $individual_id),
        ]);
    }

    protected function findModel($page_id, $post_id, $individual_id)
    {
        if (($model = LikesDetailInPost::findOne(['page_id' => $page_id, 'post_id' => $post_id, 'individual_id' => $individual_id])) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
