<?php
/* @var $this ImportController */
/* @var $model Import */

$this->breadcrumbs = [
    PriceModule::t('T_IMPORTS'),
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
        'label' => PriceModule::t('MENU_IMPORT_PRODUCT'),
        'url' => $this->createUrl('products'),
        'ajaxOption' => [
            'type' => 'POST',
            'success' => 'startImportProductsSuccess',
            'data' => ['import' => 1],
            'cache' => 'false',
            'dataType' => 'json'
        ],
        'linkOptions' => [
            'class' => 'btn btn-mini tooltip-bottom',
            'title' => 'Import Products',
            'data-toggle' => "tooltip",
            'id' => 'btn-import-products'
        ],
        'type' => 'ajaxLink'
    ],
];

Yii::app()->clientScript->registerScript('search', "
var noticeMessage = function(title, message) {
    GNotification({title: title, text: message, sticky: true, time: '', class_name: 'my-sticky-class' },{fade: true, speed: 'slow'}, 5000, 500);
    return false;
}
var startImportProductsSuccess = function(data) {
    var title = data.message + (data.error == 0 ? '' : ' Er.No: ' + data.error);
    var message = data.message + (data.error == 0 ? '' : ' Send mail to administrator');
    noticeMessage(title, message);
};
var startImport = function(data) {
    $('#AjFlash').html(data).fadeIn().animate({opacity: 1.0}, 3000).fadeOut('slow');
    $.fn.yiiGridView.update('import-grid');

    var title = data.message + (data.error == 0 ? '' : ' Er.No: ' + data.error);
    var message = data.message + (data.error == 0 ? '' : ' Send mail to administrator');
    noticeMessage(title, message);
}");
$this->widget('ext.theme-widgets.gritter.EGritter');

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => 'List imports',
    'iconTitle' => 'icon-list-alt',
    'portletMenu' => $menu,
]);

$this->renderPartial('_grid', [
    'model' => $model
]);

$this->endWidget();
