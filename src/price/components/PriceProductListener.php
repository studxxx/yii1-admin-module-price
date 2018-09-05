<?php
Yii::import('admin-module-tecdoc.components.TecdocLookupListener');

class PriceProductListener
{
    /**
     * @param CEvent $event
     * @throws CException
     */
    public static function documentRowRead(CEvent $event)
    {
        $row = $event->sender;
        if (!$product = PriceProduct::model()->find('token = :token', [':token' => $row['token']])) {
            $product = new PriceProduct();
        }
        $product->attributes = $row;
        $supplier = PriceSupplier::model()->findByPk($row['supplier_id']);
        $product->setFinalPrice($supplier->currency, $supplier->ranges);

        $product->attachEventHandler('onPriceProductSaved', ['TecdocLookupListener', 'priceProductPersisted']);
//        $product->attachEventHandler('onPriceProductSaved', ['ProductListener', 'saveVariant']);

        if (!$product->save()) {
            $message = $product->isNewRecord
                ? 'Product not added.'
                : "Product with ID: {$product->id} not saved." . PHP_EOL;
            $message .= CVarDumper::dumpAsString($product->getErrors()) . PHP_EOL;
            $message .= 'with row data:' . PHP_EOL;
            $message .= CVarDumper::dumpAsString($row);
            Yii::log($message, CLogger::LEVEL_ERROR, 'application');
        }
        if ($product->hasEventHandler('onPriceProductSaved')) {
            $product->onPriceProductSaved(new CEvent($product));
        }
    }
}
