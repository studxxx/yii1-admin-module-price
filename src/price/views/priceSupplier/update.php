<?php
/* @var $this PriceSupplierController */
/* @var $supplier PriceSupplier */
/* @var $range PriceRangeForm */
/* @var $template PriceTemplateForm */

$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_SUPPLIERS') => ['index'],
    $supplier->name => ['view', 'id' => $supplier->id],
    PriceModule::t('MENU_UPDATE'),
];

$this->widget('ext.theme-widgets.gritter.EGritter');
?>

<div class="row-fluid">
    <div class="span12">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'title' => PriceModule::t('UPDATE_SUPPLIER', [
                '{name}' => $supplier->name
            ]),
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
            'contentCssClass' => 'widget-body form',
            'portletMenu' => [
                [
                    'label' => '<i class="icon-plus"></i>',
                    'url' => ['create'],
                    'linkOptions' => ['class' => 'btn btn-mini', 'title' => PriceModule::t('CREATE_SUPPLIERS')]
                ],
                [
                    'label' => '<i class="icon-pencil"></i>',
                    'url' => ['view', 'id' => $supplier->id],
                    'linkOptions' => ['class' => 'btn btn-mini', 'title' => PriceModule::t('VIEW_SUPPLIERS')]
                ],
                [
                    'label' => '<i class="icon-trash"></i>',
                    'url' => ['delete', 'id' => $supplier->id],
                    'linkOptions' => ['class' => 'btn btn-mini', 'title' => PriceModule::t('T_DELETE_SUPPLIER')]
                ],
            ],
        ]);

        $this->renderPartial('_form', [
            'model' => $model,
        ]);

        $this->endWidget(); ?>
    </div>
</div>
