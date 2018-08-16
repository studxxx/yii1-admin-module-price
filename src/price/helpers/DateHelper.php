<?php

class DateHelper
{
    public static function datetime(CModel $model, $attribute)
    {
        return date("d.m.Y, H:i", $model->$attribute);
    }
}
