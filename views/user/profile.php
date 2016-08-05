<?php

/** @var $model Admin */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\Admin;

$this->title = '个人资料';

?>
<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'user_name')->textInput(['disabled' => true]) ?>
            <?= $form->field($model, 'real_name')->textInput(['disabled' => true]) ?>
            <?= $form->field($model, 'identity_num')->textInput(['disabled' => true]) ?>
            <?= $form->field($model, 'phone')->textInput(['disabled' => true]) ?>
            <?= $form->field($model, 'email')->textInput(['disabled' => true]) ?>
            <?= $form->field($model, 'gender')->dropDownList(Admin::getGenderList()) ?>
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-floppy-o"></i> 保存', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>