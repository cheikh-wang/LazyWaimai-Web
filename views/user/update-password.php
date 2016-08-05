<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use app\models\UpdatePasswordForm;

/** @var $model UpdatePasswordForm */

$this->title = '修改密码';

?>
<div class="row">
    <div class="col-lg-6">
        <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'old_password')->passwordInput() ?>
            <?= $form->field($model, 'new_password')->passwordInput() ?>
            <?= $form->field($model, 'repeat_password')->passwordInput() ?>
            <div class="form-group">
                <?= Html::submitButton('<i class="fa fa-floppy-o"></i> 确认修改', ['class' => 'btn btn-primary']) ?>
            </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>