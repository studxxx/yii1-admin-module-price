<?php
/* @var $this PriceSupplierController */
/* @var $model PriceSupplier */
/* @var $form TbActiveForm */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'suppliers-form',
    'enableAjaxValidation' => false,
    'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
]); ?>

<?= PriceModule::t('NOTIFICATION_FIELD_REQUIRED') ?>

<?= $form->errorSummary($model); ?>

<?= $form->textFieldRow($model, 'name', ['class' => 'span12', 'maxlength' => 255,]); ?>

<?= $form->textFieldRow($model, 'email', ['class' => 'span12', 'maxlength' => 255,]); ?>

<?= $form->dropDownListRow($model, 'currency_id', ['uah' => 'UAH', 'eur' => 'EUR', 'usd' => 'USD', 'rub' => 'RUB'], ['class' => 'span12',]); ?>

<?= $form->textFieldRow($model, 'phone', ['class' => 'span12', 'maxlength' => 18,]); ?>

<?= $form->textAreaRow($model, 'description', ['class' => 'span12', 'rows' => 6]); ?>

<?= $form->textAreaRow($model, 'note', ['class' => 'span12', 'rows' => 6]); ?>

    <div class="form-actions">
        <?= CHtml::submitButton(
            $model->isNewRecord
                ? PriceModule::t('BUTTON_CREATE')
                : PriceModule::t('BUTTON_SAVE'),
            ['class' => 'btn btn-primary']
        ); ?>
    </div>

<?php $this->endWidget(); ?>