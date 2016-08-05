<?php

namespace app\assets;

use yii\web\AssetBundle;

class ClockPickerAsset extends AssetBundle
{
    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @vendor alias used.
    public $sourcePath = '@vendor/clockpicker/dist';
    public $css = [
        'bootstrap-clockpicker.css',
    ];
    public $js = [
        'bootstrap-clockpicker.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}