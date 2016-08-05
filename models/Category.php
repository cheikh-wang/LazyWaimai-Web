<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "category".
 *
 * @property integer $id
 * @property integer $business_id
 * @property string $name
 * @property string $description
 * @property string $icon_url
 * @property integer $created_at
 * @property integer $updated_at
 */
class Category extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%category}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['business_id', 'name'], 'required'],
            [['business_id', 'created_at', 'updated_at'], 'integer'],
            [['name'], 'string', 'max' => 30],
            [['description'], 'string', 'max' => 50],
            [['icon_url'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'business_id' => 'Business ID',
            'name' => '分类名',
            'description' => '描述',
            'icon_url' => '图标url',
            'created_at' => '创建时间',
            'updated_at' => '最近更新时间',
        ];
    }

    /**
     * 获取指定商家的所有商品分类（id name）键值对
     * @return array
     */
    public static function getKeyValuePairs() {
        /* @var $admin Admin */
        $admin = Admin::findOne(Yii::$app->user->id);

        $sql = "SELECT `id`, `name` FROM ".self::tableName()." WHERE `business_id`=".$admin->business_id." ORDER BY `id` ASC";

        return Yii::$app->db->createCommand($sql)->queryAll(\PDO::FETCH_KEY_PAIR);
    }
}
