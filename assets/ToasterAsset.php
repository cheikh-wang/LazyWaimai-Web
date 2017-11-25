<?php

namespace app\assets;

use Yii;
use yii\web\AssetBundle;

class ToasterAsset extends AssetBundle {

    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @vendor alias used.
    public $sourcePath = '@bower/jquery.toaster';
    public $js = [
        'jquery.toaster.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}
