<?php

use yii\helpers\Url;
use yii\helpers\Html;
use app\models\PhoneLoginForm;
use app\models\AccountLoginForm;
use app\assets\BaseAsset;
use app\assets\CountDownAsset;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $phoneLoginModel PhoneLoginForm */
/* @var $accountLoginModel AccountLoginForm */

CountDownAsset::register($this);

BaseAsset::addCss($this, '@web/css/login.css');
$this->registerJsFile('@web/js/login.js', [
    'depends' => [
        'app\assets\CountDownAsset',
    ]
]);

$this->title = '登录';

?>
<div id="login-box">
    <div class="logo">
        <?= Html::img('@web/images/logo.png') ?>
    </div>
    <div class="border">
        <!-- 手机号登录表单 开始 -->
        <form id="mobile-login-form" role="form">
            <div class="form-group form-group-lg">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-phone"></i>
                    </span>
                    <input class="form-control" type="text" name="phone" placeholder="请输入手机号">
                </div>
            </div>
            <div class="form-group form-group-lg">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock"></i>
                    </span>
                    <input class="form-control" type="text" name="code" placeholder="请输入验证码">
                    <span class="input-group-btn">
                        <button class="btn btn-default btn-lg" type="button" name="send-sms-btn" data-url="<?= Url::to(['user/send-login-sms']) ?>">发送验证码</button>
                    </span>
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input type="checkbox" name="remember">
                        记住我1周
                    </label>
                </div>
            </div>
            <div class="form-group form-group-lg">
                <button class="btn btn-default btn-lg btn-block" type="button" name="login-btn" data-url="<?= Url::to(['user/phone-login']) ?>">登录</button>
            </div>
            <div class="form-actions">
                <span class="pull-right">
                    <a href="#" class="flip-link" id="to-account-login">使用用户名登录</a>
                </span>
            </div>
        </form>
        <!-- 手机号登录表单 结束 -->

        <!-- 帐号登录表单 开始 -->
        <form id="account-login-form" role="form">
            <div class="form-group form-group-lg">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-user"></i>
                    </span>
                    <input class="form-control" type="text" name="username" placeholder="请输入帐号" >
                </div>
            </div>
            <div class="form-group form-group-lg">
                <div class="input-group">
                    <span class="input-group-addon">
                        <i class="glyphicon glyphicon-lock"></i>
                    </span>
                    <input class="form-control" type="password" name="password" placeholder="请输入密码">
                </div>
            </div>
            <div class="form-group">
                <div class="checkbox">
                    <label>
                        <input name="remember" type="checkbox">
                        记住我1周
                    </label>
                </div>
            </div>
            <div class="form-group form-group-lg">
                <button class="btn btn-default btn-lg btn-block" type="button" name="login-btn" data-url="<?= Url::to(['user/account-login']) ?>">登录</button>
            </div>
            <div class="form-actions">
                <span class="pull-left"><a href="#" class="flip-link" id="to-mobile-login">&lt; 使用手机号登录</a></span>
            </div>
        </form>
        <!-- 账户登录表单 结束 -->
    </div>
</div>
