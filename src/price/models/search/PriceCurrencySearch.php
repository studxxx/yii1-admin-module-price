<?php

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property integer $default
 * @property integer $created_at
 * @property integer $updated_at
 */
class PriceCurrencySearch extends CModel
{
    public $id;
    public $code;
    public $name;
    public $value;
    public $default;
    public $created_at;
    public $updated_at;

    public function rules()
    {
        return [
            ['value', 'numerical'],
            ['default, created_at, updated_at', 'numerical', 'integerOnly' => true],
            ['code, name', 'length', 'max' => 10],
            ['id, code, name, value, created_at, updated_at', 'safe', 'on' => 'search'],
        ];
    }

    public function attributeNames()
    {
        return ['id', 'code', 'name', 'value', 'default', 'created_at', 'updated_at'];
    }


    public function attributeLabels()
    {
        return array(
            'id' => PriceModule::t('T_ID'),
            'code' => PriceModule::t('T_CODE'),
            'name' => PriceModule::t('T_NAME'),
            'value' => PriceModule::t('T_VALUE'),
            'default' => PriceModule::t('T_DEFAULT'),
            'created_at' => PriceModule::t('T_CREATED_AT'),
            'updated_at' => PriceModule::t('T_UPDATED_AT'),
        );
    }

    /**
     * @param array|null $params
     * @return CActiveDataProvider
     */
    public function search($params = null)
    {
        $criteria = new CDbCriteria;

        $dataProvider = new CActiveDataProvider(PriceCurrency::class, [
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

        $criteria = new CDbCriteria;

        $criteria->compare('id', $this->id);
        $criteria->compare('code', $this->code, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('value', $this->value, true);
        $criteria->compare('value', $this->default);
        $criteria->compare('created_at', $this->created_at);
        $criteria->compare('updated_at', $this->updated_at);

        return $dataProvider;
    }
}
