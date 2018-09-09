<?php

class DocumentForm extends DynamicForm
{
    /**
     * DocumentForm constructor.
     * @param PriceTemplate[] $templates
     * @param array $attributes
     * @param string $scenario
     * @throws CException
     */
    public function __construct($templates, array $attributes = [], $scenario = '')
    {
        foreach ($templates as $template) {
            $this->defineAttribute($template->field_name);
            if ($template->field_name === 'count') {
                $this->addRule('count', 'filter', ['filter' => ['DocumentForm', 'filterOnlyNumber']]);
            }
            $this->addRule($template->field_name, $template->validator);
        }

        parent::__construct($attributes, $scenario);
    }

    public function filterOnlyNumber($value)
    {
        return preg_replace('/[^0-9\.]/', '', $value);
    }

    public function filterOnlySymbol($value)
    {
        return preg_replace("/[^a-zA-Z0-9]/", '', $value);
    }
}
