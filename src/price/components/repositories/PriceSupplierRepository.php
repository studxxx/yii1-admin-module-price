<?php

class PriceSupplierRepository
{
    public function get($id)
    {
        if(!$supplier = PriceSupplier::model()->findByPk($id)) {
            throw new RuntimeException('Supplier not found!');
        }
        return $supplier;
    }

    public function save(PriceSupplier $supplier)
    {
        if (!$supplier->save()) {
            throw new RuntimeException('Saving supplier error!');
        }
        // dispatch(new EntityPersisted($price))
    }

    /**
     * @param PriceSupplier $supplier
     * @throws CDbException
     */
    public function remove(PriceSupplier $supplier)
    {
        if (!$supplier->delete()) {
            throw new RuntimeException('Removing supplier error.');
        }
    }

    public function getList()
    {
        return CHtml::listData(PriceSupplier::model()->findAll(), 'id', 'name');
    }
}