<?php
/* @var $this PriceSupplierController */
/* @var $model PriceSupplier */
/* @var $currencies array */
$this->pageTitle = PriceModule::t('MENU_SUPPLIERS');
$this->breadcrumbs = [
    PriceModule::t('T_MENU_PRICES') => ['price/index'],
    $this->pageTitle,
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => PriceModule::t('MENU_SUPPLIERS'),
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
        'currency_id' => [
            'name' => 'currency_id',
            'value' => function (PriceSupplier $data) {
                return $data->currency->name;
            },
            'filter' => $currencies,
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
        ],
    ],
]);
$this->endWidget();
