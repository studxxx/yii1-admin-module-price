<?php

/**
 * @property integer $id
 * @property string $name
 * @property string $output_file
 * @property integer $supplier
 * @property integer $status
 * @property integer $created
 * @property integer $updated
 */
class Import extends CActiveRecord
{
    const STATUS_UPLOADED = 0;
    const STATUS_IMPORTED = 1;
    const STATUS_ERROR = 2;

    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'import';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return [
            ['supplier, status, created, updated', 'numerical', 'integerOnly' => true],
            ['supplier', 'required'],
            ['output_file', 'length', 'max' => 255],
            ['name', 'file', 'types' => 'xlsx,xls,csv'],
            ['id, name, output_file, supplier, status, created, updated', 'safe', 'on' => 'search'],
        ];
    }

    /**
     * @return array relational rules.
     */
    public function relations()
    {
        return [
            'suppliers' => [self::BELONGS_TO, 'Suppliers', 'supplier'],
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => ConsoleModule::t('MODEL_ID'),
            'name' => ConsoleModule::t('MODEL_NAME_FILE'),
            'output_file' => ConsoleModule::t('MODEL_OUTPUT_FILE'),
            'supplier' => ConsoleModule::t('MODEL_SUPPLIER'),
            'status' => ConsoleModule::t('MODEL_STATUS_IMPORT'),
            'created' => ConsoleModule::t('MODEL_CREATED'),
            'updated' => ConsoleModule::t('MODEL_UPDATED'),
        );
    }

    /**
     * @return CActiveDataProvider
     */
    public function search()
    {
        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('output_file', $this->output_file, true);
        $criteria->compare('supplier', $this->supplier);
        $criteria->compare('status', $this->status);
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);
        $criteria->order = 'status ASC';

        return new CActiveDataProvider($this, [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 20],
        ]);
    }

    /**
     * @param string $className
     * @return Import|CActiveRecord
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    protected function beforeSave()
    {
        if (!parent::beforeSave()) {
            return false;
        }

        if ($this->isNewRecord) {
            $this->status = self::STATUS_UPLOADED;
            $this->created = time();
        }
        $this->updated = time();

        return true;
    }

    protected function afterDelete()
    {
        parent::afterDelete();
    }

    public function isImported()
    {
        return $this->status == Import::STATUS_IMPORTED;
    }

    public function isUploaded()
    {
        return $this->status == Import::STATUS_UPLOADED;
    }
}
