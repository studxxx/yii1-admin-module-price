<?php

class DynamicForm extends FormModel
{
    private $_attributes = [];

    /**
     * @param array $attributes
     * @param string $scenario
     */
    public function __construct(array $attributes = [], $scenario = '')
    {
        foreach ($attributes as $name => $value) {
            if (is_int($name)) {
                $this->_attributes[$value] = null;
            } else {
                $this->_attributes[$name] = $value;
            }
        }
        parent::__construct($scenario);
    }

    /**
     * @inheritdoc
     * @throws CException
     */
    public function __get($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return $this->_attributes[$name];
        }

        return parent::__get($name);
    }

    /**
     * @inheritdoc
     * @throws CException
     */
    public function __set($name, $value)
    {
        if (array_key_exists($name, $this->_attributes)) {
            $this->_attributes[$name] = $value;
        } else {
            parent::__set($name, $value);
        }
    }

    /**
     * @inheritdoc
     */
    public function __isset($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            return isset($this->_attributes[$name]);
        }

        return parent::__isset($name);
    }

    /**
     * @inheritdoc
     * @throws CException
     */
    public function __unset($name)
    {
        if (array_key_exists($name, $this->_attributes)) {
            unset($this->_attributes[$name]);
        } else {
            parent::__unset($name);
        }
    }

    /**
     * Defines an attribute.
     * @param string $name the attribute name
     * @param mixed $value the attribute value
     */
    public function defineAttribute($name, $value = null)
    {
        $this->_attributes[$name] = $value;
    }

    /**
     * Undefines an attribute.
     * @param string $name the attribute name
     */
    public function undefineAttribute($name)
    {
        unset($this->_attributes[$name]);
    }

    /**
     * @param $attributes
     * @param $validator
     * @param array $options
     * @return $this
     */
    public function addRule($attributes, $validator, $options = [])
    {
        $validators = $this->getValidatorList();
        $validators->add(CValidator::createValidator($validator, $this, (array) $attributes, $options));

        return $this;
    }

    /**
     * @param array $data
     * @param array $rules
     * @return DynamicForm
     * @throws InvalidConfigException
     */
    public static function validateData(array $data, $rules = [])
    {
        /* @var $model DynamicForm */
        $model = new static($data);
        if (!empty($rules)) {
            $validators = $model->getValidatorList();
            foreach ($rules as $rule) {
                if ($rule instanceof CValidator) {
                    $validators->add($rule);
                } elseif (is_array($rule) && isset($rule[0], $rule[1])) { // attributes, validator type
                    $validator = CValidator::createValidator($rule[1], $model, (array) $rule[0], array_slice($rule, 2));
                    $validators->add($validator);
                } else {
                    throw new InvalidConfigException('Invalid validation rule: a rule must specify both attribute names and validator type.');
                }
            }
        }
        $model->validate();

        return $model;
    }

    /**
     * {@inheritdoc}
     */
    public function attributes()
    {
        return array_keys($this->_attributes);
    }

    public function getAttributes($names = null)
    {
        if (is_array($names)) {
            foreach ($names as $name) {
                if (key_exists($name, $this->_attributes)) {
                    $attributes[$name] = $this->_attributes[$name];
                }
            }
        }

        return $this->_attributes;
    }
}
