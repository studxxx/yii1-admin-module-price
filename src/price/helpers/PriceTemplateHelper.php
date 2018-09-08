<?php

class PriceTemplateHelper
{
    public static function fields() {
        return [
            'sku' => PriceModule::t('sku'),
            'brand' => PriceModule::t('brand'),
            'name' => PriceModule::t('name'),
            'count' => PriceModule::t('count'),
            'price' => PriceModule::t('price'),
        ];
    }

    public static function validators()
    {
        return [
            'length' => PriceModule::t('String'),
            'numerical' => PriceModule::t('Number'),
            'required' => PriceModule::t('Required'),
            'count' => PriceModule::t('count'),
            'price' => PriceModule::t('price'),
        ];
    }
}
