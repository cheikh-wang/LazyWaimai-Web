<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Category;

class CategorySearch extends Category {

    public $date;

    /**
     * 定义可被搜索的字段
     * @return array
     */
    public function rules() {
        return [
            [['name', 'description', 'date'], 'safe'],
        ];
    }

    public function search($params) {
        /* @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);

        $query = Category::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query->where(['business_id' => $admin->business_id]),
            'sort' => [
                'defaultOrder' => ['id' => SORT_ASC]
            ],
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $dateBegin = strtotime($this->date);
        $dateEnd = $dateBegin + 86400;

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['>=', 'created_at', $this->date ? $dateBegin : null])
            ->andFilterWhere(['<', 'created_at', $this->date ? $dateEnd : null]);

        return $dataProvider;
    }
}