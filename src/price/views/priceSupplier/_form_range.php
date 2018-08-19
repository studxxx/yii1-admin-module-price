<?php
/* @var $range PriceRangeForm */
/* @var $form TbActiveForm */
/* @var $index int */
?>
<div class="control-group">
    <div class="controls">
        <?= $form->textField($range, "[$index]from", [
            'class' => 'span4',
            'placeholder' => PriceModule::t('from')
        ]); ?>
        <?= $form->textField($range, "[$index]to", [
            'class' => 'span4',
            'placeholder' => PriceModule::t('to')
        ]); ?>
        <?= $form->textField($range, "[$index]value", [
            'class' => 'span3',
            'placeholder' => PriceModule::t('value')
        ]); ?>
        <?= CHtml::error($range, "[$index]from"); ?>
        <?= CHtml::error($range, "[$index]to"); ?>
        <?= CHtml::error($range, "[$index]value"); ?>
        <span class="help-inline">
            <?= CHtml::link('<i class="icon-remove"></i>', 'javascript:void(0);', [
                'class' => 'btn btn-link',
                'onclick' => "
                    $(this).parent().parent().parent().remove();
                ",
            ]); ?>
        </span>
    </div>
</div>
