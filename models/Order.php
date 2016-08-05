<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "{{%order}}".
 *
 * @property integer $id
 * @property integer $cart_id
 * @property integer $business_id
 * @property integer $user_id
 * @property string $order_num
 * @property integer $status
 * @property double $origin_price
 * @property double $discount_price
 * @property double $total_price
 * @property string $consignee
 * @property string $phone
 * @property string $address
 * @property string $pay_method
 * @property string $remark
 * @property string $booked_at
 * @property integer $created_at
 * @property integer $updated_at
 */
class Order extends ActiveRecord {

    const STATUS_WAIT_SUBMIT = -1;  // 待提交，默认状态
    const STATUS_WAIT_PAYMENT = 0;  // 待支付
    const STATUS_WAIT_ACCEPT = 1;   // 待接单
    const STATUS_WAIT_SEND = 2;     // 待发货
    const STATUS_WAIT_ARRIVE = 3;   // 待送达
    const STATUS_WAIT_CONFIRM = 4;  // 待确认
    const STATUS_FINISHED = 5;      // 已完成

    const PAYMENT_ONLINE = 1;
    const PAYMENT_OFFLINE = 0;

    private static $_payMethodtList;
    private static $_statusList;

    /**
     * @inheritdoc
     */
    public static function tableName() {
        return '{{%order}}';
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
            [['cart_id', 'business_id', 'user_id', 'order_num', 'status', 'origin_price', 'discount_price', 'total_price', 'consignee', 'phone', 'address', 'pay_method'], 'required'],
            [['cart_id', 'business_id', 'user_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['origin_price', 'discount_price', 'total_price'], 'number'],
            [['pay_method'], 'string'],
            [['order_num'], 'string', 'max' => 50],
            [['consignee', 'phone', 'booked_at'], 'string', 'max' => 20],
            [['address'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels() {
        return [
            'id' => '订单ID',
            'cart_id' => '购物车ID',
            'business_id' => '商铺ID',
            'user_id' => '用户ID',
            'order_num' => '订单编号',
            'status' => '订单状态',
            'origin_price' => '商品原价',
            'discount_price' => '优惠价格',
            'total_price' => '合计价格',
            'consignee' => '联系人',
            'phone' => '联系电话',
            'address' => '收货地址',
            'pay_method' => '支付方式',
            'remark' => '备注',
            'booked_at' => '预订时间',
            'created_at' => '下单时间',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getPayMethodList() {
        if (self::$_payMethodtList === null) {
            self::$_payMethodtList = [
                self::PAYMENT_ONLINE => '在线支付',
                self::PAYMENT_OFFLINE => '货到付款'
            ];
        }

        return self::$_payMethodtList;
    }

    public static function getStatusList() {
        if (self::$_statusList === null) {
            self::$_statusList = [
                self::STATUS_WAIT_ACCEPT => '待接单',
                self::STATUS_WAIT_SEND => '待配送',
                self::STATUS_FINISHED => '已完成',
            ];
        }

        return self::$_statusList;
    }
}