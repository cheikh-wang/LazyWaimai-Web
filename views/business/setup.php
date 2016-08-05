<?php

/* @var $this yii\web\View */
/** @var $model Business */
/** @var $bookingTimes array */

use yii\helpers\Html;
use app\models\Business;
use yii\bootstrap\ActiveForm;
use kartik\widgets\SwitchInput;
use app\assets\ClockPickerAsset;
use app\assets\MultiSelectAsset;

ClockPickerAsset::register($this);
MultiSelectAsset::register($this);

$this->registerJsFile('@web/js/setup.js', [
    'depends' => [
        'app\assets\ClockPickerAsset',
        'app\assets\MultiSelectAsset',
    ]
]);

$this->title = '店铺设置';

$html = '';
foreach ($bookingTimes as $item) {
    $html .= "<optgroup label='{$item['group_label']}'>";
    foreach ($item['group_items'] as $time) {
        $html .= "<option value='{$time}'";
        if (strpos($model->booking_times, $time) !== false) {
            $html .= " selected";
        }
        $html .= ">{$time}</option>";
    }
    $html .= "</optgroup>";
}
?>
<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'status')->widget(SwitchInput::classname(), [
                'type' => SwitchInput::CHECKBOX,
            ]); ?>
            <?= $form->field($model, 'bulletin')->textarea(['rows' => '5']) ?>
            <?= $form->field($model, 'opening_time', [
                'inputTemplate' => '{input}<div id="time-rows"></div>'
            ])->hiddenInput() ?>
            <?= $form->field($model, 'booking_times', [
                'inputTemplate' => '{input}<select id="booking-times" class="form-control" multiple="multiple">'.$html.'</select>'
            ])->hiddenInput() ?>
            <?= $form->field($model, 'shipping_fee', [
                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">￥</span>{input}<span class="input-group-addon">.00</span></div>',
            ]) ?>
            <?= $form->field($model, 'package_fee', [
                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">￥</span>{input}<span class="input-group-addon">.00</span></div>',
            ]) ?>
            <?= $form->field($model, 'min_price', [
                'inputTemplate' => '<div class="input-group"><span class="input-group-addon">￥</span>{input}<span class="input-group-addon">.00</span></div>',
            ]) ?>
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-floppy-o"></i> 提交', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>