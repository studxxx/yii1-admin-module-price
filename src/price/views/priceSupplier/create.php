<?php
/* @var $this PriceSupplierController */
/* @var $model PriceSupplier */
/* @var $currencies array */

$this->pageTitle = PriceModule::t('MENU_CREATE');
$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_SUPPLIERS') => ['index'],
    $this->pageTitle,
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => PriceModule::t('T_CREATE_SUPPLIER'),
    'iconTitle' => 'icon-home',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'contentCssClass' => 'widget-body form',
]);

$this->renderPartial('_form', [
    'model' => $model,
    'currencies' => $currencies,
]);

$this->endWidget();
