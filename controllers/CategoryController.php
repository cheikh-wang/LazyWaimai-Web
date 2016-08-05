<?php

namespace app\controllers;

use Yii;
use Exception;
use yii\web\Response;
use yii\web\Controller;
use app\models\Admin;
use app\models\Product;
use app\models\Category;
use yii\filters\AccessControl;
use app\models\CategorySearch;
use yii\web\NotFoundHttpException;

/**
 * 商品分类的控制器
 */
class CategoryController extends Controller {

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
     * 浏览商品分类的操作
     */
    public function actionIndex() {
        $searchModel = new CategorySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 添加商品分类的操作
     * @return string|Response
     */
    public function actionAdd() {
        $model = new Category();
        
        if ($model->load(Yii::$app->request->post())) {
            /* @var $admin Admin */
            $admin = Admin::findOne(Yii::$app->user->id);
            $model->business_id = $admin->business_id;

            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '成功添加分类“'.$model->name.'”。');
                    return $this->redirect(['index']);
                } else {
                    Yii::$app->session->setFlash('danger', '分类添加失败。');
                }
            }
        }
        
        return $this->render('form', [
            'model' => $model
        ]);
    }

    /**
     * 修改商品分类的操作
     * @param $id
     * @return string|Response
     * @throws NotFoundHttpException
     */
    public function actionUpdate($id) {
        /** @var $model Category */
        $model = Category::findOne($id);
        
        if (!$model) {
            throw new NotFoundHttpException('未找到该分类。');
        }
        
        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                if ($model->save()) {
                    Yii::$app->session->setFlash('success', '成功更新分类“'.$model->name.'”。');
                    return $this->refresh();
                } else {
                    Yii::$app->session->setFlash('danger', '分类更新失败。');
                }
            }
        }

        return $this->render('form', [
            'model' => $model
        ]);
    }

    /**
     * 删除商品分类的操作
     * @param $id
     * @return \yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionDelete($id) {
        /* @var $model Category */
        $model = Category::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('未找到该商品分类。');
        }

        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$model->delete()) {
                throw new Exception('删除商品分类失败！');
            }

            if (!Product::deleteAll(['category_id' => $model->id])) {
                throw new Exception('删除商品分类下的商品失败！');
            }

            $transaction->commit();
            Yii::$app->session->setFlash('success', '删除商品分类成功“'.$model->name.'”。');

            return $this->refresh();
        } catch (Exception $e) {
            $transaction->rollBack();
            Yii::$app->session->setFlash('danger', $e->getMessage());
        }

        return $this->redirect(['category/index']);
    }
}