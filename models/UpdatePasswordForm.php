<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * 修改密码的表单
 */
class UpdatePasswordForm extends Model {

    public $old_password;
    public $new_password;
    public $repeat_password;

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['old_password', 'required', 'message' => '原始密码不能为空'],
            ['new_password', 'required', 'message' => '新的密码不能为空'],
            ['repeat_password', 'required', 'message' => '确认密码不能为空'],
            ['repeat_password', 'compare', 'compareAttribute' => 'new_password', 'message' => '两次密码输入不一致'],
            ['old_password', 'validateOldPassword'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'old_password' => '原始密码',
            'new_password' => '新的密码',
            'repeat_password' => '确认密码',
        ];
    }

    /**
     * Validates the old password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validateOldPassword($attribute, $params) {
        if (!$this->hasErrors()) {
            /** @var $admin Admin */
            $admin = Admin::findOne(Yii::$app->user->id);
            if (!$admin->validatePassword($this->old_password)) {
                $this->addError($attribute, '原始密码输入错误');
            }
        }
    }

    /**
     * 修改新密码
     * @return bool
     */
    public function updatePassword() {
        if ($this->validate()) {
            /* @var $admin Admin */
            $admin = Admin::findOne(Yii::$app->user->id);
            $admin->setPassword($this->new_password);
            return $admin->save();
        } else {
            return false;
        }
    }
}