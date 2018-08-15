<?php

class PriceCurrencyRepository
{
    public function get($id)
    {
        if(!$price = PriceCurrency::model()->findByPk($id)) {
            throw new RuntimeException('Price not found!');
        }
        return $price;
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
