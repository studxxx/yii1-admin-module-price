<?php

/**
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $coordinate
 * @property string $field_name
 * @property string $validator
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceTemplate extends CActiveRecord
{
    public function tableName()
    {
        return 'price_templates';
    }

    public function rules()
    {
        return [
            ['supplier_id, coordinate, created_at, updated_at', 'numerical', 'integerOnly' => true],
            ['field_name, validator', 'length', 'max' => 10],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier',
            'coordinate' => 'Coordinate',
            'field_name' => 'Field name',
            'validator' => 'Validator',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
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
}
