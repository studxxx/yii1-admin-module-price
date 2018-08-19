<?php
/* @var $template PriceTemplateForm */
/* @var $form TbActiveForm */
/* @var $index int */
?>
<div class="control-group">
    <div class="controls">
        <?= $form->textField($template, "[$index]coordinate", [
            'class' => 'span4',
            'placeholder' => PriceModule::t('coordinate')
        ]); ?>
        <?= $form->textField($template, "[$index]field_name", [
            'class' => 'span4',
            'placeholder' => PriceModule::t('field_name')
        ]); ?>
        <?= $form->textField($template, "[$index]validator", [
            'class' => 'span3',
            'placeholder' => PriceModule::t('validator')
        ]); ?>
        <?= CHtml::error($template, "[$index]coordinate"); ?>
        <?= CHtml::error($template, "[$index]field_name"); ?>
        <?= CHtml::error($template, "[$index]validator"); ?>
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
