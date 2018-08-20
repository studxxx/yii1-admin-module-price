<?php

/**
 * Class PriceProductSearch
 * @property integer $id
 * @property integer $supplier_id
 * @property string $token
 * @property string $sku
 * @property string $search
 * @property string $brand
 * @property string $name
 * @property float $price
 * @property float $final_price
 * @property integer $exist
 * @property integer $type
 * @property integer $visible
 * @property integer $tecdoc_article_id
 * @property integer $tecdoc_supplier_id
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceProductSearch extends CModel
{
    public $id;
    public $token;
    public $supplier_id;
    public $sku;
    public $search;
    public $brand;
    public $name;
    public $price;
    public $final_price;
    public $exist;
    public $type;
    public $visible;
    public $tecdoc_article_id;
    public $tecdoc_supplier_id;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            ['id, token, supplier_id, sku, search, brand, name, price, final_price, exist, type, visible, tecdoc_article_id, tecdoc_supplier_id, created_at, updated_at', 'safe']
        ];
    }

    public function attributeNames()
    {
        return [
            'token', 'supplier_id', 'sku', 'search', 'brand', 'name', 'price', 'final_price', 'exist', 'type', 'visible',
            'tecdoc_article_id', 'tecdoc_supplier_id', 'created_at', 'updated_at'
        ];
    }

    public function attributeLabels()
    {
        return [
            'token' => PriceModule::t('MODEL_ID'),
            'supplier_id' => PriceModule::t('MODEL_SUPPLIER'),
            'sku' => PriceModule::t('MODEL_ARTICLE'),
            'search' => PriceModule::t('MODEL_SEARCH'),
            'brand' => PriceModule::t('MODEL_BRAND'),
            'name' => PriceModule::t('MODEL_NAME'),
            'price' => PriceModule::t('MODEL_PRICE'),
            'final_price' => PriceModule::t('MODEL_FINAL_PRICE'),
            'exist' => PriceModule::t('MODEL_EXIST'),
            'type' => PriceModule::t('MODEL_TYPE'),
            'visible' => PriceModule::t('MODEL_VISIBLE_PRODUCT'),
            'tecdoc_article_id' => PriceModule::t('MODEL_TECDOC_ARTICLE_ID'),
            'tecdoc_supplier_id' => PriceModule::t('MODEL_TECDOC_SUPPLIER_ID'),
            'created_at' => PriceModule::t('MODEL_CREATED_AT'),
            'updated_at' => PriceModule::t('MODEL_UPDATED_AT'),
        ];
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $dataProvider = new CActiveDataProvider(PriceProduct::class, [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 20],
        ]);

        if (!$this->validate()) {
            $this->unsetAttributes();
            return $dataProvider;
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('sku', $this->sku, true);
        $criteria->compare('search', $this->search, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('brand', $this->brand, true);
        $criteria->compare('price', $this->price);
        $criteria->compare('final_price', $this->final_price);
        $criteria->compare('exist', $this->exist);
        $criteria->compare('type', $this->type);
        $criteria->compare('tecdoc_article_id', $this->tecdoc_article_id);
        $criteria->compare('tecdoc_supplier_id', $this->tecdoc_supplier_id);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->order = 'updated_at ASC';

        return $dataProvider;
    }
}
