<?php
/* @var $this ImportController */
/* @var $model Import */

$this->breadcrumbs = [
    PriceModule::t('MENU_IMPORTS') => ['index'],
    PriceModule::t('MENU_CREATE'),
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

$this->renderPartial('_form', [
    'model' => $model
]);
