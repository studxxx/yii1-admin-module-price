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
            <?php if ($template->isNewRecord) : ?>
                <?= CHtml::link('<i class="icon-remove"></i>', 'javascript:void(0);', [
                    'class' => 'btn btn-link',
                    'onclick' => "$(this).parent().parent().parent().remove();",
                ]); ?>
            <?php else : ?>
                <?= CHtml::ajaxLink(
                    '<i class="icon-remove"></i>',
                    ['/price/priceTemplate/delete', 'id' => $template->id, 'ajax' => 'template-delete'],
                    [
//                        'beforeSend' =>
//'js:function(){
//    if(confirm("Are you sure you want to delete?")) {
//        return true;
//    }
//}',
                        'success' =>
                            'js:function(data){
                                $("#template-' . $template->id . '").parent().parent().parent().remove();
                            }',
                        'type' => 'post',
                    ],
                    [
                        'id' => 'template-' . $template->id,
                        'class' => 'btn btn-link',
                        'title' => 'delete',
                        'href' => 'javascript:void(0);'
                    ]
                ); ?>
            <?php endif; ?>
        </span>
        <?= CHtml::error($template, "[$index]coordinate"); ?>
        <?= CHtml::error($template, "[$index]field_name"); ?>
        <?= CHtml::error($template, "[$index]validator"); ?>
    </div>
</div>
