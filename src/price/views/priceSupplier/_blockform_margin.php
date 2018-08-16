<?php
/* @var $margin RangeForm */
/* @var $form CActiveForm */
/* @var $index int */
?>
<div class="control-group">
    <!--    <label class="control-label" for="input1">Націнка</label>-->
    <div class="controls">
        <?= CHtml::activeTextField($margin, "[$index]condition", [
            'class' => 'span5', 'placeholder' => Yii::t('main', 'condition')
        ]); ?>
        <?= CHtml::activeTextField($margin, "[$index]value", [
            'class' => 'span5', 'placeholder' => Yii::t('main', 'value')
        ]); ?>
        <?= CHtml::error($margin, "[$index]condition"); ?>
        <?= CHtml::error($margin, "[$index]value"); ?>
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