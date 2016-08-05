<?php

namespace app\models;

use Yii;
use app\models\Product;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class ProductSearch extends Product {

    public $date;

    /**
     * 定义可被搜索的字段
     * @return array
     */
    public function rules() {
        return [
            [['name', 'description', 'category_id', 'price', 'month_sales', 'left_num', 'date'], 'safe'],
            [['name', 'description', 'price', 'month_sales', 'left_num'], 'trim']
        ];
    }

    public function search($params) {
        /** @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);

        $query = Product::find();
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

        $this->_addDigitalFilter($query, 'price');
        $this->_addDigitalFilter($query, 'month_sales');
        $this->_addDigitalFilter($query, 'left_num');

        $dateBegin = strtotime($this->date);
        $dateEnd = $dateBegin + 86400;

        // adjust the query by adding the filters
        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['category_id' => $this->category_id])
            ->andFilterWhere(['>=', 'created_at', $this->date ? $dateBegin : null])
            ->andFilterWhere(['<', 'created_at', $this->date ? $dateEnd : null]);

        return $dataProvider;
    }

    /**
     * 附加数字过滤器
     * @param $query ActiveQuery
     * @param $attribute string
     */
    protected function _addDigitalFilter($query, $attribute) {
        $pattern = '/^(>|>=|<|<=|=)(\d*\.?\d+)$/';
        if (preg_match($pattern, $this->{$attribute}, $matches) === 1) {
            $query->andFilterWhere([$matches[1], $attribute, $matches[2]]);
        } else {
            $query->andFilterWhere(['like', $attribute, $this->{$attribute}]);
        }
    }
}