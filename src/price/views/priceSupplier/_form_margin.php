<?php
/* @var $form TbActiveForm */
/* @var $margin RangeForm */
/* @var $model PriceSupplier */
/* @var $id int */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'margin-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => ['class' => 'form-horizontal']
]); ?>

<?php if (!empty($model->margin)) : ?>
    <?php foreach ($model->margin as $index => $data) {
        $margin->attributes = $data;
        $this->renderPartial('_blockform_margin', [
            'index' => $index,
            'margin' => $margin,
        ]);
    } ?>
<?php else : ?>
    <?php $this->renderPartial('_blockform_margin', [
        'index' => 0,
        'margin' => $margin,
    ]); ?>
<?php endif; ?>

    <div class="form-actions">
        <?= CHtml::button(
            empty($model->margin)
                ? PriceModule::t('BUTTON_CREATE')
                : PriceModule::t('BUTTON_SAVE'),
            ['class' => 'btn btn-primary', 'onclick' => CHtml::ajax([
                'type' => 'POST',
                'url' => $this->createUrl("/console/suppliers/update", ['id' => $id]),
                'data' => 'js: $("#margin-form").serialize()',
                'dataType' => 'json',
                'success' => 'function(result){
                    GNotification({
                        title: "' . PriceModule::t('SUCCESS') . '",
                        text: "' . PriceModule::t('MARGIN_SAVE') . '",
                        sticky: true,
                        time: "",
                        class_name: "my-sticky-class"
                    },{
                        fade: true,
                        speed: "slow"
                    }, 5000, 500);
                }',
                'error' => 'function(result) {
                    GNotification({
                        title: "' . PriceModule::t('ERROR') . '",
                        text: "' . PriceModule::t('MARGIN_NOT_SAVE') . '",
                        sticky: true,
                        time: "",
                        class_name: "my-sticky-class"
                    },{
                        fade: true,
                        speed: "slow"
                    }, 5000, 500);
                }'
            ])
            ]); ?>
    </div>

<?php $this->endWidget(); ?>
<?php /*
<!--<form action="#" class="form-horizontal">-->
<!--    <div class="control-group">-->
<!--        <label class="control-label" for="input1">Націнка</label>-->
<!--        <div class="controls">-->
<!--            <input type="text" class="span5" id="input1" placeholder="condition">-->
<!--            <input type="text" class="span5" id="input1" placeholder="value">-->
<!--            <span class="help-inline">-->
<!--                --><?php //echo CHtml::link('<i class="icon-remove"></i>','javascript:void(0);',[
//                    'class'=>'btn btn-link',
//                    'onclick'=>"
//                        $(this).parent().parent().parent().remove();
//                    ",
//                ]);?>
<!--            </span>-->
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="form-actions">-->
<!--        <button type="submit" class="btn btn-primary">Save</button>-->
<!--        <button type="button" class="btn">Cancel</button>-->
<!--    </div>-->
<!--</form>-->*/