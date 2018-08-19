<?php
/* @var $this PriceSupplierController */
/* @var $model PriceSupplierForm */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'suppliers-form',
    'enableAjaxValidation' => false,
    'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
]);

echo PriceModule::t('T_FIELD_REQUIRED');

echo $form->errorSummary($model);

echo $form->textFieldRow($model, 'name', ['class' => 'span12', 'maxlength' => 255,]);

echo $form->textFieldRow($model, 'email', ['class' => 'span12', 'maxlength' => 255,]);

echo $form->dropDownListRow($model->currency, 'currency', $model->currency->currenciesList(), [
    'class' => 'span12',
]);

echo $form->textFieldRow($model, 'phone', ['class' => 'span12', 'maxlength' => 18,]);

echo $form->textAreaRow($model, 'description', ['class' => 'span12', 'rows' => 6]);

echo $form->textAreaRow($model, 'note', ['class' => 'span12', 'rows' => 6]);?>

<div class="row-fluid">
    <div class="span12">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'id' => 'form-ranges',
            'title' => PriceModule::t('T_RANGE_PRICE'),
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
            'contentCssClass' => 'widget-body form js-ranges',
            'portletMenu' => [[
                'label' => '<i class="icon-plus"></i>',
                'url' => 'javascript:void(0);',
                'linkOptions' => [
                    'class' => 'btn btn-mini',
                    'title' => PriceModule::t('T_ADD_RANGE'),
                    'onclick' => CHtml::ajax([
                        'type' => 'GET',
                        'url' => $this->createUrl('/price/priceSupplier/rangeRow'),
                        'data' => [
                            'total' => 'js: $("#form-ranges .controls").length',
                        ],
                        'dataType' => 'html',
                        'success' => 'function(result){$("#form-ranges .js-ranges").append(result);}',
                    ])
                ]
            ],],
        ]); ?>

        <?php foreach ($model->ranges as $index => $range) : ?>
            <?php $this->renderPartial('_form_range', [
                'form' => $form,
                'index' => $index,
                'range' => $range,
            ])?>
        <?php endforeach; ?>

        <?php $this->endWidget(); ?>
    </div>
</div>

<div class="row-fluid">
    <div class="span12">
        <?php $this->beginWidget('application.components.widgets.WPortlet', [
            'id' => 'form-templates',
            'title' => PriceModule::t('TEMPLATE_IMPORT_PRICE'),
            'iconTitle' => 'icon-home',
            'hideConfigButton' => true,
            'hideRefreshButton' => true,
            'contentCssClass' => 'widget-body form js-templates',
            'portletMenu' => [[
                'label' => '<i class="icon-plus"></i>',
                'url' => 'javascript:void(0);',
                'linkOptions' => [
                    'class' => 'btn btn-mini',
                    'title' => PriceModule::t('T_ADD_TEMPLATE'),
                    'onclick' => CHtml::ajax([
                        'type' => 'GET',
                        'url' => $this->createUrl('/price/priceSupplier/templateRow'),
                        'data' => [
                            'total' => 'js: $("#form-templates .controls").length',
                        ],
                        'dataType' => 'html',
                        'success' => 'function(result){
                                $("#form-templates .js-templates").append(result);
                            }',
                    ])
                ]
            ]],
        ]); ?>

        <?php foreach ($model->templates as $index => $template) : ?>
            <?php $this->renderPartial('_form_template', [
                'form' => $form,
                'index' => $index,
                'template' => $template,
            ])?>
        <?php endforeach; ?>

        <?php $this->endWidget(); ?>
    </div>
</div>

<?= TbHtml::formActions([
    $this->widget('bootstrap.widgets.TbButton', [
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => PriceModule::t('BUTTON_SAVE'),
    ], true)
]);

$this->endWidget();
