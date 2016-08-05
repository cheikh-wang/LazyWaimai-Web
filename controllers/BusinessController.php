<?php

namespace app\controllers;


use Yii;
use Exception;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\Admin;
use app\models\Business;
use app\models\BusinessScene;
use yii\filters\AccessControl;
use app\actions\GalleryManagerAction;

/**
 * 商品的控制器
 */
class BusinessController extends Controller {

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
     * @inheritdoc
     */
    public function actions() {
        return [
            'gallery' => [
                'class' => GalleryManagerAction::className(),
            ],
        ];
    }

    /**
     * 店铺设置的操作
     */
    public function actionSetup() {
        /* @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);
        /* @var $model Business */
        $model = Business::findOne($admin->business_id);

        $json = file_get_contents(Yii::getAlias('@webroot').'/raw/booking_times.json');
        $bookingTimes = Json::decode($json);

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', '店铺设置成功。');
            } else {
                Yii::$app->session->setFlash('error', '系统异常,店铺设置失败。');
            }
        }

        return $this->render('setup', [
            'model' => $model,
            'bookingTimes' => $bookingTimes
        ]);
    }

    /**
     * 基本资料的操作
     */
    public function actionProfile() {
        /* @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);
        /* @var $model Business */
        $model = Business::findOne($admin->business_id);

        if ($model->load(Yii::$app->request->post())) {
            if ($model->validate()) {
                $imageFile = UploadedFile::getInstance($model, 'image');

                // 通过事务来保存数据
                $transaction = Yii::$app->db->beginTransaction();
                try {
                    if ($imageFile != null) {
                        // 生成一个随机的图片名并保存到数据库
                        $model->pic_url = Yii::$app->security->generateRandomString(10).'.'.$imageFile->extension;
                    }

                    if (!$model->save(false)) {
                        throw new Exception('资料修改失败！');
                    }

                    // 保存上传图片到服务器的指定目录
                    if ($imageFile != null) {
                        $filename = Yii::getAlias(Yii::$app->params['business.logoPath']) . DIRECTORY_SEPARATOR . $model->pic_url;
                        if (!$imageFile->saveAs($filename)) {
                            throw new Exception('logo保存失败！');
                        }
                    }

                    $transaction->commit();
                    Yii::$app->session->setFlash('success', '资料修改成功。');

                    return $this->refresh();
                } catch (Exception $e) {
                    $transaction->rollBack();
                    Yii::$app->session->setFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    /**
     * 店面实景的操作
     */
    public function actionScene() {
        /* @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);

        // 门面
        $frontImages = BusinessScene::find()
            ->where([
                'business_id' => $admin->business_id,
                'type' => 1
            ])
            ->orderBy(['rank' => 'asc'])
            ->all();
        // 大厅
        $foyerImages = BusinessScene::find()
            ->where([
                'business_id' => $admin->business_id,
                'type' => 2
            ])
            ->orderBy(['rank' => 'asc'])
            ->all();
        // 后厨
        $kitchenImages = BusinessScene::find()
            ->where([
                'business_id' => $admin->business_id,
                'type' => 3
            ])
            ->orderBy(['rank' => 'asc'])
            ->all();

        return $this->render('scene', [
            'frontImages' => $frontImages,
            'foyerImages' => $foyerImages,
            'kitchenImages' => $kitchenImages
        ]);
    }
}