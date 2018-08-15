<?php
/* @var $this ImportController */
/* @var $model Import */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'import-form',
    'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    'enableAjaxValidation' => false,
    'htmlOptions' => ['enctype' => 'multipart/form-data']
]);

echo TbHtml::quote(
    PriceModule::t('NOTIFICATION_FIELD_REQUIRED')
);

echo $form->errorSummary($model);

echo $form->fileFieldRow($model, 'name');

echo $form->dropDownListRow($model, 'supplier', Suppliers::model()->getList(), [
    'prompt' => '----',
    'span' => 12
]);

echo TbHtml::formActions([
    $this->widget('bootstrap.widgets.TbButton', [
        'buttonType' => 'submit',
        'label' => $model->isNewRecord
            ? PriceModule::t('BUTTON_CREATE')
            : PriceModule::t('BUTTON_SAVE'),
    ], true)
]);

$this->endWidget();
