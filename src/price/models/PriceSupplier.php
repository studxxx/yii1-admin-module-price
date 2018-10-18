<?php

/**
 * @property integer $id
 * @property integer $currency_id
 * @property string $name
 * @property string $title
 * @property string $email
 * @property string $phone
 * @property string $description
 * @property string $note
 * @property integer $created_at
 * @property integer $updated_at
 * @property PriceCurrency $currency
 * @property PriceRange[] $ranges
 * @property PriceTemplate[] $templates
 */
class PriceSupplier extends CActiveRecord
{
    public function tableName()
    {
        return 'price_suppliers';
    }

    public function rules()
    {
        return [
            ['currency_id, created_at, updated_at', 'numerical', 'integerOnly' => true],
            ['name, email, title', 'length', 'max' => 255],
            ['email', 'email'],
            ['phone', 'length', 'max' => 18],
            ['description, note', 'safe'],
        ];
    }

    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
                'setUpdateOnCreate' => true,
            ],
            'RelationBehavior' => [
                'class' => 'vendor.yiiext.activerecord-relation-behavior.EActiveRecordRelationBehavior',
            ],
        ];
    }

    public function relations()
    {
        return [
            'currency' => [self::BELONGS_TO, 'PriceCurrency', 'currency_id'],
            'ranges' => [self::HAS_MANY, 'PriceRange', 'supplier_id'],
            'templates' => [self::HAS_MANY, 'PriceTemplate', 'supplier_id'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => PriceModule::t('ID'),
            'currency_id' => PriceModule::t('MODEL_CURRENCY'),
            'name' => PriceModule::t('MODEL_NAME'),
            'email' => PriceModule::t('MODEL_EMAIL'),
            'phone' => PriceModule::t('MODEL_PHONE'),
            'note' => PriceModule::t('MODEL_NOTE'),
            'description' => PriceModule::t('MODEL_DESCRIPTION'),
            'created_at' => PriceModule::t('MODEL_CREATED_AT'),
            'updated_at' => PriceModule::t('MODEL_UPDATED_AT'),
        ];
    }

    /**
     * @param string $className
     * @return CActiveRecord|PriceSupplier
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function changeCurrency(int $currencyId)
    {
        $this->currency_id = $currencyId;
    }

    public function setRange($from, $to, $value, $id = null)
    {
        $ranges = $this->ranges;
        if ($id) {
            foreach ($ranges as $range) {
                if ($range->isForRange($id)) {
                    $range->change($from, $to, $value);
                    $this->ranges = $ranges;
                    return;
                }
            }
        }

        if ($value === null) {
            return;
        }

        $newRange = new PriceRange();
        $newRange->from = $from;
        $newRange->to = $to;
        $newRange->value = $value;

        $ranges[] = $newRange;
        $this->ranges = $ranges;
    }

    public function setTemplate($coordinate, $fieldName, $validator, $id = null)
    {
        $templates = $this->templates;
        if ($id) {
            foreach ($templates as $template) {
                if ($template->isForTemplate($id)) {
                    $template->change($coordinate, $fieldName, $validator);
                    $this->templates = $templates;
                    return;
                }
            }
        }

        $newTemplate = new PriceTemplate();
        $newTemplate->coordinate = $coordinate;
        $newTemplate->field_name = $fieldName;
        $newTemplate->validator = $validator;

        $templates[] = $newTemplate;
        $this->templates = $templates;
    }
}
