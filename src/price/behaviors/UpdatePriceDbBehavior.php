<?php

Yii::import('app.components.gearman.WorkerJobInterface');

/**
 * Class CsvBehavior
 * @property string $importPath
 * @property Import $import
 * @property Logs $log
 * @property Csv $csv
 * @property array $args
 */
class UpdatePriceDbBehavior extends CBehavior implements WorkerJobInterface
{
    /**
     * @var array
     */
    private $args;
    /**
     * @var array
     */
    public $map = [
        'search' => 0,
        'brand' => 1,
        'sku' => 2,
        'name' => 3,
        'price' => 4,
        'exist' => 5,
        'count' => 6,
        'sid' => 7,
        'visible' => 8,
        'marker' => 9,
        'note' => 10,
        'delivery' => 11,
    ];

    /**
     * @param $job
     */
    public function perform($job)
    {
        $startTime = microtime(true);
        $this->args = $job;
        $this->init();

        foreach ($this->csv->getItems() as $key => $item) {
            if (!isset($item['marker'])) {
                $this->log->message('no_item_update', [
                    '{item}' => print_r($item, true)
                ]);
                continue;
            }
            if ($key % 100 == 0) {
                $this->log->message('count_items_updated', [
                    '{count}' => $key
                ]);
            }
            $this->saveToDb($item);
        }
        if (isset($key)) {
            $this->log->message('job_finished', [
                '{message}' => 'Updated ' . $key . ' items'
            ]);
        }
        $this->log->message('end', ['{microtime}' => $startTime]);
    }

    public function init()
    {
        $this->log = new Logs();
        $this->log->display = Yii::app()->config->get('IMPORT.DISPLAY_LOG');
        $this->log->message('start');

        $id = !empty($this->args->id)
            ? $this->args->id
            : $this->args;

        $this->import = Import::model()->findByPk($id);

        $this->initCsvReader();

        $this->csv->import();

        $this->log->message('count_items_for_update', [
            '{count}' => $this->csv->count()
        ]);
    }

    public function saveToDb($item)
    {
        $model = Goods::model()->findByAttributes(['marker' => $item['marker']]);
        if ($model === null) {
            $model = new Goods();
        } else {
            unset($item['made']);
            unset($item['brand']);
            unset($item['sku']);
            unset($item['name']);
            unset($item['marker']);
            unset($item['sid']);
            unset($item['note']);
        }

        $model->attributes = $item;
        $model->validate();

        if ($model->hasErrors()) {
            var_dump($model->getErrors());
        } else {
            $model->price = new CDbExpression("ROUND({$model->price}, 2)");
            $model->save();
        }
    }

    /**
     * @todo Це видаляемо з роботи
     * @param $brand
     * @return int
     */
    public function saveBrandToDb($brand)
    {
        $brands = Catalog::model()
            ->getNodeByName('catalog_manufacturers');

        $model = Catalog::model()->findByAttributes([
            'title' => $brand,
        ], 'left_key > :left_key AND right_key < :right_key', [
            ':left_key' => $brands->left_key,
            ':right_key' => $brands->right_key,
        ]);

        if ($model === null) {
            $name = strtr($brand, [' ' => '_']);
            $model = new Catalog();
            $model->idNode = $brands->id;
            $model->name = mb_strtolower($name);
            $model->title = $brand;
            if (!$model->validate()) {
                var_dump($model->getErrors());
            }
            if ($model->save(false)) {
                if (empty($model->id) || empty($model->title)) {
                    var_dump($model);
                }
                $this->log->message('add_new_brand', [
                    '{id}' => $model->id,
                    '{brand}' => $model->title,
                ]);
            } else {
                Yii::log(print_r($model->getErrors(), true), CLogger::LEVEL_ERROR);
            }
        }
        return $model->id;
    }

    /**
     * @param array $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }

    /**
     * @param $log Logs
     */
    public function setLog($log)
    {
        $this->log = $log;
    }

    /**
     * @param Import $import
     */
    public function setImport($import)
    {
        $this->import = $import;
    }

    /**
     * @param $csv Csv
     */
    public function setCsv($csv)
    {
        $this->csv = $csv;
    }

    protected function getImportPath()
    {
        if (!Yii::app()->config->has('IMPORT.PATH_UPLOAD_IMPORT')) {
            Yii::app()->config->add([
                'param' => 'IMPORT.PATH_UPLOAD_IMPORT',
                'label' => 'Path for imported price',
                'value' => 'uploads/imported',
                'type' => 'string',
                'default' => 'import',
            ]);
        }
        return Helpers::getPublicPath(
            Yii::app()->config
                ->get('IMPORT.PATH_UPLOAD_IMPORT')
        );
    }

    protected function initCsvReader()
    {
        $this->csv = new Csv();
        $this->csv->fileName = $this->import->output_file;
        $this->csv->path = $this->importPath;
        $this->csv->delimiter = '|';
        $this->csv->setFields($this->map);
    }
}