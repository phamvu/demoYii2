<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\PageList;

/**
 * PageListSearch represents the model behind the search form about `app\models\PageList`.
 */
class PageListSearch extends PageList
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id'], 'integer'],
            [['name', 'data_aquired_time'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = PageList::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        $query->andFilterWhere(['like', 'page_id', $this->page_id]);

        $query->andFilterWhere(['like', 'data_aquired_time', $this->data_aquired_time]);
        

        $query->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
