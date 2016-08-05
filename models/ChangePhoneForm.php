<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * 修改手机号的验证码
 */
class ChangePhoneForm extends Model {

    public $phone;
    public $verifyCode;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['phone', 'verifyCode'], 'trim'],
            [['phone', 'verifyCode'], 'required'],
            
            [['phone'], 'match', 'pattern' => '/^1[3|5|7|8|][0-9]{9}$/'],
            [['phone'], 'unique', 'targetClass' => '\backend\models\Admin', 'message' => '该手机号已被注册！'],
            [['phone'], function ($attribute, $params) {
                $session = Yii::$app->session;
                if ($session->has('changePhoneSendPhone') && $session['changePhoneSendPhone'] !== $this->phone) {
                    $this->addError($attribute, '该手机号与上次不匹配！');
                }
            }],
            
            [['verifyCode'], 'string', 'length' => 6],
            [['verifyCode'], function ($attribute, $params) {
                $session = Yii::$app->session;
                if (!$session->has('changePhoneSendPhone') || !$session->has('changePhoneVerifyCode')) {
                    $this->addError($attribute, '请您发送验证码！');
                    return;
                }
                if ($session['changePhoneVerifyCode'] !== $this->verifyCode) {
                    $this->addError($attribute, '验证码不匹配！');
                }
            }],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'phone' => '新手机号',
            'verifyCode' => '验证码',
        ];
    }

    /**
     * 执行修改手机号的错作
     * @param bool $runValidation
     * @return bool
     */
    public function change($runValidation = true) {
        if ($runValidation && !$this->validate()) {
            return false;
        }

        /** @var $admin Admin */
        $admin = Yii::$app->user->identity;
        $admin->phone = $this->phone;
    
        return $admin->save(false);
    }
}
