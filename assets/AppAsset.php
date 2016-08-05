<?php

namespace app\assets;

use yii\web\AssetBundle;


class AppAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $js = [
        'js/site.js'
    ];
    public $depends = [
        'app\assets\BaseAsset',
        'app\assets\MetisMenuAsset'
    ];
}