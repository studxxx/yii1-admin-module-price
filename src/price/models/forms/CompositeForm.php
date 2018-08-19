<?php

abstract class CompositeForm extends FormModel
{
    /** @var FormModel[]|array */
    private $forms = [];

    /**
     * @return array
     */
    abstract protected function internalForms();

    public function load($data, $formName = null)
    {
        $success = parent::load($data, $formName);
        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                $success = FormModel::loadMultiple($form, $data, $formName === null ? null : $name) && $success;
            } else {
                $success = $form->load($data, $formName !== '' ? null : $name) && $success;
            }
        }
        return $success;
    }

    /**
     * @inheritdoc
     */
    public function validate($attributeNames = null, $clearErrors = true)
    {
        $parentNames = $attributeNames !== null ? array_filter((array) $attributeNames, 'is_string') : null;
        $success = parent::validate($parentNames, $clearErrors);
        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                $success = FormModel::validateMultiple($form) && $success;
            } else {
                $innerNames = $attributeNames !== null ? ArrayHelper::getValue($attributeNames, $name) : null;
                $success = $form->validate($innerNames ?: null, $clearErrors) && $success;
            }
        }
        return $success;
    }

    /**
     * @inheritdoc
     */
    public function hasErrors($attribute = null)
    {
        if ($attribute !== null) {
            return parent::hasErrors($attribute);
        }
        if (parent::hasErrors($attribute)) {
            return true;
        }
        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                foreach ($form as $i => $item) {
                    if ($item->hasErrors()) {
                        return true;
                    }
                }
            } else {
                if ($form->hasErrors()) {
                    return true;
                }
            }
        }
        return false;
    }

    public function getFirstErrors()
    {
        $errors = parent::getFirstErrors();
        foreach ($this->forms as $name => $form) {
            if (is_array($form)) {
                foreach ($form as $i => $item) {
                    foreach ($item->getFirstErrors() as $attribute => $error) {
                        $errors[$name . '.' . $i . '.' . $attribute] = $error;
                    }
                }
            } else {
                foreach ($form->getFirstErrors() as $attribute => $error) {
                    $errors[$name . '.' . $attribute] = $error;
                }
            }
        }
        return $errors;
    }

    /**
     * @inheritdoc
     */
    public function __get($name)
    {
        return isset($this->forms[$name]) ? $this->forms[$name] : parent::__get($name);
    }

    /**
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->internalForms(), true)) {
            $this->forms[$name] = $value;
            return;
        }
        parent::__set($name, $value);
    }

    /**
     * @inheritdoc
     * @throws CException
     */
    public function __isset($name)
    {
        return isset($this->forms[$name]) || parent::__unset($name);
    }
}
