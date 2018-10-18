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
    public function create(PriceSupplierForm $form)
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

        foreach ($form->templates as $template) {
            $supplier->setTemplate($template->coordinate, $template->field_name, $template->validator);
        }

        $this->transaction->wrap(function () use ($supplier) {
            foreach ($supplier->ranges as $range) {
                $range->save();
            }

            foreach ($supplier->templates as $template) {
                $template->save();
            }
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

        foreach ($form->templates as $template) {
            $supplier->setTemplate($template->coordinate, $template->field_name, $template->validator, $template->id);
        }

        $this->transaction->wrap(function () use ($supplier) {

            foreach ($supplier->ranges as $range) {
                $range->save();
            }

            foreach ($supplier->templates as $template) {
                $template->save();
            }
            $this->suppliers->save($supplier);
        });
    }

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
