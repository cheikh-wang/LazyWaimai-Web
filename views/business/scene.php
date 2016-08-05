<?php

/* @var $this yii\web\View */
/* @var $frontImages array */
/* @var $foyerImages array */
/* @var $kitchenImages array */

use app\widgets\GalleryManager;
use app\models\BusinessScene;

$this->title = '店面实景';

?>
<div class="panel panel-default hidden-xs">
    <div class="panel-heading">门面图</div>
    <div>
        <?= GalleryManager::widget([
            'images' => $frontImages,
            'type' => BusinessScene::TYPE_FRONT,
            'apiRoute' => 'business/gallery'
        ]) ?>
    </div>
</div>
<div class="panel panel-default hidden-xs">
    <div class="panel-heading">大堂图</div>
    <div>
        <?= GalleryManager::widget([
            'images' => $foyerImages,
            'type' => BusinessScene::TYPE_FOYER,
            'apiRoute' => 'business/gallery'
        ]) ?>
    </div>
</div>
<div class="panel panel-default hidden-xs">
    <div class="panel-heading">后厨图</div>
    <div>
        <?= GalleryManager::widget([
            'images' => $kitchenImages,
            'type' => BusinessScene::TYPE_KITCHEN,
            'apiRoute' => 'business/gallery'
        ]) ?>
    </div>
</div>