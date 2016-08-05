<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $searchModel app\models\CategorySearch */

use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\grid\GridView;
use kartik\date\DatePicker;

$this->title = '商品分类列表';

?>
<p>
    <?= Html::a('<i class="fa fa-plus"></i> 添加商品分类', ['category/add'], ['class' => 'btn btn-primary']) ?>
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
                    'headerOptions' => ['class' => 'col-md-1']
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
                    'attribute' => 'created_at',
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
                    'headerOptions' => ['class' => 'col-md-1']
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'header' => '操作',
                    'headerOptions' => ['class' => 'col-md-1'],
                    'template' => '{update} {delete}',
                    'buttons' => [
                        'update' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-edit" aria-hidden="true"></span>', $url, ['title' => '修改']);
                        },
                        'delete' => function ($url, $model, $key) {
                            return Html::a('<span class="glyphicon glyphicon-remove" aria-hidden="true"></span>', $url, ['title' => '删除']);
                        },
                    ]
                ]
            ]
        ]) ?>
        <?php Pjax::end() ?>
    </div>
</div>