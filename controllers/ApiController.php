<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Facebook;
use Facebook\FacebookRequest;
use app\models;//\LikesDetailInPost;
//use app\models\PostFromFeed;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;

class ApiController extends Controller
{
	public $defaultAction = 'index';
	private $pageId;
	private $token;
	

	public function __construct($id, $module, $config = [])
	{
		$this->pageId = Yii::$app->params['APP_ID'];
		if(!empty($_SESSION['fb_access_token']))
			$this->token = $_SESSION['fb_access_token'];
		parent::__construct($id, $module, $config);
	}
    
	public function actionIndex(){
		$post = new models\PostFromFeed();
		$query = $post::find();

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
		return $this->render('index',
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
				            Html::a('<i class="glyphicon glyphicon-repeat"></i>', ['grid-demo'], ['data-pjax'=>0, 'class'=>'btn btn-default', 'title'=>'Reset Grid'])
				        ],
				        '{export}',
				    ],
				    'panel'=>[
				        'type'=>GridView::TYPE_PRIMARY,
				    ]
				], 
		    	'pageId'=> $this->pageId
		    ]
		);
	}
    public function actionCreate()
    {
    	$PAGE_ID =$this->pageId;
		$fb = new Facebook\Facebook([
		   	'app_id' => $this->pageId,
		  	'app_secret' => Yii::$app->params['APP_SECRET'],
		  	'default_graph_version' => Yii::$app->params['APP_VERSION'],
		]);
		$fbApp = $fb->getApp();
		
		$serializedFacebookApp = serialize($fbApp);
		$unserializedFacebookApp = unserialize($serializedFacebookApp);
		
		if(empty($_SESSION['fb_access_token']))
			$this->token = $_SESSION['fb_access_token'] = $unserializedFacebookApp->getAccessToken();


		$paramArrPage = http_build_query(
			array(
				'fields' => 'category,category_list'
			)
		);
		$paramArrFeed = http_build_query(
			array(
				'limit'=>Yii::$app->params['APP_LIMIT'],
				'fields' => 'name,admin_creator,coordinates,created_time,description,likes.summary(true),comments.summary(true),from,to,message,message_tags,with_tags,story_tags',
			)
		);
		$requests = [
			$fb->request('GET', '/'.$PAGE_ID .'?'. $paramArrPage),
		  	$fb->request('GET', '/'.$PAGE_ID .'/feed?'.$paramArrFeed),
		];

		try {
		  	$feeds = $fb->sendBatchRequest($requests, $this->token);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
		 	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}
		
		$pageInfo = $feeds[0]->getDecodedBody();
		$response = $feeds[1];
		if ($response->isError()) {
	    	$error = $response->getThrownException();
	    	echo ' error: ' . $error->getMessage(); die;
	  	} else {
	    	$data = $response->getDecodedBody();
	    	$this->addData($data['data'], $pageInfo);
	  	}
	  	return $this->redirect('index.php/index');
	}
    
    private function addData($datas, $pageInfo = []){
    	$connection = \Yii::$app->db;
    	$transaction = $connection->beginTransaction();
		try {
			$post = new models\PostFromFeed();
			$likes = new models\LikesDetailInPost();
			$rowDatas = array();
			$requests = array();
			foreach($datas as $row){
				$rowDatas[] = [
					'page_id' => $this->pageId,
			    	'post_id' => $row['id'],
			    	'from_name' => $row['from']['name'],
			    	'from_category' => $pageInfo['category'], //get api page category
			    	'from_id' => $row['from']['id'],
			    	'page_owner' => ($row['from']['id'] == $this->pageId)?1:0,
			    	'to_id' => @$row['to']['data'][0]['id'],

			    	'to_category' => 'get api AAAA', //get api page category
			    	
			    	'to_name' => @$row['to']['data'][0]['name'],
			    	'message' => @$row['message'],
			    	'message_tags' => (!empty($row['message_tags']))?1:0,
			    	'picture' => @$row['picture'],
			    	'link' => @$row['link'],
			    	'name' => @$row['name'],
			    	'caption' => @$row['caption'],
			    	'description' => @$row['description'],
			    	'source' => @$row['source'],
			    	'properties' => @$row['description'],
			    	'icon' => @$row['icon'],
			    	'actions_name_comment' => @$row['actions'][0]['name'],
			    	'actions_link_comment' => @$row['actions'][0]['link'],	
			    	
			    	'actions_link_like' => @$row['actions'][0]['link'],	
			    	'actions_name_like' => @$row['actions'][0]['name'],	

			    	'privacy_description' => @$row['privacy']['description'],
					'privacy_value' => @$row['privacy']['value'],
					'type' => @$row['type'],
					'likes' => @$row['likes']['summary']['total_count'],
			    	'place' => @$row['place'],
			    	'story' => @$row['story'],
			    	'story_tags' => (!empty($row['story_tags']))?1:0,
					'with_tags' => (!empty($row['with_tags']))?1:0,
			    	'comments' => @$row['comments']['summary']['total_count'],
			    	'object_id' => @$row['object_id'],

			    	'application_name' => @$row['object_id'],
			    	'application_id' => @$row['object_id'],

			    	'created_time' => @$row['created_time'],
			    	'updated_time' => @$row['updated_time'],
			    	'data_aquired_time' => date('Y-m-d H:i:s'),
				];
				$requests[] = $fb->request('GET', '/'. $row['id'] .'/likes?'. http_build_query(array('limit'=>@$row['likes']['summary']['total_count'])));
	    	}
	    	Yii::$app->db->createCommand()->batchInsert(models\PostFromFeed::tableName(), $post->attributes(), $rowDatas)->execute();
	    	
	    	if(!empty($requests)){
		    	$feeds = $fb->sendBatchRequest($requests, $this->token);
		    	foreach($feeds as $row){
		    		if(!$row->isError()) {
				    	$data = $row->getDecodedBody();
					  	// $rowDatasLike[] = [
						// 	'page_id' => $this->pageId,
					 	// 	'post_id' => $row['id'],
						// 	'individual_name' => '',
						// 	'individual_category' => '',
						// 	'individual_id' => '',
						// 	'to_name' => '',
						// 	'data_aquired_time' => date('Y-m-d H:i:s'),
						// ];
				    }		    	
		    	}
		    	// Yii::$app->db->createCommand()->batchInsert(models\LikesDetailInPost::tableName(), $likes->attributes(), $rowDatasLike)->execute();
	    	}

		    $transaction->commit();
		} catch (\Exception $e) {
		    $transaction->rollBack();
		    throw $e;
		} catch (\Throwable $e) {
		    $transaction->rollBack();
		    throw $e;
		}
    }

    public function actionExport()
    {
    	$data = "Product Name; Article; Price; Description; Amount; Manufacturer\r\n";
		$model = [];//models\LikesDetailInPost::model()->findAll();
		foreach ($model as $value) {
		$data .= $value->name.
		';' . $value->article .
		';' . $value->cost .
		';' . $value->description .
		';' . $value->count .
		';' . $value->producer .
		"\r\n";
		}
		header('Content-type: text/csv');
		header('Content-Disposition: attachment; filename="export_' . date('d.m.Y') . '.csv"');
		echo iconv('utf-8', 'windows-1251', $data); //If suddenly in Windows will gibberish
		Yii::app()->end();
    }
}
