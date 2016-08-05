<?php

/** @var $content string */
/** @var $admin Admin */

use yii\helpers\Html;
use yii\widgets\Menu;
use app\widgets\Alert;
use app\assets\AppAsset;
use app\models\Admin;
use app\models\Order;

AppAsset::register($this);

$admin = Admin::findOne(Yii::$app->user->id);
$route = Yii::$app->requestedAction->uniqueId;

?>
<?php $this->beginContent('@app/views/layouts/base.php'); ?>
<div id="wrapper">

    <!-- Navigation -->
    <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0;">
        <div class="nav navbar-header">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <?= Html::a(Yii::$app->name, Yii::$app->homeUrl, ['class' => 'navbar-brand']) ?>
        </div>

        <ul class="nav navbar-top-links navbar-right">
            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <?= $admin->real_name ?>
                    <i class="fa fa-caret-down"></i>
                </a>
                <ul class="dropdown-menu">
                    <li><?= Html::a('<i class="glyphicon glyphicon-user"></i> 我的资料', ['user/profile']) ?></li>
                    <li><?= Html::a('<i class="glyphicon glyphicon-phone-alt"></i> 修改手机', ['user/update-phone']) ?></li>
                    <li><?= Html::a('<i class="glyphicon glyphicon-lock"></i> 修改密码', ['user/update-password']) ?></li>
                    <li class="divider"></li>
                    <li><?= Html::a('<i class="glyphicon glyphicon-log-out"></i> 注销登录', ['user/logout']) ?></li>
                </ul>
            </li>
        </ul>
    </nav>

    <div class="navbar-default sidebar">
        <div class="sidebar-nav navbar-collapse collapse in" aria-expanded="true">
            <?= Menu::widget([
                    'encodeLabels' => false,
                    'submenuTemplate' => "\n<ul class=\"nav nav-second-level collapse\">\n{items}\n</ul>\n",
                    'options' => [
                        'class' => 'nav',
                        'id' => 'side-menu'
                    ],
                    'items' => [
                        [
                            'label' => '<i class="fa fa-dashboard fa-fw"></i> 仪表盘',
                            'url' => ['/site/index']
                        ],
                        [
                            'label' => '<i class="fa fa-product-hunt fa-fw"></i> 商铺管理<span class="fa arrow"></span>',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => '店铺资料',
                                    'url' => ['/business/profile']
                                ],
                                [
                                    'label' => '店铺设置',
                                    'url' => ['/business/setup']
                                ],
                                [
                                    'label' => '店面实景',
                                    'url' => ['/business/scene']
                                ],
                            ]
                        ],
                        [
                            'label' => '<i class="fa fa-product-hunt fa-fw"></i> 商品管理<span class="fa arrow"></span>',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => '商品列表',
                                    'url' => ['/product'],
                                    'active' => in_array($route, ['product/index', 'product/add', 'product/update', 'goods/delete', 'goods/surplus'])
                                ],
                                [
                                    'label' => '商品分类',
                                    'url' => ['/category'],
                                    'active' => in_array($route, ['category/index', 'category/add', 'category/update', 'goods/delete'])
                                ]
                            ]
                        ],
                        [
                            'label' => '<i class="fa fa-dashboard fa-fw"></i> 活动管理',
                            'url' => ['/activity/index']
                        ],
                        [
                            'label' => '<i class="fa fa-product-hunt fa-fw"></i> 订单管理<span class="fa arrow"></span>',
                            'url' => '#',
                            'items' => [
                                [
                                    'label' => '待接单',
                                    'url' => ['order/index', 'status' => Order::STATUS_WAIT_ACCEPT],
                                ],
                                [
                                    'label' => '待配送',
                                    'url' => ['order/index', 'status' => Order::STATUS_WAIT_SEND],
                                ],
                                [
                                    'label' => '已完成',
                                    'url' => ['order/index', 'status' => Order::STATUS_FINISHED],
                                ]
                            ]
                        ],
                    ]
            ]) ?>
        </div>
        <!-- /.sidebar-collapse -->
    </div>
        <!-- /.navbar-static-side -->

    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?= Html::encode($this->title) ?></h1>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <?= Alert::widget() ?>
            </div>
        </div>
        <?= $content ?>
    </div>
    <!-- /#page-wrapper -->

</div>
<!-- /#wrapper -->

<?php $this->endContent() ?>