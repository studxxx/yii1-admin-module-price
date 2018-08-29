<?php

class PriceCurrencyRepository
{
    /**
     * @param integer $id
     * @return array|CActiveRecord|mixed|null|PriceCurrency
     */
    public function get($id)
    {
        if(!$currency = PriceCurrency::model()->findByPk($id)) {
            throw new RuntimeException('Price not found!');
        }
        return $currency;
    }

    public function save(PriceCurrency $price)
    {
        if (!$price->save()) {
            throw new RuntimeException('Saving price error!');
        }
        // dispatch(new EntityPersisted($price))
    }

    /**
     * @param PriceCurrency $price
     * @throws CDbException
     */
    public function remove(PriceCurrency $price)
    {
        if (!$price->delete()) {
            throw new RuntimeException('Removing price error.');
        }
    }
}
