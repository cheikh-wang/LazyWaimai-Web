<?php

namespace app\models;

use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * Admin model
 *
 * @property integer $id
 * @property integer $business_id
 * @property string $user_name
 * @property string $real_name
 * @property string $identity_num
 * @property string $auth_key
 * @property string $password_hash
 * @property string $access_token
 * @property string $gender
 * @property string $email
 * @property string $phone
 * @property string $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $statusMsg read-only $statusMsg
 * @property string $genderMsg read-only $genderMsg
 * @property string $password write-only password
 */
class Admin extends ActiveRecord implements IdentityInterface {
    const STATUS_ACTIVE = 'active';
    const STATUS_BLOCKED = 'blocked';
    
    const GENDER_MALE = 'male';
    const GENDER_WOMAN = 'woman';
    const GENDER_OTHER = 'other';
    
    public $password;
    
    private static $_statusList;
    private static $_genderList;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%admin}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className(),
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            ['user_name', 'required'],
            ['user_name', 'filter', 'filter' => 'trim'],
            ['user_name', 'string', 'min' => 4, 'max' => 20],
            ['user_name', 'match', 'pattern' => '/^[A-Za-z_-][A-Za-z0-9_-]+$/'],
            ['user_name', 'unique', 'message' => '该用户名已被使用'],

            ['business_id', 'required'],

            ['real_name', 'filter', 'filter' => 'trim'],
            ['real_name', 'required'],
            ['real_name', 'string', 'min' => 2, 'max' => 20],

            ['identity_num', 'required'],
            ['identity_num', 'string', 'max' => 20],

            ['email', 'filter', 'filter' => 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            
            [['password'], 'required', 'on' => 'insert'],
            [['password'], 'string', 'min' => 6, 'max' => 24],
            [['password'], 'match', 'pattern' => '/^\S+$/'],
            
            ['gender', 'default', 'value' => self::GENDER_MALE],
            ['gender', 'in', 'range' => [self::GENDER_MALE, self::GENDER_WOMAN, self::GENDER_OTHER]],
            
            ['phone', 'required'],
            ['phone', 'match', 'pattern' => '/^1[3|4|5|7|8][0-9]{9}$/'],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'business_id' => '管理的商铺ID',
            'user_name' => '用户名',
            'real_name' => '真实姓名',
            'identity_num' => '身份证号',
            'password' => '密码',
            'gender' => '性别',
            'email' => '邮箱',
            'phone' => '手机',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id) {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null) {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return Admin|null
     */
    public static function findByUsername($username) {
        return static::findOne(['user_name' => $username, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public function getId() {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey() {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey) {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password) {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password) {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey() {
        $this->auth_key = Yii::$app->security->generateRandomString();
    }
    
    public static function getGenderList() {
        if (self::$_genderList === null) {
            self::$_genderList = [
                self::GENDER_MALE => '男',
                self::GENDER_WOMAN => '女',
                self::GENDER_OTHER => '保密'
            ];
        }
        
        return self::$_genderList;
    }
    
    public function getGenderMsg() {
        $list = self::getGenderList();
        
        return isset($list[$this->gender]) ? $list[$this->gender] : null;
    }
    
    public static function getStatusList() {
        if (self::$_statusList === null) {
            self::$_statusList = [
                self::STATUS_ACTIVE => '正常',
                self::STATUS_BLOCKED => '禁用'
            ];
        }
        
        return self::$_statusList;
    }
    
    public function getStatusMsg() {
        $list = self::getStatusList();
    
        return isset($list[$this->status]) ? $list[$this->status] : null;
    }
}