<?php

/**
 * @property integer $id
 * @property string $code
 * @property string $name
 * @property string $value
 * @property integer $default
 * @property integer $created
 * @property integer $updated
 */
class PriceCurrencySearch extends CModel
{
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
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'value' => 'Value',
            'default' => 'Default',
            'created_at' => 'Created',
            'updated_at' => 'Updated',
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
        $criteria->compare('created', $this->created);
        $criteria->compare('updated', $this->updated);

        return $dataProvider;
    }
}
