<?php
/* @var $this PriceController */
/* @var $model Price */

$this->pageTitle = $model->price_file;
$this->breadcrumbs = [
    PriceModule::t('T_PRICES') => ['index'],
    $this->pageTitle,
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
        'price_file',
        [
            'type' => 'raw',
            'name' => 'supplier_id',
            'value' => function ($data) {
                return $data->suppliers->name;
            },
        ],
        'status' => [
            'name' => 'status',
            'value' => PriceHelper::getStatus($model),
            'type' => 'raw'
        ],
        'created_at' => [
            'name' => 'created_at',
            'value' => PriceHelper::date($model, 'created_at')
        ],
        'updated_at' => [
            'name' => 'updated_at',
            'value' => PriceHelper::date($model, 'updated_at')
        ],
    ],
]);

$this->endWidget();
