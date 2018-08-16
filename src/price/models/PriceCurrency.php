<?php

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property integer $default
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceCurrency extends CActiveRecord
{
    public function tableName()
    {
        return 'price_currencies';
    }

    public function rules()
    {
        return [
            ['default, created_at, updated_at', 'numerical', 'integerOnly' => true],
            ['code, name', 'length', 'max' => 10],
            ['value', 'numerical'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => PriceModule::t('MODEL_ID'),
            'code' => PriceModule::t('MODEL_CODE'),
            'name' => PriceModule::t('MODEL_NAME'),
            'value' => PriceModule::t('MODEL_CURRENCY_VALUE'),
            'default' => PriceModule::t('MODEL_BASIC'),
            'created_at' => PriceModule::t('MODEL_CREATED_AT'),
            'updated_at' => PriceModule::t('MODEL_UPDATED_AT'),
        ];
    }

    /**
     * @param string $className
     * @return PriceCurrency|CActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
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

    protected function beforeSave()
    {
        if ($this->default == 1) {
            self::updateAll(['default' => 0]);
            if ($this->value != 1) {
                $this->value = 1;
            }
        }
        return parent::beforeSave();
    }
}
