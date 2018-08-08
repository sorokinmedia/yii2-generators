<?php
namespace tests\runtime\data;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use sorokinmedia\gii\generators\tests\crud\Post;

/**
 * Class PostSearch
 * @package tests\runtime\data
 */
class PostSearch extends Post
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'type_id', 'exchange_id', 'sector_id'], 'integer'],
            [['ticker', 'name', 'google_link'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * @param array $params
     * @return ActiveDataProvider
     */
    public function search(array $params)
    {
        $query = Post::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);
        $this->load($params);
        if (!$this->validate()) {
            return $dataProvider;
        }
        $query->andFilterWhere([
            'id' => $this->id,
            'type_id' => $this->type_id,
            'exchange_id' => $this->exchange_id,
            'sector_id' => $this->sector_id,
        ]);

        $query->andFilterWhere(['like', 'ticker', $this->ticker])
            ->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'google_link', $this->google_link]);
        return $dataProvider;
    }
}
