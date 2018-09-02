<?php

/**
 * Class PriceSupplierForm
 *
 * @property integer $id
 * @property string $name
 * @property string $title
 * @property string $email
 * @property string $phone
 * @property string $description
 * @property string $note
 * @property PriceCurrencyForm $currency
 * @property PriceRangeForm[] $ranges
 * @property PriceTemplateForm[] $templates
 */
class PriceSupplierForm extends CompositeForm
{
    public $id;
    public $name;
    public $title;
    public $email;
    public $phone;
    public $description;
    public $note;

    public function __construct(PriceSupplier $supplier = null, $scenario = '')
    {
        if ($supplier) {
            $this->name = $supplier->name;
            $this->title = $supplier->title;
            $this->email = $supplier->email;
            $this->phone = $supplier->phone;
            $this->description = $supplier->description;
            $this->note = $supplier->note;
            $this->currency = new PriceCurrencyForm($supplier);
            $this->ranges = empty($supplier->ranges)
                ? [new PriceRangeForm()]
                : array_map(function (PriceRange $range) {
                    $form = new PriceRangeForm();
                    $form->id = $range->id;
                    $form->from = $range->from;
                    $form->to = $range->to;
                    $form->value = $range->value;

                    return $form;
                }, $supplier->ranges);
            $this->templates = empty($supplier->templates)
                ? [new PriceTemplateForm()]
                : array_map(function (PriceTemplate $template) {
                    $form = new PriceTemplateForm();
                    $form->coordinate = $template->coordinate;
                    $form->field_name = $template->field_name;
                    $form->validator = $template->validator;

                    return $form;
                }, $supplier->templates);
        } else {
            $this->currency = new PriceCurrencyForm();
            $this->ranges = [new PriceRangeForm()];
            $this->templates = [new PriceTemplateForm];
        }
        parent::__construct($scenario);
    }

    public function rules()
    {
        return [
            ['name, phone', 'required'],
            ['name, title, phone', 'length', 'max' => 255],
            ['email', 'email'],
            ['description, note', 'safe'],
        ];
    }

    protected function internalForms()
    {
        return ['currency', 'ranges', 'templates'];
    }
}
