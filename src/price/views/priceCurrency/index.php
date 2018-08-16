<?php
/* @var $this PriceCurrencyController */
/* @var $model PriceCurrency */

$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_CURRENCIES')
];

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => PriceModule::t('MENU_CURRENCIES'),
    'iconTitle' => 'icon-money',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'portletMenu' => [[
        'label' => '<i class="icon-plus"></i>',
        'url' => ['create'],
        'linkOptions' => ['class' => 'btn btn-mini', 'title' => 'Create Currency'],
        'visible' => Yii::app()->user->checkAccess(Users::ROLE_ADMIN)
    ]],
]);

$this->widget('bootstrap.widgets.TbGridView', [
    'id' => 'currency-grid',
    'dataProvider' => $model->search(),
    'columns' => [
        'id',
        'code',
        'name',
        'value',
        'default',
        'created_at' => [
            'name' => 'created_at',
            'value' => 'DateHelper::datetime($data, "created_at")',
        ],
        'updated_at' => [
            'name' => 'updated_at',
            'value' => 'DateHelper::datetime($data, "updated_at")',
        ],
        [
            'class' => 'bootstrap.widgets.TbButtonColumn',
            'template' => '{update}',
        ],
    ],
]);

$this->endWidget();
