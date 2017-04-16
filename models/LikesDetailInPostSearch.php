<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\LikesDetailInPost;

/**
 * LikesDetailInPostSearch represents the model behind the search form about `app\models\LikesDetailInPost`.
 */
class LikesDetailInPostSearch extends LikesDetailInPost
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'individual_id'], 'integer'],
            [['post_id', 'individual_name', 'individual_category', 'to_name', 'data_aquired_time'], 'safe'],
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
        $query = LikesDetailInPost::find();

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

        // grid filtering conditions
        $query->andFilterWhere([
            'page_id' => $this->page_id,
            'individual_id' => $this->individual_id,
            'data_aquired_time' => $this->data_aquired_time,
        ]);

        $query->andFilterWhere(['like', 'post_id', $this->post_id])
            ->andFilterWhere(['like', 'individual_name', $this->individual_name])
            ->andFilterWhere(['like', 'individual_category', $this->individual_category])
            ->andFilterWhere(['like', 'to_name', $this->to_name]);

        return $dataProvider;
    }
}
