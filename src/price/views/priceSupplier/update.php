<?php
/**
 * @var $this PriceSupplierController
 * @var $model PriceSupplier
 * @var $margin RangeForm
 * @var $template TemplateForm
 */

$this->breadcrumbs = [
    PriceModule::t('MENU_PRICES') => ['price/index'],
    PriceModule::t('MENU_SUPPLIERS') => ['index'],
    $model->name => ['view', 'id' => $model->id],
    PriceModule::t('MENU_UPDATE'),
];

$this->widget('ext.theme-widgets.gritter.EGritter');
?>

<div class="row-fluid">
    <div class="span12">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'title' => PriceModule::t('UPDATE_SUPPLIER', [
                '{name}' => $model->name
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
                    'url' => ['view', 'id' => $model->id],
                    'linkOptions' => ['class' => 'btn btn-mini', 'title' => PriceModule::t('VIEW_SUPPLIERS')]
                ],
            ],
        ]);

        $this->renderPartial('_form', [
            'model' => $model
        ]);

        $this->endWidget(); ?>
    </div>
</div>

<div class="row-fluid">
    <div class="span6">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'title' => PriceModule::t('MARGIN_PRICE'),
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
            'contentCssClass' => 'widget-body form',
            'portletMenu' => [[
                'label' => '<i class="icon-plus"></i>',
                'url' => 'javascript:void(0);',
                'linkOptions' => ['class' => 'btn btn-mini', 'title' => PriceModule::t('ADD_CONDITION'), 'onclick' => CHtml::ajax([
                    'type' => 'GET',
                    'url' => $this->createUrl('/ajax/marginblock'),
                    'data' => [
                        'total' => 'js: $("#margin-form .controls").length',
                    ],
                    'dataType' => 'html',
                    'success' => 'function(result){
                            $("#margin-form").prepend(result);
                        }',
                ])
                ]
            ],],
        ]);

        $this->renderPartial('_form_margin', [
            'model' => $model,
            'margin' => $margin,
            'id' => $model->id,
        ]);

        $this->endWidget(); ?>
    </div>
    <div class="span6">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'title' => PriceModule::t('TEMPLATE_IMPORT_PRICE'),
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
            'contentCssClass' => 'widget-body form',
        ]);

        $this->renderPartial('_form_tmpl', [
            'template' => $template,
            'id' => $model->id,
        ]);

        $this->endWidget(); ?>
    </div>
</div>


