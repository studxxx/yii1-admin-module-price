<?php

/**
 * @property integer $id
 * @property integer $currency_id
 * @property integer $range_id
 * @property integer $template_id
 * @property string $name
 * @property string $title
 * @property string $email
 * @property string $phone
 * @property string $description
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceSupplier extends CActiveRecord
{
    public function tableName()
    {
        return 'price_suppliers';
    }

    public function rules()
    {
        return [
            ['currency_id, template_id, range_id, created_at, updated_at', 'numerical', 'integerOnly' => true],
            ['name, email, title', 'length', 'max' => 255],
            ['email', 'email'],
            ['phone', 'length', 'max' => 18],
            ['description, note', 'safe'],
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

    public function relations()
    {
        return [
            'currency' => [self::BELONGS_TO, 'PriceCurrency', 'currency_id'],
            'ranges' => [self::HAS_MANY, 'PriceRange', 'supplier_id'],
            'templates' => [self::HAS_MANY, 'PriceTemplate', 'supplier_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => PriceModule::t('ID'),
            'currency_id' => PriceModule::t('Currency'),
            'name' => PriceModule::t('MODEL_SUPPLIERS_NAME'),
            'email' => PriceModule::t('Email'),
            'phone' => PriceModule::t('Phone'),
            'description' => PriceModule::t('Description'),
            'template' => PriceModule::t('template'),
            'margin' => PriceModule::t('Margin'),
            'note' => PriceModule::t('Note'),
            'created_at' => PriceModule::t('Created'),
            'updated_at' => PriceModule::t('Updated'),
        ];
    }

    /**
     * @param string $className
     * @return CActiveRecord|PriceSupplier
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }
}
