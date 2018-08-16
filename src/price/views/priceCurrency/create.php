<?php
/* @var $this CurrencyController */
/* @var $model Currency */

$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_CURRENCIES') => ['index'],
    PriceModule::t('MENU_CREATE'),
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => PriceModule::t('MENU_CREATE_CURRENCY'),
    'iconTitle' => 'icon-money',
    'contentCssClass' => 'widget-body form',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'portletMenu' => [[
        'label' => '<i class="icon-th-list"></i>',
        'url' => ['index'],
        'linkOptions' => ['class' => 'btn btn-mini', 'title' => 'List Currency']
    ]],
]);

$this->renderPartial('_form', [
    'model' => $model
]);
$this->endWidget();