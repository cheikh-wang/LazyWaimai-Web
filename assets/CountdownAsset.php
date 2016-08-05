<?php

namespace app\assets;

use yii\web\AssetBundle;

class CountDownAsset extends AssetBundle
{
    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @vendor alias used.
    public $sourcePath = '@vendor/countdown/dist';
    public $js = [
        'jquery.countdown.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset'
    ];
}