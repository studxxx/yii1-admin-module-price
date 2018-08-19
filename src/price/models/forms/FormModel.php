<?php

class FormModel extends CFormModel
{
    /**
     * @return string
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function formName()
    {
        $reflector = new ReflectionClass($this);
        if (PHP_VERSION_ID >= 70000 && $reflector->isAnonymous()) {
            throw new InvalidConfigException('The "formName()" method should be explicitly defined for anonymous models');
        }
        return $reflector->getShortName();
    }

//    public function attributes()
//    {
//        $class = new ReflectionClass($this);
//        $names = [];
//        foreach ($class->getProperties(\ReflectionProperty::IS_PUBLIC) as $property) {
//            if (!$property->isStatic()) {
//                $names[] = $property->getName();
//            }
//        }
//
//        return $names;
//    }
//
//    public function validate($attributeNames = null, $clearErrors = true)
//    {
//        if ($clearErrors) {
//            $this->clearErrors();
//        }
//
//        if (!$this->beforeValidate()) {
//            return false;
//        }
//
//        $scenarios = $this->scenarios();
//        $scenario = $this->getScenario();
//        if (!isset($scenarios[$scenario])) {
//            throw new InvalidArgumentException("Unknown scenario: $scenario");
//        }
//
//        if ($attributeNames === null) {
//            $attributeNames = $this->activeAttributes();
//        }
//
//        $attributeNames = (array)$attributeNames;
//
//        foreach ($this->getActiveValidators() as $validator) {
//            $validator->validateAttributes($this, $attributeNames);
//        }
//        $this->afterValidate();
//
//        return !$this->hasErrors();
//    }
    /**
     * @param $data
     * @param string|null $formName
     * @return bool
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public function load($data, $formName = null)
    {
        $scope = $formName === null ? $this->formName() : $formName;
        if ($scope === '' && !empty($data)) {
            $this->attributes = $data;

            return true;
        } elseif (isset($data[$scope])) {
            $this->attributes = $data[$scope];

            return true;
        }

        return false;
    }

    /**
     * @param $models
     * @param $data
     * @param null $formName
     * @return bool
     * @throws InvalidConfigException
     * @throws ReflectionException
     */
    public static function loadMultiple($models, $data, $formName = null)
    {
        if ($formName === null) {
            /* @var $first FormModel|false */
            $first = reset($models);
            if ($first === false) {
                return false;
            }
            $formName = $first->formName();
        }

        $success = false;
        foreach ($models as $i => $model) {
            /* @var $model FormModel */
            if ($formName == '') {
                if (!empty($data[$i]) && $model->load($data[$i], '')) {
                    $success = true;
                }
            } elseif (!empty($data[$formName][$i]) && $model->load($data[$formName][$i], '')) {
                $success = true;
            }
        }

        return $success;
    }

    public static function validateMultiple($models, $attributeNames = null)
    {
        $valid = true;
        /* @var $model FormModel */
        foreach ($models as $model) {
            $valid = $model->validate($attributeNames) && $valid;
        }

        return $valid;
    }

    public function getFirstErrors()
    {
        if (empty($this->errors)) {
            return [];
        }

        $errors = [];
        foreach ($this->errors as $name => $es) {
            if (!empty($es)) {
                $errors[$name] = reset($es);
            }
        }

        return $errors;
    }
}
