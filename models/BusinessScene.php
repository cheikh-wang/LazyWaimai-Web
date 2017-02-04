<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%business_scene}}".
 *
 * @property integer $id
 * @property integer $business_id
 * @property integer $type
 * @property string $original_name
 * @property string $original_url
 * @property string $thumb_name
 * @property string $thumb_url
 * @property integer $rank
 * @property integer $created_at
 * @property integer $updated_at
 */
class BusinessScene extends ActiveRecord
{

    const TYPE_FRONT = 1;
    const TYPE_FOYER = 2;
    const TYPE_KITCHEN = 3;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%business_scene}}';
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className()
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['business_id', 'type', 'original_name', 'original_url', 'thumb_name', 'thumb_url', 'rank'], 'required'],
            [['business_id', 'type', 'rank', 'created_at', 'updated_at'], 'integer'],
            [['original_name', 'original_url', 'thumb_name', 'thumb_url'], 'string', 'max' => 100],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'business_id' => 'Business ID',
            'type' => '实景类别，1=门面、2=大堂、3=后厨',
            'original_name' => 'Original Name',
            'original_url' => 'Original Url',
            'thumb_name' => 'Thumb Name',
            'thumb_url' => 'Thumb Url',
            'rank' => 'Rank',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}
