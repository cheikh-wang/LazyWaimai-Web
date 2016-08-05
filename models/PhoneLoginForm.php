<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * 使用手机号快捷登录的表单
 */
class PhoneLoginForm extends Model {

    public $phone;
    public $code;
    public $remember;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['phone', 'code'], 'required'],
            ['code', 'validateCode'],
            ['remember', 'boolean'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'phone' => '手机号',
            'code' => '验证码',
            'remember' => '记住我1周',
        ];
    }

    /**
     * Validates the code.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateCode($attribute, $params) {
        if (!$this->hasErrors()) {
            $session = Yii::$app->session;
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, '不存在该手机号！');
            } else if ($session['loginVerifyCode'] != $this->code) {
                $this->addError($attribute, '验证码不正确！');
            }
        }
    }

    /**
     * Logs in a admin using the provided username and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login() {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->remember ? 3600 * 24 * 7 : 0);
        } else {
            return false;
        }
    }

    /**
     * Finds user by [[username]]
     *
     * @return Admin|null
     */
    public function getUser() {
        if ($this->_user === false) {
            $this->_user = Admin::findOne(['phone' => $this->phone]);
        }

        return $this->_user;
    }
}