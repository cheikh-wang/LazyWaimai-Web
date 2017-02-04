<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\ProductSearch */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use app\models\Category;
use app\models\Product;

$this->title = '商品列表';

?>

<p>
    <?= Html::a('<i class="fa fa-plus"></i> 添加商品', ['product/add'], ['class' => 'btn btn-primary']) ?>
</p>
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
                        'headerOptions' => ['class' => 'col-md-1'],
                    ],
                    [
                        'attribute' => 'image_path',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'format' => 'raw',
                        'value' => function($model){
                            /** @var $model Product */
                            return Html::img($model->image_path, [
                                'class' => 'img-rounded',
                                'width' => 50,
                                'height' => 50
                            ]);
                        }
                    ],
                    [
                        'attribute' => 'name',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'filterInputOptions' => ['class' => 'form-control input-sm']
                    ],
                    [
                        'attribute' => 'description',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'filterInputOptions' => ['class' => 'form-control input-sm']
                    ],
                    [
                        'attribute' => 'category_id',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'filter' => Category::getKeyValuePairs(),
                        'filterInputOptions' => ['class' => 'form-control input-sm'],
                        'value' => function ($model, $key, $index, $column) {
                            return $model->category->name;
                        }
                    ],
                    [
                        'attribute' => 'price',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'format' => 'html',
                        'filterInputOptions' => ['class' => 'form-control input-sm', 'title' => '支持运算符'],
                        'value' => function ($model, $key, $index, $column) {
                            return '&yen; ' . $model->price;
                        }
                    ],
                    [
                        'attribute' => 'month_sales',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'filterInputOptions' => ['class' => 'form-control input-sm', 'title' => '支持运算符']
                    ],
                    [
                        'attribute' => 'left_num',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'filterInputOptions' => ['class' => 'form-control input-sm', 'title' => '支持运算符']
                    ],
                    [
                        'attribute' => 'created_at',
                        'headerOptions' => ['class' => 'col-md-2'],
                        'format' => ['date', 'php:Y-m-d H:i'],
                        'filter' => DatePicker::widget([
                            'model' => $searchModel,
                            'type' => DatePicker::TYPE_COMPONENT_APPEND,
                            'attribute' => 'date',
                            'options' => ['class' => 'input-sm'],
                            'pluginOptions' => [
                                'autoclose' => true,
                                'format' => 'yyyy-mm-dd'
                            ]
                        ]),
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'headerOptions' => ['class' => 'col-md-1'],
                        'template' => '{update} {delete} {surplus}',
                        'buttons' => [
                            'delete' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', $url, ['title' => '删除']);
                            },
                            'surplus' => function ($url, $model, $key) {
                                return Html::a('<span class="glyphicon glyphicon-equalizer" aria-hidden="true"></span>', $url, ['title' => '库存变化记录']);
                            },
                        ]
                    ]
                ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>