<?php

namespace app\models;

use Yii;
use app\models\Order;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;

class OrderSearch extends Order {

    public $date;

    /**
     * 定义可被搜索的字段
     * @return array
     */
    public function rules() {
        return [
            [['id', 'order_num', 'consignee', 'phone', 'address', 'status', 'origin_price', 'discount_price', 'total_price', 'pay_method', 'remark', 'date'], 'safe'],
            [['order_num', 'consignee', 'phone', 'address', 'origin_price', 'discount_price', 'total_price', 'remark'], 'trim']
        ];
    }

    public function search($params) {
        /** @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);

        $query = Order::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query->where([
                'business_id' => $admin->business_id,
                'status' => $params['status']
            ]),
            'sort' => [
                'defaultOrder' => ['created_at' => SORT_DESC]
            ],
            'pagination' => [
                'pageSize' => 20
            ]
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $this->_addDigitalFilter($query, 'order_num');
        $this->_addDigitalFilter($query, 'phone');
        $this->_addDigitalFilter($query, 'origin_price');
        $this->_addDigitalFilter($query, 'discount_price');
        $this->_addDigitalFilter($query, 'total_price');

        $dateBegin = strtotime($this->date);
        $dateEnd = $dateBegin + 86400;

        // adjust the query by adding the filters
        $query->andFilterWhere(['id' => $this->id])
            ->andFilterWhere(['like', 'consignee', $this->consignee])
            ->andFilterWhere(['like', 'address', $this->address])
            ->andFilterWhere(['like', 'remark', $this->remark])
            ->andFilterWhere(['pay_method' => $this->pay_method])
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