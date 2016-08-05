<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "{{%activity}}".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $icon_name
 * @property string $icon_color
 * @property string $code
 * @property integer $is_share
 * @property integer $priority
 * @property integer $created_at
 * @property integer $updated_at
 */
class Activity extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%activity}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'description', 'icon_name', 'icon_color', 'code', 'is_share', 'priority'], 'required'],
            [['is_share', 'priority', 'created_at', 'updated_at'], 'integer'],
            [['name', 'code'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 100],
            [['icon_name', 'icon_color'], 'string', 'max' => 10],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => '主键ID',
            'name' => '活动的名称',
            'description' => '活动的描述',
            'icon_name' => '活动图标的文字',
            'icon_color' => '活动图标的颜色',
            'code' => '逻辑code',
            'is_share' => '是否和其他活动共享',
            'priority' => '活动的优先级',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }
}
