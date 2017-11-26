<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class GalleryAsset extends AssetBundle {

    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/jquery.iframe-transport.js',
        'js/jquery.gallery.js',
        'js/lightbox.js'
    ];
    public $css = [
        'css/gallery.css',
        'css/lightbox.css'
    ];
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\jui\JuiAsset'
    ];
}
