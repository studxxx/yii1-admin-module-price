<?php
/* @var $this ImportController */
/* @var $model Import */

$this->widget('bootstrap.widgets.TbGridView', [
    'id' => 'import-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'name',
        'supplier' => [
            'name' => 'supplier',
            'value' => '$data->suppliers->name',
            'filter' => Suppliers::model()->getList(),
        ],
        'status' => [
            'name' => 'status',
            'value' => 'ImportHelper::getStatus($data)',
            'filter' => ImportHelper::statuses(),
            'type' => 'raw',
        ],
        [
            'class' => 'bootstrap.widgets.TbButtonGroupColumn',
            'template' => '{import} {update_price} {import_products}',
            'buttons' => [
                'import' => [
                    'label' => '<i class="icon-white icon-download-alt"></i>',
                    'url' => function ($data) {
                        return $this->createUrl('price', [
                            'id' => $data->id
                        ]);
                    },
                    'type' => 'success',
                    'options' => [
                        'title' => PriceModule::t('T_IMPORT_PRICE'),
                        'class' => 'btn btn-mini',
                        'ajax' => [
                            'type' => 'GET',
                            'dataType' => 'json',
                            'url' => 'js:$(this).attr("href")',
                            'success' => 'js:startImport'
                        ]
                    ],
                ],
                'import_products' => [
                    'label' => '<i class="icon-white icon-upload-alt"></i>',
                    'url' => function ($data) {
                        return Yii::app()->createUrl("/console/import/products", [
                            'id' => $data->id
                        ]);
                    },
                    'type' => 'info',
                    'options' => [
                        'title' => PriceModule::t('T_IMPORT_PRODUCTS'),
                        'class' => 'btn btn-mini',
                        'ajax' => [
                            'type' => 'GET',
                            'dataType' => 'json',
                            'url' => 'js:$(this).attr("href")',
                            'success' => 'js:updateProducts'
                        ]
                    ],
                ],
            ],
        ],
        'created:datetime:U',
        'updated:datetime',
        [
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{view} {delete}',
        ],
    ],
]);
