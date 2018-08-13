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
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'value' => 'Value',
            'default' => 'Default',
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
            ]
        ];
    }
}
