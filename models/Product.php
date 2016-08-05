<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use \yii\db\ActiveQuery;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "product".
 *
 * @property integer $id
 * @property integer $business_id
 * @property integer $category_id
 * @property string $name
 * @property double $price
 * @property string $description
 * @property string $image_path
 * @property integer $month_sales
 * @property integer $rate
 * @property integer $left_num
 * @property integer $created_at
 * @property integer $updated_at
 */
class Product extends ActiveRecord {

    /**
     * @var UploadedFile
     */
    public $image;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%product}}';
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
            [['name', 'category_id', 'business_id', 'price', 'left_num'], 'required'],

            ['image', 'required', 'on' => 'insert'],
            [
                'image',
                'image',
                'extensions' => 'jpg, png, jpeg, gif',
                'mimeTypes' => 'image/jpeg, image/png, image/gif',
                'checkExtensionByMimeType' => false,
                'minSize' => 100,
                'maxSize' => 204800,
                'tooBig' => '{attribute}最大不能超过200KB',
                'tooSmall' => '{attribute}最小不能小于0.1KB',
                'notImage' => '{file} 不是图片文件'
            ],

            [['business_id', 'category_id', 'left_num'], 'integer'],

            [['category_id'], 'exist', 'targetClass' => Category::className(), 'targetAttribute' => 'id'],

            ['price', 'number', 'min' => 0],

            [['name'], 'string', 'max' => 100],
            [['description'], 'string', 'max' => 200],
            [['image_path'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '编号',
            'business_id' => '商家',
            'category_id' => '分类',
            'name' => '商品名',
            'description' => '商品描述',
            'image' => '商品图片',
            'price' => '价格',
            'image_path' => '商品图片',
            'month_sales' => '月销量',
            'rate' => '评价',
            'left_num' => '库存',
            'created_at' => '添加时间',
        ];
    }

    /**
     * @return ActiveQuery
     */
    public function getCategory() {
        return $this->hasOne(Category::className(), ['id' => 'category_id']);
    }
}
