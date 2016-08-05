<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use app\models\VerifyPasswordForm;
use app\models\ChangePhoneForm;
use app\assets\CountDownAsset;

/** @var $step string */
/** @var $phone string */
/** @var $verifyPasswordForm VerifyPasswordForm */
/** @var $changeMobileForm ChangePhoneForm */

CountDownAsset::register($this);

$this->registerJsFile('@web/js/update-phone.js', [
    'depends' => [
        'app\assets\CountDownAsset',
    ]
]);

$this->title = '修改手机号';

?>
<div class="row">
    <div class="col-lg-6">
        <?php if ($step === '1') :?>
            <div class="edit-form">
                <div class="callout callout-info">
                    <p>当前手机号：<?= $phone ?>，如需更换，请输入登录密码进行下一步。</p>
                </div>
                <?php $form = ActiveForm::begin() ?>
                <?= $form->field($verifyPasswordForm, 'password')->passwordInput() ?>
                <div class="form-group">
                    <?= Html::submitButton('下一步 <i class="fa fa-angle-double-right"></i>', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end()?>
            </div>
        <?php elseif ($step === '2') :?>
            <div class="edit-form">
                <?php $form = ActiveForm::begin() ?>
                <div class="alert alert-success alert-msg-ok hidden" role="alert"></div>
                <div class="alert alert-danger alert-msg-err hidden" role="alert"></div>
                <?= $form->field($changeMobileForm, 'phone') ?>
                <?= $form->field($changeMobileForm, 'verifyCode', [
                    'inputTemplate' => '<div class="input-group">{input}<span class="input-group-btn"><button id="send-sms-btn" class="btn btn-default" type="button" data-url="'.Url::to(['user/send-update-phone-sms']).'">发送验证码</button></span></div>'
                ]) ?>
                <div class="form-group">
                    <?= Html::submitButton('<i class="fa fa-upload"></i> 提交', ['class' => 'btn btn-primary']) ?>
                </div>
                <?php ActiveForm::end()?>
            </div>
        <?php endif ?>
    </div>
</div>