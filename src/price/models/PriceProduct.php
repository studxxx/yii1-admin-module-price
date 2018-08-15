<?php

/**
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $variant_id
 * @property integer $currency_id
 * @property string $search
 * @property string $sku
 * @property string $brand
 * @property string $name
 * @property string $description
 * @property string $price
 * @property string $final_price
 * @property string $exist - 1 наличие(exist), 0 отстутствует (not exist), 2 под заказ (contract)
 * @property integer $count - кількість
 * @property integer $type - 1 - undefined, 2 - tecdoc, 0 - not found
 * @property integer $visible
 * @property integer $status
 * @property integer $state 1 - new, 2 - second hand
 * @property integer $delivery
 * @property string $note
 * @property string $token
 * @property string $constructions
 * @property string $cross_numbers
 * @property integer $tecdoc_article_id
 * @property string $tecdoc_article_nr
 * @property integer $tecdoc_supplier_id
 * @property string $tecdoc_supplier_brand
 * @property integer $created_at
 * @property integer $updated_at
 *
 * @property PriceSupplier $suppliers
 */
class PriceProduct extends CActiveRecord
{
    const STATE_NEW = 1;
    const STATE_SECOND_HAND = 2;

    const TYPE_NOT_FOUNT = 0;
    const TYPE_UNDEFINED = 1;
    const TYPE_TECDOC = 2;

    const EXIST_AVAILABLE = 1;
    const EXIST_UNAVAILABLE = 0;
    const EXIST_UNDER_ORDER = 2;

    const HIDE = 0;
    const SHOW = 1;

    /**
     * @param string $className
     * @return PriceProduct|CActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function tableName()
    {
        return 'price_products';
    }

    public function rules()
    {
        return [
            ['supplier_id', 'required'],
            ['id, exist, supplier_id, created, updated, visible, type, status, delivery, state, tecdoc_article_id, tecdoc_supplier_id', 'numerical', 'integerOnly' => true],
            ['search, sku, brand, name, price, count, marker, constructions, cross_numbers, marker, tecdoc_article_nr, tecdoc_supplier_brand', 'length', 'max' => 255],
            ['image, note', 'safe'],
            ['search, sku, name, constructions, cross_numbers', 'filter', 'filter' => 'trim'],
            ['id, search, sku, brand, name, price, exist, sid, visible, constructions, cross_numbers', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'suppliers' => [self::BELONGS_TO, 'PriceSupplier', 'supplier_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'search' => PriceModule::t('Search'),
            'brand' => PriceModule::t('Brand'),
            'id' => PriceModule::t('ID'),
            'sku' => PriceModule::t('SKU'),
            'name' => PriceModule::t('Name'),
            'price' => PriceModule::t('MODEL_PRICE'),
            'exist' => PriceModule::t('main', 'Exist'),
            'count' => PriceModule::t('MODEL_COUNT'),
            'sid' => PriceModule::t('MODEL_SUPPLIERS_NAME'),
            'visible' => PriceModule::t('MODEL_VISIBLE'),
            'status' => PriceModule::t('main', 'T_STATUS'),
            'created' => PriceModule::t('MODEL_CREATED'),
            'updated' => PriceModule::t('MODEL_UPDATED'),
            'constructions' => PriceModule::t('T_STATUS'),
            'cross_numbers' => PriceModule::t('T_STATUS'),
            'tecdoc_article_id' => PriceModule::t('T_STATUS'),
            'tecdoc_supplier_id' => PriceModule::t('T_STATUS'),
            'tecdoc_article_nr' => PriceModule::t('T_STATUS'),
            'tecdoc_supplier_brand' => PriceModule::t('T_STATUS'),
        ];
    }

    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
            ]
        ];
    }

    public function isShow()
    {
        return $this->visible === self::SHOW;
    }

    public function isHide()
    {
        return $this->visible === self::HIDE;
    }

    public function isExist()
    {
        return $this->exist === self::EXIST_AVAILABLE;
    }

    public function isNotExist()
    {
        return $this->exist === self::EXIST_UNAVAILABLE;
    }

    public function isExistUnderOrder()
    {
        return $this->exist === self::EXIST_UNDER_ORDER;
    }
}