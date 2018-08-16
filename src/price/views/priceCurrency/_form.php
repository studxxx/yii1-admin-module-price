<?php
/* @var $this CurrencyController */
/* @var $model Currency */
/* @var $form TbActiveForm */

$form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'currency-form',
    'enableAjaxValidation' => true,
    'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
]);

echo PriceModule::t('T_FIELD_REQUIRED');

echo $form->errorSummary($model);

echo $form->textFieldRow($model, 'code', ['class' => 'span12', 'maxlength' => 10]);

echo $form->textFieldRow($model, 'name', ['class' => 'span12', 'maxlength' => 255]);

echo $form->checkBoxRow($model, 'default');

echo $form->textFieldRow($model, 'value', ['class' => 'span12']);

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
