<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
/**
 * LikesDetailInPost
 */
class LikesDetailInPost extends ActiveRecord
{
	public static function tableName()
    {
        return 'likes_detail_in_post';
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
              [['page_id', 'post_id', 'individual_name', 'individual_category','individual_id'],'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'page_id'=>'page_id',
        ];
    }
}
