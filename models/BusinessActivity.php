<?php

namespace app\models;

use Yii;
use yii\db\Query;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;


/**
 * This is the model class for table "{{%business_activity}}".
 *
 * @property integer $id
 * @property integer $business_id
 * @property integer $activity_id
 * @property string $attribute
 * @property integer $created_at
 * @property integer $updated_at
 */
class BusinessActivity extends ActiveRecord {

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%business_activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules() {
        return [
            [['business_id', 'activity_id', 'attribute'], 'required'],
            [['business_id', 'activity_id', 'created_at', 'updated_at'], 'integer'],
            [['attribute'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '主键ID',
            'business_id' => '商户ID',
            'activity_id' => '活动ID',
            'attribute' => '解析description所用的json数据',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }

    public static function activities($businessId) {
        $query = new Query();
        return $query->select([
            'a.id',
            'b.name',
            'b.description',
            'a.attribute',
            'b.icon_name',
            'b.icon_color',
            'b.code',
            'b.is_share',
            'b.priority'
        ])
            ->from('business_activity a')
            ->innerJoin('activity b', 'a.activity_id=b.id')
            ->where(['a.business_id' => $businessId]);
    }
}