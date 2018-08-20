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
class PriceSearch extends CModel
{
    public $id;
    public $supplier_id;
    public $price_file;
    public $csv_file;
    public $status;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            ['id, price_file, supplier_id, status, created_at, updated_at', 'safe'],
        ];
    }

    public function attributeNames()
    {
        return [
            'id', 'supplier_id', 'price_file', 'csv_file', 'status', 'created_at', 'updated_at'
        ];
    }

    public function attributeLabels()
    {
        return array(
            'id' => PriceModule::t('MODEL_ID'),
            'price_file' => PriceModule::t('MODEL_PRICE_FILE'),
            'csv_file' => PriceModule::t('MODEL_CSV_FILE'),
            'supplier_id' => PriceModule::t('MODEL_SUPPLIER'),
            'status' => PriceModule::t('MODEL_STATUS_PRICE'),
            'created_at' => PriceModule::t('MODEL_CREATED_AT'),
            'updated_at' => PriceModule::t('MODEL_UPDATED_AT'),
        );
    }

    /**
     * @param array|null $params
     * @return CActiveDataProvider
     */
    public function search($params = null)
    {
        $criteria = new CDbCriteria;

        $dataProvider = new CActiveDataProvider(Price::class, [
            'criteria' => $criteria,
            'pagination' => ['pageSize' => 20],
        ]);

        if ($params) {
            $this->attributes = $params;
        }

        if (!$this->validate()) {
            $this->unsetAttributes();
            return $dataProvider;
        }

        $criteria->compare('id', $this->id);
        $criteria->compare('price_file', $this->price_file, true);
        $criteria->compare('supplier_id', $this->supplier_id);
        $criteria->compare('status', $this->status);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('updated_at', $this->updated_at);
        $criteria->order = 'status ASC';

        return $dataProvider;
    }
}
