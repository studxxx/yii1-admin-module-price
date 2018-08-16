<?php
/* @var $this PriceSupplierController */
/* @var $model PriceSupplier */

$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_SUPPLIERS'),
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => 'List',
    'iconTitle' => 'icon-th-list',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'portletMenu' => [[
        'label' => '<i class="icon-plus"></i>',
        'url' => ['create'],
        'linkOptions' => ['class' => 'btn btn-mini', 'title' => PriceModule::t('T_CREATE_SUPPLIER')]
    ]],
]);

$this->widget('bootstrap.widgets.TbGridView', [
    'id' => 'suppliers-grid',
    'dataProvider' => $model->search(),
    'filter' => $model,
    'columns' => [
        'id',
        'name',
        'email',
        'phone',
        'currency_id',
        'created' => [
            'name' => 'created',
            'value' => 'DateHelper::datetime($data, "created_at")',
        ],
        'updated' => [
            'name' => 'updated',
            'value' => 'DateHelper::datetime($data, "updated_at")',
        ],
        [
            'class' => 'bootstrap.widgets.TbButtonColumn',
        ],
    ],
]);
$this->endWidget();
