<?php

class PriceRepository
{
    public function get($id)
    {
        if(!$price = Price::model()->findByPk($id)) {
            throw new RuntimeException('Price not found!');
        }
        return $price;
    }

    public function save(Price $price)
    {
        if (!$price->save()) {
            throw new RuntimeException('Saving price error!');
        }
        // dispatch(new EntityPersisted($price))
    }

    /**
     * @param Price $price
     * @throws CDbException
     */
    public function remove(Price $price)
    {
        if (!$price->delete()) {
            throw new RuntimeException('Removing price error.');
        }
    }
}
