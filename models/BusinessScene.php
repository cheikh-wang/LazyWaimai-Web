<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "business_scene".
 *
 * @property integer $id
 * @property integer $business_id
 * @property integer $type
 * @property string $original_name
 * @property string $thumb_name
 * @property integer $rank
 * @property integer $created_at
 * @property integer $updated_at
 */
class BusinessScene extends ActiveRecord {

    const TYPE_FRONT = 1;
    const TYPE_FOYER = 2;
    const TYPE_KITCHEN = 3;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%business_scene}}';
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
            [['business_id', 'type', 'original_name', 'thumb_name', 'rank'], 'required'],
            [['business_id', 'type', 'rank', 'created_at', 'updated_at'], 'integer'],
            [['original_name', 'thumb_name'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => 'ID',
            'business_id' => 'Business ID',
            'type' => 'Type',
            'original_name' => 'Original Name',
            'thumb_name' => 'Thumb Name',
            'rank' => 'Rank',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
