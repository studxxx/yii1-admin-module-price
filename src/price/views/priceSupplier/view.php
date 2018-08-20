<?php
/* @var $this PriceSupplierController */
/* @var $model PriceSupplier */

$this->pageTitle = $model->name;
$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_SUPPLIERS') => ['index'],
    $this->pageTitle,
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => 'View ' . $model->id,
    'iconTitle' => 'icon-eye-open',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'portletMenu' => [
        [
            'label' => '<i class="icon-plus"></i>',
            'url' => ['create'],
            'linkOptions' => ['class' => 'btn btn-mini', 'title' => 'Create Suppliers']
        ],
        [
            'label' => '<i class="icon-pencil"></i>',
            'url' => ['update', 'id' => $model->id],
            'linkOptions' => ['class' => 'btn btn-mini', 'title' => 'Update Suppliers']
        ],
        [
            'label' => '<i class="icon-minus"></i>',
            'url' => '#',
            'linkOptions' => [
                'submit' => ['delete', 'id' => $model->id],
                'confirm' => 'Are you sure you want to delete this item?',
                'class' => 'btn btn-mini',
                'title' => 'Delete Suppliers',
            ],
        ],
    ],
]);

$this->widget('bootstrap.widgets.TbDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'name',
        'email',
        'phone',
        'currency',
        'description',
        'note',
        'created' => [
            'name' => 'created',
            'value' => DateHelper::datetime($model, 'created_at'),
        ],
        'updated' => [
            'name' => 'updated',
            'value' => DateHelper::datetime($model, 'updated_at'),
        ],
    ],
]);
$this->endWidget();