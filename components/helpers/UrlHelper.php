<?php

namespace app\components\helpers;

use Yii;
use yii\helpers\Url;

class UrlHelper extends Url {

    /**
     * 获取完整的商品图片地址
     * @param string $imageName
     * @return string
     */
    public static function toProductImage($imageName) {
        $baseUrl = Yii::getAlias('@web') . '/../../upload';
        if (empty($imageName)) {
            return $baseUrl.'/image/product/no_pic.jpg';
        }
        return $baseUrl.'/image/product/'.$imageName;
    }

    /**
     * 获取完整的商家店面图片地址
     * @param string $imageName
     * @return string
     */
    public static function toBusinessScene($imageName) {
        $baseUrl = Yii::getAlias(Yii::$app->params['imageBaseUrl']);

        return $baseUrl.'/upload/image/business/'.$imageName;
    }

    /**
     * 获取完整的商家logo图片地址
     * @param string $imageName
     * @return string
     */
    public static function toBusinessLogo($imageName) {
        $baseUrl = Yii::getAlias(Yii::$app->params['imageBaseUrl']);

        return $baseUrl.'/upload/image/business/'.$imageName;
    }
}