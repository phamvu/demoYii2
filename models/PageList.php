<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "page_list".
 *
 * @property integer $page_id
 * @property string $name
 * @property string $data_aquired_time
 */
class PageList extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page_list';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'name', 'data_aquired_time'], 'required'],
            [['page_id'], 'integer'],
            [['name'], 'string'],
            [['data_aquired_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'page_id' => 'Page ID',
            'name' => 'Name',
            'data_aquired_time' => 'Data Aquired Time',
        ];
    }
}
