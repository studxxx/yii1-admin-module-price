<?php
/* @var $this ImportController */
/* @var $model Import */

$this->breadcrumbs = [
    PriceModule::t('T_IMPORTS') => ['index'],
    $model->name,
];

$this->menu = [
    [
        'label' => '<i class="icon-upload-alt"></i> ' . Yii::t('PriceModule.main', 'T_IMPORTS') . '<span class="arrow"></span>',
        'url' => 'javascript:void(0);',
        'visible' => Yii::app()->user->checkAccess(Users::ROLE_MANAGER),
        'items' => [
            [
                'label' => PriceModule::t('MENU_LIST'),
                'url' => ['/console/import/index'],
                'visible' => Yii::app()->user->checkAccess(Users::ROLE_MANAGER),
            ],
            [
                'label' => PriceModule::t('MENU_ADD'),
                'url' => ['/console/import/create'],
                'visible' => Yii::app()->user->checkAccess(Users::ROLE_MANAGER),
            ]
        ],
        'itemOptions' => ['class' => 'has-sub',],
        'submenuOptions' => ['class' => 'sub'],
    ]
];

$menu = [
    [
        'label' => '<i class="icon-th-list"></i>',
        'url' => ['index'],
        'linkOptions' => [
            'class' => 'btn btn-mini tooltip-bottom',
            'title' => 'List imports',
            'data-toggle' => "tooltip",
        ]
    ],
    [
        'label' => '<i class="icon-plus"></i>',
        'url' => ['create'],
        'linkOptions' => [
            'class' => 'btn btn-mini tooltip-bottom',
            'title' => 'Add import file',
            'data-toggle' => "tooltip",
        ]
    ],
    [
        'label' => '<i class="icon-trash"></i>',
        'url' => '#',
        'linkOptions' => [
            'class' => 'btn btn-mini tooltip-bottom',
            'title' => 'Delete Import',
            'data-toggle' => "tooltip",
            'submit' => ['delete', 'id' => $model->id],
            'confirm' => 'Are you sure you want to delete this item?']
    ]
];

$this->beginWidget('app.components.widgets.WPortlet', [
    'title' => 'Items',
    'iconTitle' => 'icon-home',
    'portletMenu' => $menu,
]);

$this->widget('bootstrap.widgets.TbDetailView', [
    'data' => $model,
    'attributes' => [
        'id',
        'name',
        'output_file',
        'suppliers.name',
        'status' => [
            'name' => 'status',
            'value' => ImportHelper::getStatus($model),
            'type' => 'raw'
        ],
        'created:ImportHelper::date($model, \'created\')' => [
            'name' => 'created',
            'value' => ImportHelper::date($model, 'created')
        ],
        'updated' => [
            'name' => 'updated',
            'value' => ImportHelper::date($model, 'updated')
        ],
    ],
]);

$this->endWidget();
