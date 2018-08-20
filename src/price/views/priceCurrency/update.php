<?php
/* @var $this CurrencyController */
/* @var $model Currency */

$this->breadcrumbs = [
    PriceModule::t('MENU_CURRENCIES') => ['index'],
    PriceModule::t('MENU_UPDATE'),
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => 'Update Currency: ' . mb_strtoupper($model->name),
    'iconTitle' => 'icon-money',
    'contentCssClass' => 'widget-body form',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'portletMenu' => [
        [
            'label' => '<i class="icon-th-list"></i>',
            'url' => ['index'],
            'linkOptions' => ['class' => 'btn btn-mini', 'title' => 'List Currency']
        ],
        [
            'label' => '<i class="icon-plus"></i>',
            'url' => ['create'],
            'linkOptions' => ['class' => 'btn btn-mini', 'title' => 'Create Currency']
        ],
    ],
]);

$this->renderPartial('_form', [
    'model' => $model
]);
$this->endWidget();
