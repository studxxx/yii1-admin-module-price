<?php
/* @var $this PriceController */
/* @var $model Price */

$this->pageTitle = PriceModule::t('T_MENU_PRICES');
$this->breadcrumbs = [
    $this->pageTitle,
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
    'title' => PriceModule::t('T_PRICES'),
    'iconTitle' => 'icon-list-alt',
    'portletMenu' => [
        [
            'label' => '<i class="icon-plus"></i>',
            'url' => ['create'],
            'linkOptions' => [
                'class' => 'btn btn-mini tooltip-bottom',
                'title' => PriceModule::t('T_BUTTON_ADD_PRICE'),
                'data-toggle' => "tooltip",
                'id' => 'btn-add-price'
            ],
            'visible' => Yii::app()->user->checkAccess(Users::ROLE_ADMIN)
        ],
        [
            'label' => '<i class="icon-download"></i>',
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
                'title' => PriceModule::t('T_BUTTON_IMPORT_PRODUCTS'),
                'data-toggle' => "tooltip",
                'id' => 'btn-import-products'
            ],
            'type' => 'ajaxLink'
        ],
    ],
]);

$this->renderPartial('_grid', [
    'model' => $model
]);

$this->endWidget();
