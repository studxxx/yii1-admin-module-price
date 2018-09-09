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
            ['token, supplier_id', 'required'],
            ['id, exist, supplier_id, created_at, updated_at, visible, type, status, delivery, state, tecdoc_article_id, tecdoc_supplier_id', 'numerical', 'integerOnly' => true],
            ['token, search, sku, brand, name, price, count, constructions, cross_numbers, tecdoc_article_nr, tecdoc_supplier_brand', 'length', 'max' => 255],
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
            'id' => PriceModule::t('MODEL_ID'),
            'search' => PriceModule::t('MODEL_SEARCH'),
            'brand' => PriceModule::t('MODEL_BRAND'),
            'sku' => PriceModule::t('MODEL_SKU'),
            'name' => PriceModule::t('MODEL_NAME'),
            'price' => PriceModule::t('MODEL_PRICE'),
            'exist' => PriceModule::t('MODEL_EXIST'),
            'count' => PriceModule::t('MODEL_COUNT'),
            'supplier_id' => PriceModule::t('MODEL_SUPPLIERS_NAME'),
            'visible' => PriceModule::t('MODEL_VISIBLE'),
            'status' => PriceModule::t('MODEL_STATUS'),
            'constructions' => PriceModule::t('MODEL_CONSTRUCTIONS'),
            'cross_numbers' => PriceModule::t('MODEL_CROSS_NUMBERS'),
            'tecdoc_article_id' => PriceModule::t('MODEL_TECDOC_ARTICLE_ID'),
            'tecdoc_supplier_id' => PriceModule::t('MODEL_TECDOC_SUPPLIER_ID'),
            'tecdoc_article_nr' => PriceModule::t('MODEL_TECDOC_ARTICLE'),
            'tecdoc_supplier_brand' => PriceModule::t('MODEL_TECDOC_BRAND'),
            'created_at' => PriceModule::t('MODEL_CREATED_AT'),
            'updated_at' => PriceModule::t('MODEL_UPDATED_AT'),
        ];
    }

    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
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

    public function isTypeNotFound()
    {
        return $this->type === self::TYPE_NOT_FOUNT;
    }

    public function isTypeUndefined()
    {
        return $this->type === self::TYPE_UNDEFINED;
    }

    public function isTypeTecdoc()
    {
        return $this->type === self::TYPE_TECDOC;
    }

    /**
     * @param PriceCurrency $currency
     * @param PriceRange[] $ranges
     */
    public function setFinalPrice(PriceCurrency $currency, array $ranges)
    {
        $this->final_price = $this->price * $currency->value;

        foreach ($ranges as $range) {
            if ($range->from <= $this->final_price && $range->to >= $this->final_price
                || ($range->from <= $this->final_price && is_null($range->to))
            ) {
                $finalPrice = $this->final_price * ($range->value / 100 + 1);
                $this->final_price = new CDbExpression ("ROUND($finalPrice, 0)");
                break;
            }
        }
    }

    /**
     * @param CEvent $event
     * @throws CException
     */
    public function onPriceProductSaved(CEvent $event)
    {
        $this->raiseEvent('onPriceProductSaved', $event);
    }
}
