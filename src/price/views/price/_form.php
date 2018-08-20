<?php
/* @var $this ImportController */
/* @var $model Price */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'import-form',
    'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'enableAjaxValidation' => true,
    'htmlOptions' => ['enctype' => 'multipart/form-data']
]);

echo TbHtml::quote(
    PriceModule::t('T_FIELD_REQUIRED')
);

echo $form->errorSummary($model);

echo $form->fileFieldRow($model, 'price_file');

echo $form->dropDownListRow(
    $model,
    'supplier_id',
    CHtml::listData(PriceSupplier::model()->findAll(), 'id', 'name'),
    [
        'prompt' => '----',
        'span' => 12
    ]
);

echo TbHtml::formActions([
    $this->widget('bootstrap.widgets.TbButton', [
        'buttonType' => 'submit',
        'type' => 'primary',
        'label' => $model->isNewRecord
            ? PriceModule::t('BUTTON_CREATE')
            : PriceModule::t('BUTTON_SAVE'),
    ], true)
]);

$this->endWidget();
