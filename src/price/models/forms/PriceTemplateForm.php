<?php
/**
 * @property int $coordinate
 * @property string $field_name
 * @property string $validator
 */

class PriceTemplateForm extends FormModel
{
    public $coordinate;
    public $field_name;
    public $validator;

    public function rules()
    {
        return [
            ['coordinate, field_name, validator', 'safe'],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'coordinate' => PriceModule::t('FORM_COORDINATE'),
            'field_name' => PriceModule::t('FORM_FIELD_NAME'),
            'validator' => PriceModule::t('FORM_VALIDATOR'),
        ];
    }
}
