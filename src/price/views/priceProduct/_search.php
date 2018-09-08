<?php
/* @var $this PriceProductController */
/* @var $model PriceProduct */
/* @var $form TbActiveForm */
?>

<div class="wide form search-form" style="display: none">

    <?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
        'action' => Yii::app()->createUrl($this->route),
        'method' => 'get',
        'type' => TbHtml::FORM_LAYOUT_HORIZONTAL,
    ]); ?>

    <?= $form->textFieldRow($model, 'sku', ['class' => 'span12', 'maxlength' => 255,]); ?>

    <?= $form->dropDownListRow(
        $model,
        'supplier_id',
        CHtml::listData(PriceSupplier::model()->findAll(), 'id', 'name'),
        ['class' => 'span12', 'maxlength' => 255,]
    ); ?>

    <?= $form->dropDownListRow(
        $model,
        'brand',
        CHtml::listData(PriceProduct::model()->findAll(), 'id', 'name'),
        ['class' => 'span12', 'maxlength' => 255,]
    ); ?>

    <?php $this->endWidget(); ?>

</div>