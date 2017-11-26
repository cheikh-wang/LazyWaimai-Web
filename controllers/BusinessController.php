<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\UploadedFile;
use app\models\Admin;
use app\models\Business;
use app\models\BusinessScene;
use yii\filters\AccessControl;
use app\actions\GalleryAction;

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
                'class' => GalleryAction::className(),
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

            $model->image = UploadedFile::getInstance($model, 'image');

            if ($model->validate()) {
                if ($model->image != null) {
                    // 随机生成图片名
                    $filename = Yii::$app->security->generateRandomString(10).'.'.$model->image->extension;
                    // 使用七牛上传图片
                    $model->pic_url = Yii::$app->qiniu->uploadFile($model->image->tempName, $filename);
                }

                if (!$model->save(false)) {
                    Yii::$app->session->setFlash('danger', '资料修改失败');
                }

                Yii::$app->session->setFlash('success', '资料修改成功。');

                return $this->refresh();
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
                'type' => BusinessScene::TYPE_FRONT
            ])
            ->orderBy(['rank' => 'asc'])
            ->all();
        // 大厅
        $foyerImages = BusinessScene::find()
            ->where([
                'business_id' => $admin->business_id,
                'type' => BusinessScene::TYPE_FOYER
            ])
            ->orderBy(['rank' => 'asc'])
            ->all();
        // 后厨
        $kitchenImages = BusinessScene::find()
            ->where([
                'business_id' => $admin->business_id,
                'type' => BusinessScene::TYPE_KITCHEN
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