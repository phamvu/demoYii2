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
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
    	$PAGE_ID = Yii::$app->params['PAGE_ID'];
		$fb = new Facebook\Facebook([
		   	'app_id' => Yii::$app->params['APP_ID'],
		  	'app_secret' => Yii::$app->params['APP_SECRET'],
		  	'default_graph_version' => Yii::$app->params['APP_VERSION'],
		]);
		$fbApp = $fb->getApp();
		
		$serializedFacebookApp = serialize($fbApp);
		$unserializedFacebookApp = unserialize($serializedFacebookApp);
		
		if(empty($_SESSION['fb_access_token']))
			$token = $_SESSION['fb_access_token'] = $unserializedFacebookApp->getAccessToken();
		else
			$token = $_SESSION['fb_access_token'];

		$paramArr = http_build_query(
			array(
				'limit'=>Yii::$app->params['APP_LIMIT'],
				'fields' => 'name,admin_creator,coordinates,created_time,description,comments{like_count},likes,from,to',
			)
		);
		$requests = [
		  	$fb->request('GET', '/'.$PAGE_ID .'/feed?'.$paramArr),
		];
		try {
		  	$feeds = $fb->sendBatchRequest($requests, $token);
		} catch(Facebook\Exceptions\FacebookResponseException $e) {
		  	echo 'Graph returned an error: ' . $e->getMessage();
		 	exit;
		} catch(Facebook\Exceptions\FacebookSDKException $e) {
		  	echo 'Facebook SDK returned an error: ' . $e->getMessage();
		  	exit;
		}

		foreach ($feeds as $key => $response) {
		  	if ($response->isError()) {
		    	$error = $response->getThrownException();
		    	echo $key . ' error: ' . $error->getMessage();
		  	} else {
		    	$data = $response->getBody();
		    	$this->addData($data);
		  	}
		}
    }
    
    private function addData($datas){
    	$connection = \Yii::$app->db;
    	$transaction = $connection->beginTransaction();
		try {
			$likes = new models\LikesDetailInPost();
			foreach($datas as $row){		    	
		    	$likes->page_id = Yii::$app->params['PAGE_ID'];
		    	$likes->post_id = $row['id'];
		    	$likes->individual_name = $row['name'];
		    	$likes->individual_category = $row['message'];
		    	$likes->individual_id = $row[''];
		    	$likes->save();
	    	}
	    	
		    $connection->createCommand($sql1)->execute();
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
    	
    }
}
