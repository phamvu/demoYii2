<?php

namespace app\controllers;

use Yii;
use app\models\PageList;
use app\models\LikesDetailInPost;
use app\models\PageListSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use app\models;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
/**
 * PageController implements the CRUD actions for PageList model.
 */
class PageController extends Controller
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
     * Lists all PageList models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PageListSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PageList model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        $post = new models\PostFromFeed();
        $page = new models\PageList();
        $query = $post::find()->where(['page_id'=>$id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10,
            ],
            'sort' => [
                'defaultOrder' => [
                    'page_id' => SORT_ASC,
                    'name' => SORT_DESC, 
                ]
            ],
        ]);
        $exportConfig = [GridView::CSV => ['label' => 'Save as CSV']];
        return $this->render('view',
            [
                'dataPost'=> [
                    'exportConfig'=>$exportConfig,
                    'toolbar' => ['{export}','{toggleData}'],
                    'containerOptions'=>['style'=>'overflow: auto'],
                    'headerRowOptions'=>['class'=>'kartik-sheet-style'],
                    'filterRowOptions'=>['class'=>'kartik-sheet-style'],
                    'dataProvider'=> $dataProvider,
                    'filterModel' => $post,
                    'columns' => $post->attributes(),
                    'responsive'=>true,
                    'hover'=>true,
                     'toolbar'=> [
                        ['content'=>
                           ''
                        ],
                        '{export}',
                    ],
                    'panel'=>[
                        'type'=>GridView::TYPE_PRIMARY,
                    ]
                ],
                'model' => $this->findModel($id),
            ]
        );
    }

    /**
     * Creates a new PageList model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PageList();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->page_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PageList model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->page_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing PageList model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the PageList model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PageList the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PageList::findOne($id)) !== null) {
            LikesDetailInPost::deleteAll(['page_id'=> $id]);
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
