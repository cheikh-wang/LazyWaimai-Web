<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property integer $id
 * @property string $username
 * @property string $access_token
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $mobile
 * @property string $email
 * @property string $avatar_url
 * @property integer $last_address_id
 * @property string $last_ip
 * @property string $last_device_type
 * @property string $last_device_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class User extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['password_hash', 'mobile'], 'required'],
            [['last_address_id', 'created_at', 'updated_at'], 'integer'],
            [['username', 'mobile', 'last_ip', 'last_device_type', 'last_device_id'], 'string', 'max' => 20],
            [['access_token', 'password_hash', 'password_reset_token'], 'string', 'max' => 255],
            [['email'], 'string', 'max' => 50],
            [['avatar_url'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID',
            'username' => '用户名',
            'access_token' => '身份标识',
            'password_hash' => '密码hash 值',
            'password_reset_token' => '重置密码的标识',
            'mobile' => '手机号',
            'email' => '邮箱',
            'avatar_url' => '头像URL',
            'last_address_id' => '最近一次使用的地址ID',
            'last_ip' => '最近一次登录的IP',
            'last_device_type' => '最近一次登录的设备类型',
            'last_device_id' => '最近一次登录的设备ID',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}
