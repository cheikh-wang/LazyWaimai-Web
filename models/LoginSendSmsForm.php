<?php

namespace app\models;

use Yii;
use yii\base\Model;
use app\components\Ucpaas;

class LoginSendSmsForm extends Model {

    public $phone;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'match', 'pattern' => '/^1[3|5|7|8|][0-9]{9}$/'],
            // 验证手机号是否存在
            ['phone', function ($attribute, $params) {
                $admin = Admin::findOne(['phone' => $this->phone]);
                if (!$admin) {
                    $this->addError($attribute, '不存在该手机号！');
                }
            }],
            // 验证获取验证码的频率
            ['phone', function ($attribute, $params) {
                $session = Yii::$app->session;
                if ($session->has('loginNextSendTime') && $session['loginNextSendTime'] > time()) {
                    $this->addError($attribute, '发送验证码过于频繁。');
                }
            }]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'phone' => '手机号',
        ];
    }

    /**
     * 发送短信验证码
     * @param bool $runValidation
     * @return bool
     */
    public function sendSms($runValidation = true) {

        if ($runValidation && !$this->validate()) {
            return false;
        }

        $verifyCode = (string) mt_rand(100000, 999999);
        $validMinutes = 30;

        // 调用云之讯组件发送模板短信
        /** @var $ucpass Ucpaas */
        $ucpass = Yii::$app->ucpass;
        $ucpass->templateSMS($this->phone, $verifyCode.','.$validMinutes);

        if ($ucpass->state == Ucpaas::STATUS_SUCCESS) {
            $session = Yii::$app->session;
            $session['loginNextSendTime'] = time() + 60;
            $session['loginSendPhone'] = $this->phone;
            $session['loginVerifyCode'] = $verifyCode;

            return true;
        } else {
            $this->addError('phone', $ucpass->message);

            return false;
        }
    }
}