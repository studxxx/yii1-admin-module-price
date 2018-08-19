<?php
/**
 * @var $this PriceSupplierController
 * @var $form TbActiveForm
 * @var $template TemplateForm
 * @var $id int
 */
?>
<?php $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', [
    'id' => 'template-form',
    'enableAjaxValidation' => false,
    'htmlOptions' => [
        'onsubmit' => "return false;",/* Disable normal form submit */
        'onkeypress' => " if(event.keyCode == 13){ send(); } ", /* Do ajax call when user presses enter key */
        'class' => 'form-horizontal',
    ],
]); ?>

<?= PriceModule::t('T_FIELD_REQUIRED'); ?>

<?= $form->errorSummary($template); ?>

<?= $form->textFieldRow($template, 'made', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'sku', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'skuOE', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'name', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'description'); ?>

<?= $form->textFieldRow($template, 'price', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'delivery', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'count', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'step', ['maxlength' => 100]); ?>

<?= $form->textFieldRow($template, 'special_row', ['maxlength' => 100]); //'maxlength'=>100 ?>

<?= $form->textFieldRow($template, 'validate_row', ['maxlength' => 100]); //'maxlength'=>100 ?>

<?= $form->textFieldRow($template, 'empty_rows_for_end', ['maxlength' => 100]); //'maxlength'=>100 ?>

<div class="form-actions">
    <?= CHtml::Button(PriceModule::t('BUTTON_SAVE'), [
        'onclick' => 'send();',
        'class' => 'btn btn-primary'
    ]); ?>
</div>

<?php $this->endWidget(); ?>

<script type="text/javascript">
    function send() {
        var data = $("#template-form").serialize();
        var gritterMessage = {
            sticky: true,
            time: "",
            class_name: "my-sticky-class"
        };
        $.ajax({
            type: 'POST',
            url: '<?= $this->createUrl("/console/suppliers/update", ['id' => $id]); ?>',
            dataType: 'json',
            data: data,
            success: function (result) {
                gritterMessage.title = "<?= PriceModule::t('SUCCESS')?>";
                gritterMessage.text = "<?= PriceModule::t('TEMPLATE_SAVE')?>";

                GNotification(gritterMessage, {
                    fade: true,
                    speed: "slow"
                }, 5000, 500);
            },
            error: function (result) {
                gritterMessage.title = "<?= PriceModule::t('ERROR')?>";
                gritterMessage.text = "<?= PriceModule::t('TEMPLATE_NOT_SAVE')?>";

                GNotification(gritterMessage, {
                    fade: true,
                    speed: "slow"
                }, 5000, 500);
            }
        });
    }
</script>