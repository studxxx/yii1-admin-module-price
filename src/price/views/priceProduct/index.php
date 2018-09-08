<?php
/* @var $this PriceProductController */
/* @var $searchModel PriceProductSearch */
/* @var $dataProvider CActiveDataProvider */

$this->pageTitle = PriceModule::t('T_PRICE');
$this->breadcrumbs = [
    PriceModule::t('T_PRICES') => ['/price/price/index'],
    PriceModule::t('T_PRICE')
];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function () { $('.search-form').toggle(); return false; });

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

//Yii::app()->clientScript->registerScript('search', "
////$('.search-form form').submit(function(){
////	$.fn.yiiGridView.update('page-grid', {
////		data: $(this).serialize()
////	});
////	return false;
////});
//");
$this->widget('ext.theme-widgets.gritter.EGritter');

$this->beginWidget('application.components.widgets.WPortlet', [
    'title' => PriceModule::t('T_PRICE'),
    'iconTitle' => 'icon-barcode',
    'hideConfigButton' => true,
    'hideRefreshButton' => true,
    'portletMenu' => [
        [
            'label' => '<i class="icon-filter"></i>',
            'url' => 'javascript:;',
            'linkOptions' => [
                'class' => 'btn btn-mini btn-info tooltip-bottom search-button',
                'style' => 'color:#fff',
                'title' => PriceModule::t('T_BUTTON_FILTER_PRODUCTS'),
                'data-toggle' => "tooltip",
                'id' => 'btn-filter-products'
            ],
        ],
    ],
]); ?>

<?php if (user()->hasFlash('index')) : ?>
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Success!</strong> <?= user()->getFlash('index'); ?>
    </div>
<?php endif; ?>

<?php $this->renderPartial('_search', [
    'model' => $searchModel,
]);?>

<?php $this->renderPartial('_grid', [
    'searchModel' => $searchModel,
    'dataProvider' => $dataProvider
]);

$this->endWidget();
