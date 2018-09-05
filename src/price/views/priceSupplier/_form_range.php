<?php
/* @var $range PriceRangeForm */
/* @var $form TbActiveForm */
/* @var $index int */
?>
<div class="control-group">
    <div class="controls">
        <?= $form->hiddenField($range, "[$index]id"); ?>
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
        <span class="help-inline">
            <?php if ($range->isNewRecord) : ?>
                <?= CHtml::link('<i class="icon-remove"></i>', 'javascript:void(0);', [
                    'class' => 'btn btn-link',
                    'onclick' => "$(this).parent().parent().parent().remove();",
                ]); ?>
            <?php else : ?>
                <?= CHtml::ajaxLink(
                    '<i class="icon-remove"></i>',
                    ['/price/priceRange/delete', 'id' => $range->id, 'ajax' => 'range-delete'],
                    [
//                        'beforeSend' =>
//'js:function(){
//    if(confirm("Are you sure you want to delete?")) {
//        return true;
//    }
//}',
                        'success' =>
'js:function(data){
    console.log($("range-' . $range->id . '").parent());
    $("range-' . $range->id . '").parent().parent().parent().remove();
}',
                        'type' => 'post',
                    ],
                    [
                        'id' => 'range-' . $range->id,
                        'class' => 'btn btn-link',
                        'title' => 'delete',
                        'href' => 'javascript:void(0);'
                    ]
                ); ?>
            <?php endif; ?>
        </span>
        <?= CHtml::error($range, "[$index]from"); ?>
        <?= CHtml::error($range, "[$index]to"); ?>
        <?= CHtml::error($range, "[$index]value"); ?>
    </div>
</div>
