<?php

class PriceProductHelper
{
    const STATE_NEW = 1;
    const STATE_SECOND_HAND = 2;

    const TYPE_NOT_FOUNT = 0;
    const TYPE_UNDEFINED = 1;
    const TYPE_TECDOC = 2;

    const EXIST_AVAILABLE = 1;
    const EXIST_UNAVAILABLE = 0;
    const EXIST_UNDER_ORDER = 2;

    const HIDE = 0;
    const SHOW = 1;


    public static function types()
    {
        return [
            PriceProduct::TYPE_NOT_FOUNT => PriceModule::t('T_NOT_FOUND'),
            PriceProduct::TYPE_UNDEFINED => PriceModule::t('T_TYPE_UNDEFINED'),
            PriceProduct::TYPE_TECDOC => PriceModule::t('T_TYPE_TECDOC'),
        ];
    }

    public static function states()
    {
        return [
            PriceProduct::STATE_NEW => PriceModule::t('T_NEW'),
            PriceProduct::STATE_SECOND_HAND => PriceModule::t('T_SECOND_HAND'),
        ];
    }

    public static function getType(PriceProduct $product)
    {
        $types = self::types();

        return CHtml::tag('span', [], $types[$product->type]);
    }

    public static function getState(PriceProduct $product)
    {
        $states = self::states();

        return CHtml::tag('span', [], $states[$product->state]);
    }

    public static function date($model, $attribute)
    {
        return date("d.m.Y, H:i", $model->$attribute);
    }

    public static function getVisible(PriceProduct $product)
    {
        $class = 'success';

        $content = [
            PriceProduct::HIDE => PriceModule::t('LABEL_HIDE'),
            PriceProduct::SHOW => PriceModule::t('LABEL_SHOW'),
        ];

        if ($product->isHide()) {
            $class = 'danger';
        }
        return CHtml::tag('span', ['class' => 'badge badge-' . $class], $content[$product->visible]);
    }

    public function getExist(PriceProduct $product)
    {
        $class = 'success';
        $content = [
            PriceProduct::EXIST_UNAVAILABLE => PriceModule::t('LABEL_NOT_EXIST'),
            PriceProduct::EXIST_AVAILABLE => PriceModule::t('LABEL_EXIST'),
            PriceProduct::EXIST_UNDER_ORDER => PriceModule::t('LABEL_BY_ORDER'),
        ];

        if ($product->isNotExist()) {
            $class = 'danger';
        } elseif ($product->isExistUnderOrder()) {
            $class = 'warning';
        }

        return CHtml::tag('span', ['class' => "label label-$class"], $content[$product->exist]);
    }

    public static function price(PriceProduct $product)
    {
        $margins = array_filter($product->suppliers->margin, function ($margin) use ($product) {
            list($from, $to) = explode(';', $margin['condition']);

            return (float) str_replace('from:', '', $from) < $product->price
                && (float) str_replace('to:', '', $to) > $product->price;
        });
        $margin = reset($margins);

        return empty($margin['value'])
            ? 0
            : round(
                (float)$product->price * (1 + (float) $margin['value']) * (float) $product->suppliers->currency,
                2
            );
    }

//    /**
//     * Получаем линк к картинке товара
//     * @param bool $thumbnail . Default false
//     * @return string
//     */
//    public function getImageUrl($thumbnail = false)
//    {
//        // Формируем путь к картинкам
//        $dir = Helpers::getPath('images');
//
//        // формируем линк к фото товаров
//        $url = Helpers::getUri('images');
//
//        $dirGoods = $dir . 'goods' . DIRECTORY_SEPARATOR;
//        $dirMade = $dir . 'made' . DIRECTORY_SEPARATOR;
//        $img = $this->img;
//
//        if ($thumbnail) {
//            $img = explode('/', $img);
//            if (isset($img[1])) {
//                $img[1] = 'thumbnail/' . $img[1];
//                $img = implode('/', $img);
//            } else {
//                $img = '';
//            }
//            $dirMade .= 'thumbnail' . DIRECTORY_SEPARATOR;
//        }
//        if (file_exists($file = $dirGoods . $img) && !is_dir($file))
//            $url .= 'goods/' . $img;
//        elseif (file_exists($file = $dirMade . $this->made . '.png') && !is_dir($file))
//            $url .= 'made/' . (!$thumbnail ?: 'thumbnail/') . $this->made . '.png';
//        else {
//            $cap = !$thumbnail ? 'cap.png' : 'cap-thumbnail.png';
//            $url .= 'icons/' . $cap;
//            $file = $dir . 'icons' . DIRECTORY_SEPARATOR . $cap;
//        }
//
//        // Определяем размер картинки
//        $size = getimagesize($file);
//
//        $this->width = $size[0];
//        $this->height = $size[1];
//
//        return $url;
//    }
//
//    public function getMadeImage()
//    {
//        $url = Helpers::getUri('images/icons') . 'cap.png';
//        if (!empty($this->made)) {
//            $model = Catalog::model()->findByPk($this->made);
//
//            // Формируем путь к картинкам
//            $dir = Helpers::getPath('images' . DIRECTORY_SEPARATOR . 'made');
//
//            // формируем линк к фото товаров
//            $uri = Helpers::getUri('images/made');
//
//            $img = $model->name . '.png';
//            if (file_exists($file = $dir . $img) && !is_dir($file)) {
//                $url = $uri . $img;
//            }
//        }
//        return $url;
//    }
//
//
//
//    public function getListFieldsTools()
//    {
//        $list = $this->attributeLabels();
//        unset($list['id']);
//        unset($list['search']);
//        unset($list['brand']);
//        unset($list['price']);
//        unset($list['exist']);
//        unset($list['count']);
//        unset($list['sid']);
////        unset($list['external_id']);
//        unset($list['type']);
////        unset($list['uid']);
//        unset($list['created']);
//        unset($list['updated']);
//        unset($list['visible']);
//        unset($list['tecdoc_article_id']);
//        unset($list['tecdoc_supplier_id']);
//        unset($list['marker']);
//
//        return $list;
//    }
//
//    /**
//     * Список для dropDownList
//     * @return array
//     */
//    public function getDroplistBrand()
//    {
//        $criteria = new CDbCriteria();
//        $criteria->select = 'brand';
//        $criteria->group = 'brand';
//        $criteria->order = 'brand';
//        $model = $this->findAll($criteria);
//        // Возвращаем массив id=>name
//        return CHtml::listData($model, 'brand', 'brand');
//    }
}
