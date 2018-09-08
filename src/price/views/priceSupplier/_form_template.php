<?php
/* @var $template PriceTemplateForm */
/* @var $form TbActiveForm */
/* @var $index int */
?>
<div class="control-group">
    <div class="controls">
        <?= $form->hiddenField($template, "[$index]id"); ?>
        <?= $form->textField($template, "[$index]coordinate", [
            'class' => 'span4',
            'placeholder' => PriceModule::t('coordinate')
        ]); ?>
        <?= $form->dropDownList($template, "[$index]field_name", PriceTemplateHelper::fields(), [
            'prompt' => '----',
            'class' => 'span4',
            'placeholder' => PriceModule::t('field_name')
        ]); ?>
        <?= $form->dropDownList($template, "[$index]validator", PriceTemplateHelper::validators(), [
            'prompt' => '----',
            'class' => 'span3',
            'placeholder' => PriceModule::t('validator')
        ]); ?>
        <span class="help-inline">
            <?= CHtml::link('<i class="icon-remove"></i>', 'javascript:void(0);', [
                'class' => 'btn btn-link',
                'onclick' => "$(this).parent().parent().parent().remove();",
            ]); ?>
        </span>
        <?= CHtml::error($template, "[$index]coordinate"); ?>
        <?= CHtml::error($template, "[$index]field_name"); ?>
        <?= CHtml::error($template, "[$index]validator"); ?>
    </div>
</div>
