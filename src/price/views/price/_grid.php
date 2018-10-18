<?php
/* @var $this PriceController */
/* @var $model Price */

$this->widget('bootstrap.widgets.TbGridView', [
    'id' => 'import-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'supplier_id' => [
            'name' => 'supplier_id',
            'value' => '$data->supplier->name',
            'filter' => CHtml::listData(PriceSupplier::model()->findAll(), 'id', 'name'),
        ],
        'status' => [
            'name' => 'status',
            'value' => 'PriceHelper::getStatus($data)',
            'filter' => PriceHelper::statuses(),
            'type' => 'raw',
        ],
        [
            'class' => 'bootstrap.widgets.TbButtonGroupColumn',
            'template' => '{download} {import} {update_price} {import_products}',
            'buttons' => [
//                'import' => [
//                    'label' => '<i class="icon-white icon-download-alt"></i>',
//                    'url' => function ($data) {
//                        return $this->createUrl('price', [
//                            'id' => $data->id
//                        ]);
//                    },
//                    'type' => 'success',
//                    'options' => [
//                        'title' => PriceModule::t('T_IMPORT_PRICE'),
//                        'class' => 'btn btn-mini',
//                        'ajax' => [
//                            'type' => 'GET',
//                            'dataType' => 'json',
//                            'url' => 'js:$(this).attr("href")',
//                            'success' => 'js:startImport'
//                        ]
//                    ],
//                ],
                'download' => [
                    'label' => '<i class="icon-white icon-download-alt"></i>',
                    'url' => function (Price $data) {
                        return $this->createUrl('download', [
                            'id' => $data->id
                        ]);
                    },
                    'type' => 'success',
                    'options' => [
                        'title' => PriceModule::t('T_DOWNLOAD_PRICE'),
                        'class' => 'btn btn-mini',
                    ],
                ],
            ],
        ],
        'created_at' => [
            'name' => 'created_at',
            'value' => 'DateHelper::datetime($data, "created_at")',
        ],
        'updated_at' => [
            'name' => 'updated_at',
            'value' => 'DateHelper::datetime($data, "updated_at")',
        ],
        [
            'class' => 'bootstrap.widgets.TbButtonGroupColumn',
            'template' => '{view} {delete}',
        ],
    ],
]);
