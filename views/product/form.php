<?php

/** @var $model app\models\Product */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\select2\Select2;
use kartik\file\FileInput;
use app\models\Category;

$this->title = $model->isNewRecord ? '添加商品' : '更新商品';

?>
<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'description')->textarea() ?>
            <?= $form->field($model, 'category_id')->widget(Select2::className(), [
                'data' => Category::getKeyValuePairs(),
                'options' => ['placeholder' => '请选择分类'],
                'pluginOptions' => [
                    'allowClear' => true
                ],
            ]) ?>
            <?php if ($model->isNewRecord) :?>
                <?= $form->field($model, 'image')->widget(FileInput::className(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'browseLabel' => '选择图片',
                        'removeLabel' => '删除'
                    ],
                ]) ?>
            <?php else :?>
                <?= $form->field($model, 'image')->widget(FileInput::className(), [
                    'options' => ['accept' => 'image/*'],
                    'pluginOptions' => [
                        'showUpload' => false,
                        'browseLabel' => '选择图片',
                        'removeLabel' => '删除',
                        'initialPreview' => Html::img($model->image_path, ['class' => 'file-preview-image'])
                    ],
                ]) ?>
            <?php endif;?>
            <?= $form->field($model, 'price') ?>
            <?= $form->field($model, 'left_num') ?>
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-floppy-o"></i> 保存', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>