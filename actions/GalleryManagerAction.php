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

class GalleryManagerAction extends Action {

    public function run($action) {
        switch ($action) {
            case 'delete':
                return $this->actionDelete(Yii::$app->request->post('id'));
                break;
            case 'ajaxUpload':
                return $this->actionAjaxUpload(Yii::$app->request->get('type'));
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
     * @return string
     * @throws Exception
     */
    protected function actionDelete($id) {
        /** @var $scene BusinessScene */
        $scene = BusinessScene::findOne($id);
        if (!$scene) {
            throw new Exception('未找到改资源！');
        }

        // 删除原图
        Yii::$app->qiniu->delete($scene->original_name);
        // 删除缩略图
        Yii::$app->qiniu->delete($scene->thumb_name);

        // 删除数据库文件记录
        $scene->delete();

        return 'OK';
    }

    /**
     * Method to handle file upload thought XHR2
     * On success returns JSON object with image info.
     *
     * @param $type
     * @return string
     * @throws Exception
     */
    public function actionAjaxUpload($type) {
        $imageFile = UploadedFile::getInstanceByName('image');

        // 生成原图名称和缩略图名称
        $randomStr = Yii::$app->security->generateRandomString(20);
        $originalName = $randomStr . '.' . $imageFile->extension;
        $thumbName = $randomStr . '_145x145.' . $imageFile->extension;

        $originalImage = Image::getImagine()->open($imageFile->tempName);
        $basePath = Yii::getAlias(Yii::$app->params['business.scenePath']);

        // 临时保存原图
        $originalPath = $basePath.DIRECTORY_SEPARATOR.$originalName;
        if (!$originalImage->save($originalPath)) {
            throw new Exception('保存图片失败！');
        }

        // 临时保存缩略图
        $thumbPath = $basePath.DIRECTORY_SEPARATOR.$thumbName;
        if (!$originalImage->copy()->thumbnail(new Box(145, 145))->save($thumbPath)) {
            throw new Exception('保存图片失败！');
        }

        // 上传原图和缩略图到七牛
        $originalUrl = Yii::$app->qiniu->uploadFile($originalPath, $originalName);
        $thumbUrl = Yii::$app->qiniu->uploadFile($thumbPath, $thumbName);

        // 删除临时的图片文件
        if (file_exists($originalPath)) {
            @unlink($originalPath);
        }
        if (file_exists($thumbPath)) {
            @unlink($thumbPath);
        }

        /* @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);

        // 保存信息到数据库
        $model = new BusinessScene();
        $model->business_id = $admin->business_id;
        $model->type = $type;
        $model->rank = 1;
        $model->original_name = $originalName;
        $model->original_url = $originalUrl;
        $model->thumb_name = $thumbName;
        $model->thumb_url = $thumbUrl;
        if (!$model->save(false)) {
            throw new Exception('保存图片记录失败！');
        }

        Yii::$app->response->headers->set('Content-Type', 'text/html');

        return Json::encode([
            'id' => $model->id,
            'original_url' => $originalUrl,
            'thumb_url' => $thumbUrl,
            'rank' => $model->rank
        ]);
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