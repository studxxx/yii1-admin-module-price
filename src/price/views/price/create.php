<?php
/* @var $this PriceController */
/* @var $model Price */

$this->pageTitle = PriceModule::t('T_MENU_CREATE');
$this->breadcrumbs = [
    PriceModule::t('T_MENU_PRICES') => ['index'],
    $this->pageTitle,
];



$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => PriceModule::t('T_MENU_ADD_PRICE'),
    'iconTitle' => 'icon-money',
    'contentCssClass' => 'widget-body form',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'portletMenu' => [[
        'label' => '<i class="icon-th-list"></i>',
        'url' => ['index'],
        'linkOptions' => [
            'class' => 'btn btn-mini',
            'title' => PriceModule::t('T_BUTTON_PRICES')
        ]
    ]],
]);

$this->renderPartial('_form', [
    'model' => $model,
]);

$this->endWidget();
