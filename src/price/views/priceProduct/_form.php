<?php
/**
 * @var $this PriceProductController
 * @var $model PriceProduct
 * @var $form TbActiveForm
 */
?>

<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'goods-form',
    'enableAjaxValidation' => false,
]); ?>

<?= TbHtml::tag('p', ['class' => 'note'], ConsoleModule::t('NOTIFICATION_FIELD_REQUIRED')); ?>

<?= $form->errorSummary($model); ?>

<?= $form->textFieldRow($model, 'name', ['maxlength' => 255, 'class' => 'span12']); ?>

    <div class="controls controls-row">
        <div class="span10">
            <?= $form->select2Row($model, 'category', [
                'data' => Catalog::model()->getDropListCatalogByName('catalog'),
                'options' => [],
                'htmlOptions' => [
                    'multiple' => 'multiple'
                ]
            ]); ?>

            <?= $form->select2Row($model, 'brand', [
                'data' => Catalog::model()->getDropListCatalogByName('catalog_manufacturers')
            ]); ?>
        </div>

        <div class="span2" id="made-item">
            <ul class="thumbnails">
                <li class="span12 thumbnail" id="made-li">
                    <?= CHtml::image($model->getMadeImage(), '', array()); ?>
                </li>
            </ul>
        </div>
    </div>

    <!--    <div class="controls controls-row">-->

    <!--        <div class="span3">-->
    <!--            <div class="control-group">-->
    <!--                --><?php //echo $form->labelEx($model,'vip_cost', array('class'=>'control-label')); ?>
    <!--                <div class="controls">-->
    <!--                    --><?php //echo $form->textField($model,'vip_cost',array('maxlength'=>255, 'class'=>'input-medium')); ?>
    <!--                    --><?php //echo $form->error($model,'vip_cost'); ?>
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="span3">-->
    <!--            <div class="control-group">-->
    <!--                --><?php //echo $form->labelEx($model,'show_price', array('class'=>'control-label')); ?>
    <!--                <div class="controls">-->
    <!--                    --><?php //echo $form->dropDownList($model,'show_price',array(1=>'Показывать цену', 0=>'Не показывать цену'), array('class'=>'span12')); ?>
    <!--                    --><?php //echo $form->error($model,'show_price'); ?>
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--        <div class="span3">-->
    <!--            <div class="control-group">-->
    <!--                --><?php //echo $form->labelEx($model,'visible', array('class'=>'control-label')); ?>
    <!--                <div class="controls">-->
    <!--                    --><?php //echo $form->dropDownList($model,'visible', array('0'=>'Нет в наличии', '1'=>'В наличии', '2'=>'Под заказ'), array('class'=>'span10'/**/)); ?>
    <!--                    --><?php //echo $form->error($model,'visible'); ?>
    <!--                </div>-->
    <!--            </div>-->
    <!--        </div>-->

    <!--    </div>-->

    <div class="controls controls-row">
        <div class="span9">
            <!--            <div class="control-group">-->
            <!--                --><?php //echo $form->labelEx($model,'img', array('class'=>'control-label')); ?>
            <!--                <div class="controls">-->
            <!--                    --><?php //echo $form->dropDownList($model,'img', Goods::getSelectImage(), array(
            //                        'class'=>'span12',
            //                        'prompt' => '----'
            //                    ));?>
            <!--                    --><?php //echo $form->error($model,'img'); ?>
            <!--                </div>-->
            <!--            </div>-->
            <!---->
            <!--            <div class="control-group">-->
            <!--                --><?php //echo $form->labelEx($model,'link_detail', array('class'=>'control-label')); ?>
            <!--                <div class="controls">-->
            <!--                    --><?php //echo $form->dropDownList($model,'link_detail', Articles::getAllByName(), array('class'=>'span12', 'prompt' => '----')); ?>
            <!--                    --><?php //echo $form->error($model,'link_detail'); ?>
            <!--                </div>-->
            <!--            </div>-->

        </div>
        <div class="span3">
            <!--            <ul class="thumbnails">-->
            <!--                <li class="span12">-->
            <!--                    <a href="javascript:void(0)" class="thumbnail" title="Редактировать фото" id="item-li">-->
            <!--                        --><?php //echo CHtml::image($model->getImageUrl(),'',array()); ?>
            <!--                    </a>-->
            <!--                </li>-->
            <!--            </ul>-->
        </div>
    </div>

<?= $form->redactorRow($model, 'description', [
    'editorOptions' => [
        'plugins' => [
            'clips',
            'fontfamily'
        ],
        'lang' => 'ua'
    ]
]); ?>

    <div class="form-ations">
        <?= TbHtml::submitButton(
            $model->isNewRecord
                ? ConsoleModule::t('BUTTON_CREATE')
                : ConsoleModule::t('BUTTON_SAVE'),
            ['class' => 'btn btn-primary']
        ); ?>
    </div>

<?php $this->endWidget(); ?>