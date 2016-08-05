<?php

namespace app\actions;

use Yii;
use Exception;
use yii\base\Action;
use yii\helpers\Json;
use yii\web\HttpException;
use yii\web\UploadedFile;
use app\models\BusinessScene;
use Imagine\Image\Box;
use yii\imagine\Image;
use app\models\Admin;
use app\components\helpers\UrlHelper;

class GalleryManagerAction extends Action {

    public function run($action) {
        switch ($action) {
            case 'delete':
                return $this->actionDelete(Yii::$app->request->post('id'));
                break;
            case 'ajaxUpload':
                $type = Yii::$app->request->get('type');
                return $this->actionAjaxUpload($type);
                break;
            case 'order':
                return $this->actionOrder(Yii::$app->request->post('order'));
                break;
            default:
                throw new HttpException(400, 'Action do not exists');
                break;
        }
    }

    /**
     * Removes image with ids specified in post request.
     * On success returns 'OK'
     *
     * @param $id
     *
     * @throws HttpException
     * @return string
     */
    protected function actionDelete($id) {
        $scenes = BusinessScene::findAll($id);
        foreach ($scenes as $scene) {
            /** @var $scene BusinessScene */
            $basePath = Yii::getAlias(Yii::$app->params['business.scenePath']);
            $originalPath = $basePath.DIRECTORY_SEPARATOR.$scene->original_name;
            $thumbPath = $basePath.DIRECTORY_SEPARATOR.$scene->thumb_name;

            // 删除本地图片文件
            if (file_exists($originalPath)) {
                @unlink($originalPath);
            }
            if (file_exists($thumbPath)) {
                @unlink($thumbPath);
            }
            // 删除数据库文件记录
            $scene->delete();
        }

        return 'OK';
    }

    /**
     * Method to handle file upload thought XHR2
     * On success returns JSON object with image info.
     *
     * @param $type
     * @return string
     * @throws \yii\db\Exception
     */
    public function actionAjaxUpload($type) {
        $imageFile = UploadedFile::getInstanceByName('image');

        /* @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);

        // 通过事务来保存数据
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $model = new BusinessScene();
            $model->business_id = $admin->business_id;
            $model->type = $type;
            $model->rank = 1;
            $model->original_name = Yii::$app->security->generateRandomString(20).'.'.$imageFile->extension;
            $model->thumb_name = Yii::$app->security->generateRandomString(20).'_145x145.'.$imageFile->extension;
            if (!$model->save(false)) {
                throw new Exception('保存图片记录失败！');
            }

            // 保存上传的图片到服务器
            $originalImage = Image::getImagine()->open($imageFile->tempName);

            $basePath = Yii::getAlias(Yii::$app->params['business.scenePath']);

            // 原图
            $originalPath = $basePath.DIRECTORY_SEPARATOR.$model->original_name;
            if (!$originalImage->save($originalPath)) {
                throw new Exception('保存图片失败！');
            }
            // 缩略图
            $thumbPath = $basePath.DIRECTORY_SEPARATOR.$model->thumb_name;
            if (!$originalImage->copy()->thumbnail(new Box(145, 145))->save($thumbPath)) {
                throw new Exception('保存图片失败！');
            }
            $transaction->commit();

            // not "application/json", because  IE8 trying to save response as a file
            Yii::$app->response->headers->set('Content-Type', 'text/html');

            return Json::encode([
                'id' => $model->id,
                'original_url' => UrlHelper::toBusinessScene($model->original_name),
                'thumb_url' => UrlHelper::toBusinessScene($model->thumb_name),
                'rank' => $model->rank
            ]);
        } catch (Exception $e) {
            $transaction->rollBack();

            return null;
        }
    }

    /**
     * Saves images order according to request.
     *
     * @param array $order new arrange of image ids, to be saved
     *
     * @return string
     * @throws HttpException
     */
    public function actionOrder($order) {
        if (count($order) == 0) {
            throw new HttpException(400, 'No data, to save');
        }
        $orders = [];
        $i = 0;
        foreach ($order as $k => $v) {
            if (!$v) {
                $order[$k] = $k;
            }
            $orders[] = $order[$k];
            $i++;
        }
        sort($orders);
        $i = 0;
        $res = [];
        foreach ($order as $k => $v) {
            $res[$k] = $orders[$i];
            // TODO 更新 rank
//            \Yii::$app->db->createCommand()
//                ->update(
//                    $this->tableName,
//                    ['rank' => $orders[$i]],
//                    ['id' => $k]
//                )->execute();

            $i++;
        }

        return Json::encode($order);
    }
}