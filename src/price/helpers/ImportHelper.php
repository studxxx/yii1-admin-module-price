<?php

class ImportHelper
{
    public static function statuses()
    {
        return [
            Import::STATUS_UPLOADED => ConsoleModule::t('T_UPLOADED'),
            Import::STATUS_IMPORTED => ConsoleModule::t('T_IMPORTED'),
            Import::STATUS_ERROR => ConsoleModule::t('T_ERROR_IMPORT'),
        ];
    }


    public static function getStatus(Import $model)
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