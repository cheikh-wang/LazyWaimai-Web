<?php

namespace app\assets;

use yii\web\AssetBundle;

class MultiSelectAsset extends AssetBundle
{
    // The files are not web directory accessible, therefore we need
    // to specify the sourcePath property. Notice the @vendor alias used.
    public $sourcePath = '@vendor/multiselect/dist';
    public $css = [
        'css/bootstrap-multiselect.css',
    ];
    public $js = [
        'js/bootstrap-multiselect.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];
}