<?php

/**
 * Class PriceRangeForm
 * @property $from
 * @property $to
 * @property $value
 */
class PriceRangeForm extends FormModel
{
    public $from;
    public $to;
    public $value;

    public function rules()
    {
        return [
            ['from, to, value', 'safe']
        ];
    }

    public function attributeLabels()
    {
        return [
            'from' => 'From',
            'to' => 'To',
            'value' => 'Value',
        ];
    }
}
