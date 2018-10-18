<?php

/**
 * @property integer $id
 * @property integer $supplier_id
 * @property integer $from
 * @property integer $to
 * @property integer $value
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceRange extends CActiveRecord
{
    public function tableName()
    {
        return 'price_ranges';
    }

    public function rules()
    {
        return [
            ['supplier_id, from, to, created_at, updated_at', 'numerical', 'integerOnly' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'supplier_id' => 'Supplier',
            'from' => 'From',
            'to' => 'To',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
        ];
    }

    /**
     * @param string $className
     * @return PriceRange|CActiveRecord
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

    public function isForRange(int $id)
    {
        return intval($this->id) === $id;
    }

    public function change(int $from, $to, int $value)
    {
        $this->from = $from;
        $this->to = $to;
        $this->value = $value;
    }
}
