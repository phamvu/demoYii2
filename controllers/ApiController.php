<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use Facebook;
use Facebook\FacebookRequest;
use app\models; //\LikesDetailInPost;
//use app\models\PostFromFeed;
use kartik\grid\GridView;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use csvexport\CSVExport;

class ApiController extends Controller {

    public $defaultAction = 'index';
    private $pageId;
    private $token;
    private $fb;

    public function __construct($id, $module, $config = []) {
        if (!empty($_SESSION['fb_access_token']))
            $this->token = $_SESSION['fb_access_token'];
        parent::__construct($id, $module, $config);
        $this->fb = new Facebook\Facebook([
                'app_id' => Yii::$app->params['APP_ID'],
                'app_secret' => Yii::$app->params['APP_SECRET'],
                'default_graph_version' => Yii::$app->params['APP_VERSION'],
        ]);
        $fbApp = $this->fb->getApp();
        $serializedFacebookApp = serialize($fbApp);
        $unserializedFacebookApp = unserialize($serializedFacebookApp);
        $this->fb->setDefaultAccessToken($unserializedFacebookApp->getAccessToken());
    }

    public function actionIndex() {
        $post = new models\PostFromFeed();
        $page = new models\PageList();
        $query = $post::find();

        /* Config export CSV Page Feed - Start */

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
        /* Config export CSV Page Feed - End */
        return $this->render('index', [
                    'dataPost' => [
                        'exportConfig' => $exportConfig,
                        'toolbar' => ['{export}', '{toggleData}'],
                        'containerOptions' => ['style' => 'overflow: auto'],
                        'headerRowOptions' => ['class' => 'kartik-sheet-style'],
                        'filterRowOptions' => ['class' => 'kartik-sheet-style'],
                        'dataProvider' => $dataProvider,
                        'filterModel' => $post,
                        //'columns' => $post->attributes(),
                        'responsive' => true,
                        'hover' => true,
                        'toolbar' => [
                            ['content' =>
                                ''
                            ],
                            '{export}',
                        ],
                        'panel' => [
                            'type' => GridView::TYPE_PRIMARY,
                        ]
                    ],
                        //'pageList'=> $page::find()->all()
                        ]
        );
    }

    public function actionCreate($PAGE_ID = '') {
        if (empty($PAGE_ID))
            $PAGE_ID = Yii::$app->params['PAGE_ID'];
        else
            $this->pageId = $PAGE_ID;

		$this->fb = new Facebook\Facebook([
		   	'app_id' => Yii::$app->params['APP_ID'],
		  	'app_secret' => Yii::$app->params['APP_SECRET'],
		  	'default_graph_version' => Yii::$app->params['APP_VERSION'],
		]);
		$fbApp = $this->fb->getApp();
		
		$serializedFacebookApp = serialize($fbApp);
		$unserializedFacebookApp = unserialize($serializedFacebookApp);
		
		if(empty($_SESSION['fb_access_token']))
			$this->token = $_SESSION['fb_access_token'] = $unserializedFacebookApp->getAccessToken();

		
        ///////////////////////////////
        $paramArrPage = http_build_query(
                array(
                    'fields' => 'category,category_list'
                )
        );
        $paramArrFeed = http_build_query(
                array(
                    'limit' => Yii::$app->params['APP_LIMIT'],
                    'fields' => 'name,admin_creator,coordinates,created_time,description,likes.summary(true),comments.summary(true),from,to,message,message_tags,with_tags,story_tags',
                )
        );
        $requests = [
            $this->fb->request('GET', '/' . $PAGE_ID . '?' . $paramArrPage),
            $this->fb->request('GET', '/' . $PAGE_ID . '/feed?' . $paramArrFeed),
        ];

        try {
            $feeds = $this->fb->sendBatchRequest($requests, $this->token);
        } catch (Facebook\Exceptions\FacebookResponseException $e) {
            echo 'Graph returned an error: ' . $e->getMessage();
            exit;
        } catch (Facebook\Exceptions\FacebookSDKException $e) {
            echo 'Facebook SDK returned an error: ' . $e->getMessage();
            exit;
        }

        $pageInfo = $feeds[0]->getDecodedBody();
        $response = $feeds[1];

        if ($response->isError()) {
            $error = $response->getThrownException();
            echo ' error: ' . $error->getMessage();
            die;
        } else {
            $data = $response->getDecodedBody();
            $this->addData($data['data'], $pageInfo);
        }

        //return $this->render('../site/index');
        return $this->goBack((!empty(Yii::$app->request->referrer) ? Yii::$app->request->referrer : null));
        //return $this->redirect('index');
    }

    private function addData($datas, $pageInfo = []) {
        set_time_limit(50000);
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', 50000);
        ini_set('max_allowed_packet', '500M');
        ini_set('wait_timeout', 50000);

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();

        try {
            $post = new models\PostFromFeed();
            $likes = new models\LikesDetailInPost();
            $rowDatas = array();
            $requests = array();
            foreach ($datas as $i => $row) {
                $rowDatas[$i] = [
                    'page_id' => $this->pageId,
                    'post_id' => $row['id'],
                    'from_name' => $row['from']['name'],
                    'from_category' => $pageInfo['category'], //get api page category
                    'from_id' => $row['from']['id'],
                    'page_owner' => ($row['from']['id'] == $this->pageId) ? 1 : 0,
                    'to_id' => @$row['to']['data'][0]['id'],
                    'to_category' => @$row['to']['data'][0]['category'],
                    'to_name' => @$row['to']['data'][0]['name'],
                    'message' => htmlentities(@$row['message']),
                    'message_tags' => (!empty($row['message_tags'])) ? 1 : 0,
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
                    'story_tags' => (!empty($row['story_tags'])) ? 1 : 0,
                    'with_tags' => (!empty($row['with_tags'])) ? 1 : 0,
                    'comments' => @$row['comments']['summary']['total_count'],
                    'object_id' => @$row['object_id'],
                    'application_name' => @$row['application'][0]['name'],
                    'application_id' => @$row['application'][0]['id'],
                    'created_time' => @$row['created_time'],
                    'updated_time' => @$row['updated_time'],
                    'data_aquired_time' => date('Y-m-d H:i:s'),
                ];
                $dell = $post::find()->where(
                                [
                                    'page_id' => $rowDatas[$i]['page_id'],
                                    'post_id' => $rowDatas[$i]['post_id']
                                ]
                        )->one();

                if (!empty($dell))
                    $dell->delete();
                $requests[] = $this->fb->request('GET', '/' . $row['id'] . '/likes')->setParams(array('local_post_id' => $row['id'], 'fields' => 'id,profile_type,name', 'limit' => @$row['likes']['summary']['total_count']));
            }
            Yii::$app->db->createCommand()->batchInsert(models\PostFromFeed::tableName(), $post->attributes(), $rowDatas)->execute();

            if (!empty($requests)) {
                $feeds = $this->fb->sendBatchRequest($requests, $this->token);
                foreach ($feeds as $i => $row) {
                    if (!$row->isError()) {
                        $rowDatasLike = [];
                        //$data = $row->getDecodedBody(); //Backup
                        $Params = $requests[$i]->getParams();
                        $limitFeed = $Params["limit"];
                        $likeEdges = $row->getGraphEdge();
                        $this->SaveAllLikes($rowDatasLike, $likeEdges, $Params["local_post_id"], $likes, $Params);
                        $totalP = ($limitFeed > Yii::$app->params['MAX_LIMIT']) ? ceil($limitFeed / Yii::$app->params['MAX_LIMIT']) : 0;
                        //while ($nextLikes = $this->fb->next($likeEdges)){
                        for ($j = 0; $j <= $totalP; $j++) {
                            $nextLikes = $this->fb->next($likeEdges);
                            if (empty($nextLikes)) {
                                break;
                            }
                            $this->SaveAllLikes($rowDatasLike, $nextLikes, $Params["local_post_id"], $likes, $Params);
                            if (count($nextLikes) < Yii::$app->params['MAX_LIMIT']) {
                                break;
                            } else {
                                $likeEdges = $nextLikes;
                            }
                        };
                        $likes::deleteAll(['page_id' => $this->pageId, 'post_id' => $Params["local_post_id"]]);
                        Yii::$app->db->createCommand()->batchInsert(models\LikesDetailInPost::tableName(), $likes->attributes(), $rowDatasLike)->execute();
                    }
                }
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

    public function SaveAllLikes(&$rowDatasLike, $data, $post_id, $likes, $Params) {
        foreach ($data as $k => $like) {
            $rowDatasLike[] = [
                'page_id' => $this->pageId,
                'post_id' => $post_id,
                'individual_name' => @$like['name'],
                'individual_category' => @$like['profile_type'], //get category by 
                'individual_id' => @$like['id'],
                'to_name' => '',
                'data_aquired_time' => date('Y-m-d H:i:s'),
            ];
        }
    }

    public function actionGetidbyname() {
        if (Yii::$app->request->isAjax) {
            $data = Yii::$app->request->post('PageList');
            $name = $data['name'];
            $result = [
                'search' => $name,
                'token' => '',
                'code' => 100,
            ];
            try {
                $response = $this->fb->get('/'.$name);
                $userNode = $response->getGraphUser();
                
            } catch(Facebook\Exceptions\FacebookResponseException $e) {
                $userNode = $e->getMessage();
                $result['code'] = 404;
            } 
            $result['result'] = $userNode;
            \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            
            return $result;
        }
    }

    /*
      public function actionsDemo()
      {
      if(empty($PAGE_ID))
      $PAGE_ID = Yii::$app->params['PAGE_ID'];
      else
      $this->pageId = $PAGE_ID;

      $this->fb = new Facebook\Facebook([
      'app_id' => Yii::$app->params['APP_ID'],
      'app_secret' => Yii::$app->params['APP_SECRET'],
      'default_graph_version' => Yii::$app->params['APP_VERSION'],
      ]);
      $fbApp = $this->fb->getApp();

      $serializedFacebookApp = serialize($fbApp);
      $unserializedFacebookApp = unserialize($serializedFacebookApp);
      $likes = new models\LikesDetailInPost();
      if(empty($_SESSION['fb_access_token']))
      $this->token = $_SESSION['fb_access_token'] = $unserializedFacebookApp->getAccessToken();


      $requests = [
      $this->fb->request('GET', '/'. '452101311658243_658973447637694' .'/likes')->setParams(array('local_post_id'=> '452101311658243_658973447637694','fields'=>'id,profile_type,name','limit'=> 120))
      ];

      $feeds = $this->fb->sendBatchRequest($requests, $this->token);
      foreach($feeds as $i=> $row){
      if(!$row->isError()) {
      $rowDatasLike = [];
      //$data = $row->getDecodedBody(); //Backup
      $Params = $requests[$i]->getParams();
      $limitFeed = $Params["limit"];
      $likeEdges = $row->getGraphEdge();

      $this->SaveAllLikes($rowDatasLike, $likeEdges, $Params["local_post_id"], $likes, $Params);

      $totalP = ($limitFeed> 100)?ceil( $limitFeed/ 100): 0;
      $i = 0;
      while ($nextLikes = $this->fb->next($likeEdges)){
      echo count($nextLikes).' ROW: ' .$i++.' ==> <br/>';
      $this->SaveAllLikes($rowDatasLike, $nextLikes, $Params["local_post_id"], $likes, $Params);
      $likeEdges = $nextLikes;
      }
      var_dump('<pre>', $rowDatasLike, '</pre><br/><br/>'); /////////AAAAAAAAAAAAAA
      // for($i = 0; $i < $totalP; $i ++)
      // {
      // 	$nextLikes = $this->fb->next($likeEdges);
      // 	if(empty($nextLikes)) break;
      // 	$this->SaveAllLikes($rowDatasLike ,$nextLikes, $Params["local_post_id"], $likes, $Params);
      // 	var_dump('<pre>', $rowDatasLike, '</pre><br/><br/>'); /////////AAAAAAAAAAAAAA
      // 	if(count($nextLikes) < 10) break;
      // };
      //$likes::deleteAll(['page_id'=> $this->pageId, 'post_id'=> $Params["local_post_id"]]);
      //Yii::$app->db->createCommand()->batchInsert(models\LikesDetailInPost::tableName(), $likes->attributes(), $rowDatasLike)->execute();
      }
      }
      die;

      return;
      } */
}
