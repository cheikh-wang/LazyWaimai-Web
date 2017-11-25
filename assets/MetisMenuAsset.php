<?php

namespace app\assets;

use yii\web\AssetBundle;

class MetisMenuAsset extends AssetBundle {

    public $sourcePath = '@bower/metismenu/dist';
    public $css = [
        'metisMenu.min.css',
    ];
    public $js = [
        'metisMenu.min.js'
    ];
    public $depends = [
        'yii\bootstrap\BootstrapPluginAsset'
    ];
}