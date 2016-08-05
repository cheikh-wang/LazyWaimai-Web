<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 *  使用账户登录的表单
 */
class AccountLoginForm extends Model {

    public $username;
    public $password;
    public $remember;

    private $_user = false;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['username', 'password'], 'required'],
            ['password', 'validatePassword'],
            ['remember', 'boolean'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'username' => '用户名',
            'password' => '密码',
            'remember' => '记住我1周',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params) {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, '不存在该用户名.');
            } else if (!$user->validatePassword($this->password)) {
                $this->addError($attribute, ' 密码输入错误.');
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
            $this->_user = Admin::findByUsername($this->username);
        }

        return $this->_user;
    }
}