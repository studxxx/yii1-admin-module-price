<?php

/**
 * @property integer $id
 * @property integer $supplier_id
 * @property string $price_file
 * @property string $csv_file
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class Price extends CActiveRecord
{
    const STATUS_ERROR = 0;
    const STATUS_UPLOADED = 1;
    const STATUS_IMPORTED = 2;

    public function tableName()
    {
        return 'prices';
    }

    public function rules()
    {
        return [
            ['supplier_id, status, created_at, updated_at', 'numerical', 'integerOnly' => true],
            ['supplier_id', 'required'],
            ['price_file', 'length', 'max' => 255],
//            ['name', 'file', 'types' => 'xlsx,xls,csv'],
            ['id, price_file, supplier_id, status, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function relations()
    {
        return [
            'suppliers' => [self::BELONGS_TO, 'PriceSupplier', 'supplier_id'],
        ];
    }

    public function behaviors()
    {
        return [
            'CTimestampBehavior' => [
                'class' => 'zii.behaviors.CTimestampBehavior',
                'createAttribute' => 'created_at',
                'updateAttribute' => 'updated_at',
            ]
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return [
            'id' => PriceModule::t('MODEL_ID'),
            'price_file' => PriceModule::t('MODEL_PRICE_FILE'),
            'csv_file' => PriceModule::t('MODEL_CSV_FILE'),
            'supplier_id' => PriceModule::t('MODEL_SUPPLIER'),
            'status' => PriceModule::t('MODEL_STATUS_PRICE'),
            'created_at' => PriceModule::t('MODEL_CREATED_AT'),
            'updated_at' => PriceModule::t('MODEL_UPDATED_AT'),
        ];
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('price_file', $this->price_file, true);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->order = 'status ASC';

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 20],
        ]);
    }

    /**
     * @param string $className
     * @return Price|CActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function isImported()
    {
        return $this->status == Price::STATUS_IMPORTED;
    }

    public function isUploaded()
    {
        return $this->status == Price::STATUS_UPLOADED;
    }
}
