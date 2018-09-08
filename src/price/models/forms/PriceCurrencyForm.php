<?php

class PriceCurrencyForm extends FormModel
{
    public $currency;

    public function __construct(PriceSupplier $supplier = null, $scenario = '')
    {
        if ($supplier) {
            $this->currency = $supplier->currency_id;
        }
        parent::__construct($scenario);
    }

    public function rules(): array
    {
        return [
            ['currency', 'required'],
            ['currency', 'numerical', 'integerOnly' => true],
        ];
    }

    public function list(): array
    {
        return CHtml::listData(PriceCurrency::model()->findAll(), 'id', 'name');
    }
}
