<?php

/** @var $model app\models\Business */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use kartik\widgets\FileInput;

$this->title = '商家资料';

?>
<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
        ]); ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'phone') ?>
            <?php if (empty($model->pic_url)) :?>
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
                    'initialPreview' => Html::img($model->pic_url, ['class' => 'file-preview-image'])
                ],
            ]) ?>
            <?php endif;?>
            <?= $form->field($model, 'address')->textarea(['rows' => '3']) ?>
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-floppy-o"></i> 修改', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>