<?php

/**
 * Class PriceRangeForm
 * @property int $id
 * @property int $from
 * @property int $to
 * @property int $value
 * @property bool $isNewRecord
 */
class PriceRangeForm extends FormModel
{
    public $id;
    public $from;
    public $to;
    public $value;

    public function rules()
    {
        return [
            ['from', 'required'],
            ['id, from, to, value', 'numerical', 'integerOnly' => true],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => '#',
            'from' => 'From',
            'to' => 'To',
            'value' => 'Value',
        ];
    }

    public function getIsNewRecord()
    {
        return $this->id === null;
    }
}
