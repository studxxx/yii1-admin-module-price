<?php

/**
 * @property integer $id
 * @property integer $currency_id
 * @property string $name
 * @property string $title
 * @property string $email
 * @property string $phone
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceSupplierSearch extends CModel
{
    public function rules()
    {
        return [
            ['id, name, email, phone, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function attributeNames()
    {
        return [
            'id', 'currency_id', 'name', 'email', 'phone', 'created_at', 'updated_at'
        ];
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'id' => PriceModule::t('MODEL_ID'),
            'currency_id' => PriceModule::t('MODEL_CURRENCY'),
            'name' => PriceModule::t('MODEL_NAME'),
            'email' => PriceModule::t('MODEL_EMAIL'),
            'phone' => PriceModule::t('MODEL_PHONE'),
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

        $dataProvider = new CActiveDataProvider(PriceSupplier::class, [
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
        $criteria->compare('currency_id', $this->currency_id);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('email', $this->email, true);
        $criteria->compare('phone', $this->phone, true);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('updated_at', $this->updated_at);

        return $dataProvider;
    }
}
