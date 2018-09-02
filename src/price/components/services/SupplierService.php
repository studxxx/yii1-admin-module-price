<?php

class SupplierService
{
    /** @var PriceSupplierRepository */
    private $suppliers;
    /** @var PriceCurrencyRepository */
    private $currencies;
    /** @var TransactionManager */
    private $transaction;

    public function __construct(
        PriceSupplierRepository $suppliers,
        PriceCurrencyRepository $currencies,
        TransactionManager $transaction
    ) {
        $this->currencies = $currencies;
        $this->suppliers = $suppliers;
        $this->transaction = $transaction;
    }

    /**
     * @param PriceSupplierForm $form
     * @return PriceSupplier
     * @throws Exception
     */
    public function create(PriceSupplierForm $form): PriceSupplier
    {
        $supplier = $this->suppliers->create();
        $currency = $this->currencies->get($form->currency->currency);

        $supplier->name = $form->name;
        $supplier->title = $form->title;
        $supplier->email = $form->email;
        $supplier->phone = $form->phone;
        $supplier->description = $form->description;
        $supplier->note = $form->note;

        $supplier->changeCurrency($currency->id);

        foreach ($form->ranges as $range) {
            $supplier->setRange($range->from, $range->to, $range->value);
        }

//        foreach ($form->templates as $template) {
//            $supplier->setTemplate($template->coordinate, $template->field_name, $template->validator);
//        }

//        $product->setPrice($form->price->new, $form->price->old);

//        foreach ($form->categories->others as $otherId) {
//            $category = $this->categories->get($otherId);
//            $product->assignCategory($category->id);
//        }

//        foreach ($form->values as $value) {
//            $product->setValue($value->id, $value->value);
//        }

//        foreach ($form->photos->files as $file) {
//            $product->addPhoto($file);
//        }

//        foreach ($form->tags->existing as $tagId) {
//            $tag = $this->tags->get($tagId);
//            $product->assignTag($tag->id);
//        }

        $this->transaction->wrap(function () use ($supplier) {
            foreach ($supplier->ranges as $range) {
                if ($range->isNewRecord) {
                    $range->save();
                }
            }
//            foreach ($supplier->templates as $template) {
//                if ($template->isNewRecord) {
//                    $template->save();
//                }
//            }
            $this->suppliers->save($supplier);
        });

        return $supplier;
    }

    /**
     * @param $id
     * @param PriceSupplierForm $form
     * @throws Exception
     */
    public function edit($id, PriceSupplierForm $form)
    {

        $supplier = $this->suppliers->get($id);
        $currency = $this->currencies->get($form->currency->currency);

        $supplier->name = $form->name;
        $supplier->title = $form->title;
        $supplier->email = $form->email;
        $supplier->phone = $form->phone;
        $supplier->description = $form->description;
        $supplier->note = $form->note;

        $supplier->changeCurrency($currency->id);

        foreach ($form->ranges as $range) {
            $supplier->setRange($range->from, $range->to, $range->value, $range->id);
        }

//        $brand = $this->brands->get($form->brandId);
//        $category = $this->categories->get($form->categories->main);
//
//        $this->products->setMeta(
//            $product,
//            $form->meta->title,
//            $form->meta->description,
//            $form->meta->keywords
//        );

        $this->transaction->wrap(function () use ($supplier) {
//            $product->revokeCategories();
//            $product->revokeTags();
//            $this->products->save($product);
//
//            foreach ($form->categories->others as $otherId) {
//                $category = $this->categories->get($otherId);
//                $product->assignCategory($category->id);
//            }
//
//            foreach ($form->values as $value) {
//                $product->setValue($value->id, $value->value);
//            }
//
//            foreach ($form->tags->existing as $tagId) {
//                $tag = $this->tags->get($tagId);
//                $product->assignTag($tag->id);
//            }

            foreach ($supplier->ranges as $range) {
                $range->save();
            }

            $this->suppliers->save($supplier);
        });
    }

//    /**
//     * @inheritdoc
//     */
//    public function changePrice($id, PriceForm $form): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->setPrice($form->new, $form->old);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function changeQuantity($id, QuantityForm $form): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->changeQuantity($form->quantity);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function activate($id): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->activate();
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function draft($id): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->draft();
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function addPhotos($id, PhotosForm $form): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        foreach ($form->files as $file) {
//            $product->addPhoto($file);
//        }
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function movePhotoUp($id, $photoId): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->movePhotoUp($photoId);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function movePhotoDown($id, $photoId): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->movePhotoDown($photoId);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function removePhoto($id, $photoId): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_DELETE;
//        $product->removePhoto($photoId);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function addRelatedProduct($id, $otherId): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $other = $this->products->get($otherId);
//        $product->assignRelatedProduct($other->id);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function removeRelatedProduct($id, $otherId): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $other = $this->products->get($otherId);
//        $product->revokeRelatedProduct($other->id);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function addModification($id, ModificationForm $form): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->addModification($form->code, $form->name, $form->price, $form->quantity);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function editModification($id, $modificationId, ModificationForm $form): void
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->editModification($modificationId, $form->code, $form->name, $form->price, $form->quantity);
//        $this->products->save($product);
//    }
//
//    /**
//     * @inheritdoc
//     */
//    public function removeModification($id, $modificationId)
//    {
//        $product = $this->products->get($id);
//        $product->scenario = Product::SCENARIO_UPDATE;
//        $product->removeModification($modificationId);
//        $this->products->save($product);
//    }

    /**
     * @param $id
     * @throws CDbException
     */
    public function remove($id)
    {
        $supplier = $this->suppliers->get($id);
        $this->suppliers->remove($supplier);
    }
}
