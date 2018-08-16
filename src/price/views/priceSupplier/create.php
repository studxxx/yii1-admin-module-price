<?php
/* @var $this PriceSupplierController */
/* @var $model PriceSupplier */

$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_SUPPLIERS') => ['index'],
    PriceModule::t('MENU_CREATE'),
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => 'Create Supplier',
    'iconTitle' => 'icon-home',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'contentCssClass' => 'widget-body form',
]);

$this->renderPartial('_form', [
    'model' => $model
]);

$this->endWidget();
