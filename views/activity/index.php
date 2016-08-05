<?php

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
/* @var $model app\models\Activity */

use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use kartik\date\DatePicker;
use app\models\Category;

$this->title = '活动列表';

?>

<p>
    <?= Html::a('<i class="fa fa-plus"></i> 添加活动', ['product/add'], ['class' => 'btn btn-primary']) ?>
</p>
<div class="row">
    <div class="col-lg-12">
        <?php Pjax::begin() ?>
        <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'tableOptions' => ['class' => 'table table-striped table-bordered table-center'],
                'summaryOptions' => ['tag' => 'p', 'class' => 'text-right text-info'],
                'columns' => [
                    [
                        'class' => 'yii\grid\SerialColumn',
                        'header' => '编号',
                        'headerOptions' => ['class' => 'col-md-1'],
                    ],
                    [
                        'attribute' => 'name',
                        'label' => '活动名称',
                        'format' => 'html',
                        'headerOptions' => ['class' => 'col-md-3'],
                        'value' => function ($data) {
                            return Html::tag('span', $data['icon_name'], [
                                'style' => [
                                    'background-color' => $data['icon_color'],
                                    'color' => 'white'
                                ]
                            ]).' '.$data['name'];
                        }
                    ],
                    [
                        'attribute' => 'description',
                        'label' => '活动描述',
                        'headerOptions' => ['class' => 'col-md-4']
                    ],
                    [
                        'class' => 'yii\grid\ActionColumn',
                        'header' => '操作',
                        'headerOptions' => ['class' => 'col-md-4'],
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