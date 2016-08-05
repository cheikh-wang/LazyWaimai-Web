<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use app\models\OrderSearch;
use app\models\Order;
use yii\web\NotFoundHttpException;

/**
 * 订单控制器
 */
class OrderController extends Controller {

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * 店铺设置的操作
     * @param int $status
     * @return string
     */
    public function actionIndex($status = Order::STATUS_WAIT_ACCEPT) {
        $searchModel = new OrderSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('index', [
            'status' => $status,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    public function actionStatus($id) {
        $id = Yii::$app->request->get('id');
        $currentStatus = Yii::$app->request->get('current');
        $targetStatus = Yii::$app->request->get('target');

        /* @var $model Order */
        $model = Order::findOne($id);
        if (!$model) {
            throw new NotFoundHttpException('未找到该订单。');
        }
        $model->status = $targetStatus;
        if ($model->save(false)) {
            Yii::$app->session->setFlash('success', '操作成功.');
        } else {
            Yii::$app->session->setFlash('success', '操作失败.');
        }

        return $this->redirect(['order/index', 'status' => $currentStatus]);
    }
}