<?php

namespace app\controllers;

use Yii;
use Exception;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\Product;
use app\models\Admin;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use app\models\ProductSearch;

/**
 * 商品的控制器
 */
class ProductController extends Controller {

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

    public function actionTest() {
        $model = Product::findOne(1);

        return $this->render('test', [
            'model' => $model,
        ]);
    }

    /**
     * 浏览商品列表的操作
     *
     * @return string
     */
    public function actionIndex() {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('index', [
            'model' => new Product(),
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 添加商品的操作
     * @return string|\yii\web\Response
     * @throws Exception
     */
    public function actionAdd() {
        $model = new Product();
        $model->setScenario('insert');

        if ($model->load(Yii::$app->request->post())) {

            $model->image = UploadedFile::getInstance($model, 'image');

            /* @var $admin Admin */
            $admin = Admin::findOne(Yii::$app->user->id);
            $model->business_id = $admin->business_id;

            if ($model->validate()) {
                // 通过事务来保存数据
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    // 生成一个随机的图片名并保存到数据库
                    $model->image_path = Yii::$app->security->generateRandomString(10).'.'.$model->image->extension;
                    if (!$model->save(false)) {
                        throw new Exception('商品添加失败！');
                    }

                    // 保存上传图片到服务器的指定目录
                    $filename = Yii::getAlias(Yii::$app->params['product.imagePath']).DIRECTORY_SEPARATOR.$model->image_path;
                    if (!$model->image->saveAs($filename)) {
                        throw new Exception('商品图片添加失败！');
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '成功添加商品“'.$model->name.'”。');

                    return $this->refresh();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('form', [
            'model' => $model
        ]);
    }

    /**
     * 更新商品的操作
     * @param $id
     * @return string|\yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     * @throws \yii\db\Exception
     */
    public function actionUpdate($id) {
        /* @var $model Product */
        $model = Product::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('未找到该商品。');
        }

        if ($model->load(Yii::$app->request->post())) {

            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->validate()) {
                if ($model->image !== null) {
                    $model->image_path = Yii::$app->security->generateRandomString(10).'.'.$model->image->extension;
                }

                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if (!$model->save(false)) {
                        throw new Exception('商品更新失败！');
                    }

                    if ($model->image !== null) {
                        $filename = Yii::getAlias(Yii::$app->params['product.imagePath']).DIRECTORY_SEPARATOR.$model->image_path;
                        if (!$model->image->saveAs($filename)) {
                            throw new Exception('商品图片添加失败！');
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '成功更新商品“'.$model->name.'”。');

                    return $this->refresh();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('form', [
            'model' => $model
        ]);
    }

    /**
     * 删除商品的操作
     * @param $id
     * @return \yii\web\Response
     * @throws Exception
     * @throws NotFoundHttpException
     */
    public function actionDelete($id) {
        /* @var $model Product */
        $model = Product::findOne($id);

        if (!$model) {
            throw new NotFoundHttpException('未找到该商品。');
        }

        // 删除数据库数据
        if ($model->delete()) {
            Yii::$app->session->setFlash('success', '成功删除商品“'.$model->name.'”。');
        } else {
            Yii::$app->session->setFlash('danger', '删除商品失败。');
        }

        return $this->redirect(['product/index']);
    }
}