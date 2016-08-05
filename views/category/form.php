<?php

/** @var $model Category */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Category;

$this->title = $model->isNewRecord ? '添加分类' : '更新分类';

?>
<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'description')->textarea() ?>
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-floppy-o"></i> 保存', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>