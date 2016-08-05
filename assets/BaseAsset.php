<?php

namespace app\assets;

use yii\web\AssetBundle;
use yii\web\View;

class BaseAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/site.css'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset',
        'yii\web\YiiAsset',
        'rmrevin\yii\fontawesome\cdn\AssetBundle',
        'app\assets\ToasterAsset'
    ];

    /**
     * 定义按需加载JS方法，注意加载顺序在最后
     * @param $view View
     * @param $jsfile string
     */
    public static function addScript($view, $jsfile) {
        $view->registerJsFile($jsfile, [BaseAsset::className(), 'depends' => 'app\assets\BaseAsset']);
    }

    /**
     * 定义按需加载css方法，注意加载顺序在最后
     * @param $view View
     * @param $cssfile string
     */
    public static function addCss($view, $cssfile) {
        $view->registerCssFile($cssfile, [BaseAsset::className(), 'depends' => 'app\assets\BaseAsset']);
    }
}
