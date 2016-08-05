<?php

/* @var $this yii\web\View */
/* @var $status integer */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\OrderSearch */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use app\models\Order;

$statusList = Order::getStatusList();
$this->title = isset($statusList[$status]) ? $statusList[$status] : '';

$buttons = [];
if ($status == Order::STATUS_WAIT_ACCEPT) {
    $buttons['status'] = function ($url, $model, $key) {
        return Html::a('接单', ['/order/status',
            'id' => $model->id,
            'current' => Order::STATUS_WAIT_ACCEPT,
            'target' => Order::STATUS_WAIT_SEND
        ], ['class' => 'btn btn-primary']);
    };
} else if ($status == Order::STATUS_WAIT_SEND) {
    $buttons['status'] = function ($url, $model, $key) {
        return Html::a('配送', ['/order/status',
            'id' => $model->id,
            'current' => Order::STATUS_WAIT_SEND,
            'target' => Order::STATUS_WAIT_ARRIVE
        ], ['class' => 'btn btn-primary']);
    };
}

?>
<div class="row">
    <div class="col-lg-12">
        <?php Pjax::begin() ?>
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-center'],
                'summaryOptions' => ['tag' => 'p', 'class' => 'text-right text-info'],
                'columns' => [
                    [
                        'attribute' => 'id',
                        'headerOptions' => ['class' => 'col-lg-1'],
                        'filterInputOptions' => ['class' => 'form-control input-sm']
                    ],
                    [
                        'attribute' => 'consignee',
                        'headerOptions' => ['class' => 'col-lg-1'],
                        'filterInputOptions' => ['class' => 'form-control input-sm']
                    ],
                    [
                        'attribute' => 'phone',
                        'headerOptions' => ['class' => 'col-lg-1'],
                        'filterInputOptions' => ['class' => 'form-control input-sm']
                    ],
                    [
                        'attribute' => 'address',
                        'headerOptions' => ['class' => 'col-lg-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm']
                    ],
                    [
                        'attribute' => 'total_price',
                        'headerOptions' => ['class' => 'col-lg-1'],
                        'format' => 'html',
                        'filterInputOptions' => ['class' => 'form-control input-sm', 'title' => '支持运算符'],
                        'value' => function ($model, $key, $index, $column) {
                            /** @var $model Order */
                            return '&yen; ' . $model->total_price;
                        }
                    ],
                    [
                        'attribute' => 'pay_method',
                        'filter' => Order::getPayMethodList(),
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                        'headerOptions' => ['class' => 'col-lg-1'],
                        'value' => function ($model, $key, $index, $column) {
                            /** @var $model Order */
                            $list = Order::getPayMethodList();

                            return $list[$model->pay_method];
                        }
                    ],
                    [
                        'attribute' => 'remark',
                        'headerOptions' => ['class' => 'col-lg-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm']
                    ],
                    [
                        'attribute' => 'created_at',
                        'headerOptions' => ['class' => 'col-lg-2'],
                        'format' => ['date', 'php:Y-m-d H:i'],
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'type' => DatePicker::TYPE_INPUT,
                            'attribute' => 'date',
                            'options' => ['class' => 'input-sm'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd hh:mm'
                            ]
                        ]),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'headerOptions' => ['class' => 'col-lg-1'],
                        'template' => '{status}',
                        'buttons' => $buttons
                    ]
                ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>