<?php

namespace app\widgets;

use Yii;
use yii\base\Exception;
use yii\base\Widget;
use yii\helpers\Json;
use yii\helpers\Url;
use app\components\helpers\UrlHelper;
use app\models\BusinessScene;
use app\assets\GalleryManagerAsset;

/**
 * Widget to manage gallery.
 * Requires Twitter Bootstrap styles to work.
 */
class GalleryManager extends Widget {

    /** @var array */
    public $images;

    /** @var int */
    public $type = BusinessScene::TYPE_FOYER;

    /** @var string Route to gallery controller */
    public $apiRoute = false;

    public $options = array();

    /** Render widget */
    public function run() {
        if ($this->apiRoute === null) {
            throw new Exception('$apiRoute must be set.', 500);
        }

        $images = [];
        foreach ($this->images as $image) {
            /** @var $image BusinessScene */
            $images[] = array(
                'id' => $image->id,
                'original_url' => UrlHelper::toBusinessScene($image->original_name),
                'thumb_url' => UrlHelper::toBusinessScene($image->thumb_name),
                'rank' => $image->rank
            );
        }

        $baseUrl = [
            $this->apiRoute,
            'type' => $this->type
        ];

        $opts = array(
            'uploadUrl' => Url::to($baseUrl + ['action' => 'ajaxUpload']),
            'deleteUrl' => Url::to($baseUrl + ['action' => 'delete']),
            'arrangeUrl' => Url::to($baseUrl + ['action' => 'order']),
            'photos' => $images,
        );

        $opts = Json::encode($opts);
        $view = $this->getView();
        GalleryManagerAsset::register($view);
        $view->registerJs("$('#{$this->id}').galleryManager({$opts});");

        $this->options['id'] = $this->id;
        $this->options['class'] = 'gallery-manager';

        return $this->render('@app/views/galleryManager', [
            'options' => $this->options
        ]);
    }
}
