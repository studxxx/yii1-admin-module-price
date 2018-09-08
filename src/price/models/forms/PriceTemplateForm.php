<?php
/**
 * @property int $id
 * @property int $coordinate
 * @property string $field_name
 * @property string $validator
 */
class PriceTemplateForm extends FormModel
{
    public $id;
    public $coordinate;
    public $field_name;
    public $validator;

    public function rules()
    {
        return [
            ['id, coordinate', 'numerical', 'integerOnly' => true],
            ['field_name', 'length'],
            ['validator', 'safe'],
//            ['name','uniqueIdAndName']
//            [
//                'id, coordinate, field_name, validator',
//                'unique',
//                'criteria' => [
//                    'condition' => "infoblock = :infoblock",
//                    'params' => [':infoblock' => $this->infoblock]
//                ]
//            ],
        ];
    }

    /**
     * Declares attribute labels.
     */
    public function attributeLabels()
    {
        return [
            'id' => PriceModule::t('#'),
            'coordinate' => PriceModule::t('FORM_COORDINATE'),
            'field_name' => PriceModule::t('FORM_FIELD_NAME'),
            'validator' => PriceModule::t('FORM_VALIDATOR'),
        ];
    }



//    public function uniqueIdAndName($attribute,$params=array())
//    {
//        if(!$this->hasErrors())
//        {
//            $params['criteria']=array(
//                'condition'=>'id=:id',
//                'params'=>array(':id'=>$this->id),
//            );
//            $validator=CValidator::createValidator('unique',$this,$attribute,$params);
//            $validator->validate($this,array($attribute));
//        }
//    }
}
