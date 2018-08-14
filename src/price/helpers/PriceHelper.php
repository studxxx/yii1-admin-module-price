<?php

class PriceHelper
{
    public static function statuses()
    {
        return [
            Price::STATUS_UPLOADED => PriceModule::t('T_UPLOADED'),
            Price::STATUS_IMPORTED => PriceModule::t('T_IMPORTED'),
            Price::STATUS_ERROR => PriceModule::t('T_ERROR_IMPORT'),
        ];
    }

    public static function getStatus(Price $model)
    {
        $statuses = self::statuses();
        $class = 'danger';

        if ($model->isImported()) {
            $class = 'success';
        } elseif ($model->isUploaded()) {
            $class = 'warning';
        }
        return CHtml::tag('span', ['class' => "label label-$class"], $statuses[$model->status]);
    }

    public static function date($model, $attribute)
    {
        return date("d.m.Y, H:i", $model->$attribute);
    }
}
