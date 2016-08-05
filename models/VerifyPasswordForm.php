<?php

namespace app\models;

use yii\base\Model;
use Yii;

/**
 * 验证密码的表单
 */
class VerifyPasswordForm extends Model {

    public $password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['password', 'required'],
            ['password', 'string', 'min' => 6, 'max' => 24],
            ['password', 'match', 'pattern' => '/^\S+$/'],
            ['password', 'validatePassword']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'password' => '登录密码'
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
            /** @var $admin Admin  */
            $admin = Yii::$app->user->identity;
            if (!$admin->validatePassword($this->password)) {
                $this->addError($attribute, '密码验证错误。');
            }
        }
    }
}