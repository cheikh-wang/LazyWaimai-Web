<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\User;
use app\models\Order;
use yii\base\Exception;
use yii\web\HttpException;
use yii\base\UserException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;


class SiteController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['index', 'error'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['error'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'index' => ['get', 'post'],
                    'error' => ['get', 'post'],
                ],
            ],
        ];
    }

    /**
     * 主页仪表盘的操作
     * @return string
     */
    public function actionIndex() {
        $last15days         = [];
        $last6Month         = [];
        $numDataOrder       = []; // 订单生成数据
        $numDataUser        = []; // 用户注册数据
        $numDataVolume      = []; // 营业额数据
        $numDataCompleted   = []; // 订单完成数据
        $numDataVolumeMonth = []; // 每月营业额

        $today = strtotime("00:00:00");
        $todayEnd = strtotime("23:59:59");
        for ($i = 0; $i < 15; $i++) {
            $timestrap = strtotime('-' . $i . ' days', $today);
            $timestrapEnd = strtotime('-' . $i . ' days', $todayEnd);
            $where = [
                'and',
                ['>=', 'created_at', $timestrap],
                ['<=', 'created_at', $timestrapEnd]
            ];
            array_unshift($last15days, date('m/d', $timestrap));
            array_unshift($numDataOrder, Order::find()->where($where)->count());
            array_unshift($numDataUser, User::find()->where($where)->count());

            $data = Order::find()->select(['sum(total_price) AS volume', 'count(*) AS count'])
                ->where($where)
                ->asArray()
                ->one();
            array_unshift($numDataVolume, $data['volume']);
            array_unshift($numDataCompleted, $data['count']);
        }

        for ($i = 0; $i < 6; $i ++) {
            $timestrap = strtotime("first day of -{$i} month", $today);
            $timestrapEnd = strtotime("last day of -{$i} month", $todayEnd);
            $where = [
                'and',
                ['>=', 'created_at', $timestrap],
                ['<=', 'created_at', $timestrapEnd]
            ];
            array_unshift($last6Month, date('Y/m', $timestrap));
            array_unshift($numDataVolumeMonth, Order::find()->where($where)->sum('total_price'));
        }

        $data2 = Order::find()->select(['sum(total_price) AS volume', 'count(*) AS count'])
            ->asArray()
            ->one();

        return $this->render('index', [
            'last15days' => $last15days,
            'last6Month' => $last6Month,
            'numDataOrder' => $numDataOrder,
            'numDataUser' => $numDataUser,
            'numDataVolume' => $numDataVolume,
            'numDataCompleted' => $numDataCompleted,
            'numDataVolumeMonth' => $numDataVolumeMonth,
            'countOrder' => Order::find()->count(),
            'countCompleted' => $data2['count'],
            'sumVolume' => $data2['volume'] ?: '0.00',
            'countUser' => User::find()->count(),
        ]);
    }

    /**
     * 显示系统报错的操作
     * @return string
     */
    public function actionError() {
        if (Yii::$app->user->isGuest) {
            $this->layout = 'base';
        }

        if (($exception = Yii::$app->getErrorHandler()->exception) === null) {
            return '';
        }

        if ($exception instanceof HttpException) {
            $code = $exception->statusCode;
        } else {
            $code = $exception->getCode();
        }
        if ($exception instanceof Exception) {
            $name = $exception->getName();
        } else {
            $name = '错误';
        }
        if ($code) {
            $name .= " (#$code)";
        }

        if ($exception instanceof UserException) {
            $message = $exception->getMessage();
        } else {
            $message = '服务器错误！';
        }

        if (Yii::$app->getRequest()->getIsAjax()) {
            return "$name: $message";
        } else {
            return $this->render('error', [
                'name' => $name,
                'message' => $message,
                'exception' => $exception,
            ]);
        }
    }
}