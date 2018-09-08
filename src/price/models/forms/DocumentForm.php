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
            $this->addRule($template->field_name, $template->validator);
        }

        parent::__construct($attributes, $scenario);
    }
}
