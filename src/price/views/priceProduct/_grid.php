<?php
/* @var $searchModel PriceProductSearch */
/* @var $dataProvider CActiveDataProvider */

$this->widget('bootstrap.widgets.TbGridView', [
    'id' => 'price-products-grid',
    'dataProvider' => $dataProvider,
    'filter' => $searchModel,
    'template' => '{summary} {pager} {items} {pager}',
    'columns' => [
        'supplier_id' => [
            'name' => 'supplier_id',
            'value' => '$data->suppliers->name',
            'filter' => CHtml::listData(PriceSupplier::model()->findAll(), 'id', 'name'),
        ],
        'sku',
        'brand',
        'name',
        'price' => [
            'name' => 'price',
            'header' => PriceModule::t('MODEL_PRICE'),
            'value' => function ($data) {
                return $data->price;
            },
        ],
        'exist' => [
            'name' => 'exist',
            'value' => 'PriceProductHelper::getExist($data)',
            'filter' => PriceProductHelper::exists(),
            'type' => 'raw',
        ],
        'visible' => [
            'name' => 'visible',
            'value' => 'PriceProductHelper::getVisible($data)',
            'filter' => PriceProductHelper::visible(),
            'type' => 'raw',
        ],
//        'type' => [
//            'name' => 'type',
//            'value' => 'PriceProductHelper::getType($data)',
//            'filter' => PriceProductHelper::types(),
//            'type' => 'raw',
//        ],
        'updated_at' => [
            'name' => 'updated_at',
            'value' => 'DateHelper::datetime($data, "updated_at")',
        ],
        [
            'class' => 'bootstrap.widgets.TbButtonGroupColumn',
        ],
    ],
]);
