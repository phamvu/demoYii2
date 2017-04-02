<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;
/**
 * PostFromFeed
 */
class PostFromFeed extends ActiveRecord
{
	public static function tableName()
    {
        return 'post_from_feed';
    }
    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            //[['page_id', 'post_id', 'individual_name', 'individual_category','individual_id'], 'required'],
        ];
    }
}
