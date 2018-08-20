<?php
/* @var $this GoodsController */
/* @var $model Goods */

$this->menu = [
    ['label' => 'Журнал товаров', 'url' => ['index']],
];
?>

    <h1>Добавить новый товар</h1>

<?php echo $this->renderPartial('_form', array('model' => $model)); ?>

