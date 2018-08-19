<?php

class SupplierService
{
    /** @var PriceSupplierRepository */
    private $suppliers;
    /** @var PriceCurrencyRepository */
    private $currencies;

    public function __construct(
        PriceSupplierRepository $suppliers,
        PriceCurrencyRepository $currencies
    ) {
        $this->currencies = $currencies;
        $this->suppliers = $suppliers;
    }

    /**
     * @param PriceSupplierForm $form
     * @return PriceSupplier
     */
    public function create(PriceSupplierForm $form): PriceSupplier
    {
        $brand = $this->brands->get($form->brandId);
        $category = $this->categories->get($form->categories->main);

        $product = $this->products->create();

        $product->brand_id = $brand->id;
        $product->category_id = $category->id;
        $product->code = $form->code;
        $product->name = $form->name;
        $product->description = $form->description;
        $product->weight = $form->weight;
        $product->quantity = $form->quantity->quantity;
        $this->products->setMeta(
            $product,
            $form->meta->title,
            $form->meta->description,
            $form->meta->keywords
        );

        $product->setPrice($form->price->new, $form->price->old);

        foreach ($form->categories->others as $otherId) {
            $category = $this->categories->get($otherId);
            $product->assignCategory($category->id);
        }

        foreach ($form->values as $value) {
            $product->setValue($value->id, $value->value);
        }

        foreach ($form->photos->files as $file) {
            $product->addPhoto($file);
        }

        foreach ($form->tags->existing as $tagId) {
            $tag = $this->tags->get($tagId);
            $product->assignTag($tag->id);
        }

        $this->transaction->wrap(function () use ($product, $form) {
            foreach ($form->tags->newNames as $tagName) {
                if (!$tag = $this->tags->findByName($tagName)) {
                    $tag = $this->tags->create();
                    $tag->name = $tagName;
                    $tag->slug = $tagName;
                    $this->tags->save($tag);
                }
                $product->assignTag($tag->id);
            }
            $this->products->save($product);
        });

        return $product;
    }

    /**
     * @param $id
     * @param PriceSupplierForm $form
     */
    public function edit($id, PriceSupplierForm $form): void
    {
        $supplier = $this->suppliers->get($id);
        $currency = $this->currencies->get($form->currency->currency);

//        $brand = $this->brands->get($form->brandId);
//        $category = $this->categories->get($form->categories->main);
//
//        $product->brand_id = $brand->id;
//        $product->code = $form->code;
//        $product->name = $form->name;
//        $product->description = $form->description;
//        $product->weight = $form->weight;
//
//        $this->products->setMeta(
//            $product,
//            $form->meta->title,
//            $form->meta->description,
//            $form->meta->keywords
//        );
//
//        $product->changeMainCategory($category->id);
//
//        $this->transaction->wrap(function () use ($product, $form) {
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
//
//            foreach ($form->tags->newNames as $tagName) {
//                if (!$tag = $this->tags->findByName($tagName)) {
//                    $tag = $this->tags->create();
//                    $tag->name = $tagName;
//                    $tag->slug = $tagName;
//                    $this->tags->save($tag);
//                }
//                $product->assignTag($tag->id);
//            }
//
//            $this->products->save($product);
//        });
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
