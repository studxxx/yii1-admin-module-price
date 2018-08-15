<?php

/**
 * Class RebuildPriceBehavior
 * @author studxxx
 * @property mixed $args
 * @property ProductSuppliers $supplier
 * @property integer $chunkSize
 * @property Goods $parseItems
 */
class RebuildPriceBehavior extends CBehavior implements WorkerJobInterface
{
    private $_aliasMap = [
        ['alias' => "'LUK", 'supplier' => 'LUK'],
        ['alias' => "'SACHS", 'supplier' => 'SACHS'],
        ['alias' => "'SASIC", 'supplier' => 'SASIC'],
        ['alias' => "'NTN-SNR", 'supplier' => 'SNR'],
        ['alias' => "'GATES", 'supplier' => 'GATES'],
        ['alias' => "'FAG", 'supplier' => 'FAG'],
        ['alias' => "'METALCAUCHO", 'supplier' => 'METALCAUCHO'],
        ['alias' => "'MECAFILTER", 'supplier' => 'MECAFILTER'],
        ['alias' => "'PURFLUX", 'supplier' => 'PURFLUX'],
        ['alias' => "'BOSCH", 'supplier' => 'BOSCH'],

        ['alias' => '4MAX EXHAUST', 'supplier' => 'GT Exhaust'],
        ['alias' => 'Abs', 'supplier' => 'A.B.S.'],
        ['alias' => 'AC Delco', 'supplier' => 'ACDelco'],
        ['alias' => 'AD', 'supplier' => 'AD KÜHNER'],
        ['alias' => 'Alco', 'supplier' => 'ALCO FILTER'],
        ['alias' => 'Alco', 'supplier' => 'AL-KO'],
        ['alias' => 'Autofren', 'supplier' => 'AUTOFREN SEINSA'],
        ['alias' => 'AUTOTECH', 'supplier' => 'ATL Autotechnik'],
        ['alias' => 'AUTOTECHTEILE', 'supplier' => 'ATL Autotechnik'],
        ['alias' => 'Berg', 'supplier' => 'BERGA'],
        ['alias' => 'BEGEL', 'supplier' => 'BEGEL Germany'],
        ['alias' => 'BEHR THERMOT-TRONIK', 'supplier' => 'BEHR'],
        ['alias' => 'CARGO', 'supplier' => 'CARGOPARTS'],
        ['alias' => 'CARGO', 'supplier' => 'HC-Cargo'],
        ['alias' => 'Clean', 'supplier' => 'CLEAN FILTERS'],
        ['alias' => 'Conti', 'supplier' => 'CONTITECH'],
        ['alias' => 'Cs', 'supplier' => 'CS Germany'],
        ['alias' => 'CQ', 'supplier' => 'LAUBER'],
        ['alias' => 'CRB', 'supplier' => 'General Motors'],
        ['alias' => 'doria', 'supplier' => 'Meat&doria'],
        ['alias' => 'Febi', 'supplier' => 'FEBI BILSTEIN'],
        ['alias' => 'Fortuna Line', 'supplier' => 'FORTUNE LINE'],
        ['alias' => 'Goetze', 'supplier' => 'GOETZE ENGINE'],
        ['alias' => 'Hella', 'supplier' => 'BEHR HELLA SERVICE'],
        ['alias' => 'Hella', 'supplier' => 'HELLA PAGID'],
        ['alias' => 'Hella', 'supplier' => 'HELLA NUSSBAUM'],
        ['alias' => 'Hella', 'supplier' => 'HELLA GUTMANN'],
        ['alias' => 'Hengst', 'supplier' => 'HENGST FILTER'],
        ['alias' => 'Jakoparts', 'supplier' => 'HERTH+BUSS JAKOPARTS'],
        ['alias' => 'Japan Parts', 'supplier' => 'JAPANPARTS'],
        ['alias' => 'Kraft', 'supplier' => 'KRAFT AUTOMOTIVE'],
        ['alias' => 'Kayaba', 'supplier' => 'KYB'],
        ['alias' => 'Lemforder', 'supplier' => 'LEMFÖRDER'],
        ['alias' => 'Lesjofors', 'supplier' => 'LESJÖFORS'],
        ['alias' => 'Lucas', 'supplier' => 'LUCAS ELECTRICAL'],
        ['alias' => 'Lucas', 'supplier' => 'LUCAS DIESEL'],
        ['alias' => 'Lucas', 'supplier' => 'LUCAS ENGINE DRIVE'],
        ['alias' => 'Magneti Mareli', 'supplier' => 'MAGNETI MARELLI'],
        ['alias' => 'Mahle', 'supplier' => 'MAHLE ORIGINAL'],
        ['alias' => 'MALO', 'supplier' => 'MALÒ'],
        ['alias' => 'MOTO', 'supplier' => 'MOTORAD'],
        ['alias' => 'Mann', 'supplier' => 'MANN-FILTER'],
        ['alias' => 'OE BMW', 'supplier' => 'BMW'],
        ['alias' => 'OE Chrysler', 'supplier' => 'Chrysler'],
        ['alias' => 'OE Citroen', 'supplier' => 'Citroen'],
        ['alias' => 'OE Fiat/Alfa/La', 'supplier' => 'Fiat'],
        ['alias' => 'OE Fiat/Alfa/La', 'supplier' => 'Alfa'],
        ['alias' => 'OE Fiat/Alfa/La', 'supplier' => 'Lancia'],
        ['alias' => 'OE Ford', 'supplier' => 'Ford'],
        ['alias' => 'OE GM', 'supplier' => 'GM'],
        ['alias' => 'OE Honda', 'supplier' => 'Honda'],
        ['alias' => 'OE Mazda', 'supplier' => 'Mazda'],
        ['alias' => 'OE Mercedes', 'supplier' => 'Mercedes'],
        ['alias' => 'OE Mitsubishi', 'supplier' => 'Mitsubishi'],
        ['alias' => 'OE Nissan', 'supplier' => 'Nissan'],
        ['alias' => 'OE Opel', 'supplier' => 'Opel'],
        ['alias' => 'OE Peugeot', 'supplier' => 'Peugeot'],
        ['alias' => 'OE Renault', 'supplier' => 'Renault'],
        ['alias' => 'OE Subaru', 'supplier' => 'Subaru'],
        ['alias' => 'OE Toyota', 'supplier' => 'Toyota'],
        ['alias' => 'OE VW/Audi', 'supplier' => 'VW/Audi'],
        ['alias' => 'OE VW/Audi', 'supplier' => 'VW'],
        ['alias' => 'OE VW/Audi', 'supplier' => 'Audi'],
        ['alias' => 'OE VW/Audi', 'supplier' => 'VAG'],
        ['alias' => 'Roulunds', 'supplier' => 'ROULUNDS RUBBER'],
        ['alias' => 'SEINSA', 'supplier' => 'AUTOFREN SEINSA'],
        ['alias' => 'Siemens Vdo', 'supplier' => 'VDO'],
        ['alias' => 'Trucktec', 'supplier' => 'TRUCKTEC AUTOMOTIVE'],
        ['alias' => 'TRW / KETNER', 'supplier' => 'TRW Automotive'],
        ['alias' => 'TRW / KETNER', 'supplier' => 'TRW'],
        ['alias' => 'TRW / KETNER', 'supplier' => 'TRW Engine Component'],
        ['alias' => 'TRW Automotive', 'supplier' => 'TRW'],
        ['alias' => 'TRW Automotive', 'supplier' => 'TRW Engine Component'],
        ['alias' => 'Valeo PHC', 'supplier' => 'Valeo'],
        ['alias' => 'Vernet', 'supplier' => 'CALORSTAT by Vernet'],
        ['alias' => 'Victor Reinz', 'supplier' => 'REINZ'],
        ['alias' => 'WALKER', 'supplier' => 'WALKER PRODUCTS'],
        ['alias' => 'Zf', 'supplier' => 'ZF LENKSYSTEME'],
        ['alias' => 'Zf', 'supplier' => 'ZF Parts'],
        ['alias' => 'Zf', 'supplier' => 'ZF'],
        ['alias' => 'Zf', 'supplier' => 'SACHS (ZF SRE)'],
        ['alias' => 'Dello/Automega', 'supplier' => 'automega'],
        ['alias' => 'Dello/Automega', 'supplier' => 'Dello'],
        ['alias' => 'Hans Pries', 'supplier' => 'Topran'],
        ['alias' => 'FRECCIA', 'supplier' => 'OPTIMAL'],
        ['alias' => 'ks', 'supplier' => 'KOLBENSCHMIDT'],
        ['alias' => 'CS Germany', 'supplier' => 'CS Germanynl'],
        ['alias' => 'CARGO', 'supplier' => 'HC-Cargo'],
        ['alias' => 'Febest', 'supplier' => 'Asva'],
        ['alias' => '3RG - 3RG', 'supplier' => '3RG'],
        ['alias' => 'AD - AD', 'supplier' => 'AD'],
        ['alias' => 'BOS - BOSCH', 'supplier' => 'BOSCH'],
        ['alias' => 'COR - CORTECO', 'supplier' => 'CORTECO'],
        ['alias' => 'CX - CX', 'supplier' => 'CX'],
        ['alias' => 'DEL - DELPHI', 'supplier' => 'DELPHI'],
        ['alias' => 'DOL - DOLZ', 'supplier' => 'DOLZ'],
        ['alias' => 'EQ - EYQUEM', 'supplier' => 'EYQUEM'],
        ['alias' => 'EQ - EYQUEM', 'supplier' => 'EYQUEM'],
        ['alias' => 'FAE - FAE', 'supplier' => 'FAE'],
        ['alias' => 'GAT - GATES', 'supplier' => 'GATES'],
        ['alias' => 'HH - HUTCHINSON', 'supplier' => 'HUTCHINSON'],
        ['alias' => 'IMP - IMPERGOM', 'supplier' => 'ORIGINAL IMPERIUM'],
        ['alias' => 'INA - INA', 'supplier' => 'INA'],
        ['alias' => 'KYB - KAYABA', 'supplier' => 'KYB'],
        ['alias' => 'LEC - LECOY', 'supplier' => 'MAURICE LECOY'],
        ['alias' => 'LUK', 'supplier' => 'INA'],

        ['alias' => 'LUK - LUK', 'supplier' => 'LUK'],
        ['alias' => 'MC - METALCAUCHO', 'supplier' => 'METALCAUCHO'],
        ['alias' => 'O - POLCAR', 'supplier' => 'POLCAR'],
        ['alias' => 'PUR - PURFLUX', 'supplier' => 'PURFLUX'],
        ['alias' => 'REC - RECORD', 'supplier' => 'RECORD FRANCE'],
        ['alias' => 'SNR - SNR', 'supplier' => 'SNR'],
        ['alias' => 'TAL - TALOSA', 'supplier' => 'TALOSA'],
        ['alias' => 'TRW - TRW', 'supplier' => 'TRW'],
        ['alias' => 'VAL - VALEO', 'supplier' => 'VALEO'],
    ];

    /**
     * @param $job
     * @throws CException
     */
    public function perform($job)
    {
        $this->args = $job;

        $this->initCLILogger();

        Yii::import('app.components.tecdocLookup.TecdocLookup');

        while ($items = $this->parseItems) {
            foreach ($items as $key => $item) {

                try {
                    /**
                     * @var Goods $model
                     */
                    $model = clone $item;

                    $tecdocLookup = new TecdocLookup($model);

                    while (!($tecdocLookup->state instanceof StateEnd)) {
                        $tecdocLookup->request();
                    }
                    if ($model != $item && !$model->save()) {
                        throw new CDbException('No save data into DB');
                    }
                } catch (CDbException $e) {
                    Yii::log(CVarDumper::dumpAsString($e->getMessage()), CLogger::LEVEL_ERROR);
                } catch (CException $e) {
                    Yii::log(CVarDumper::dumpAsString($e->getTrace()), CLogger::LEVEL_ERROR);
                }
            }
        }
    }

    /**
     * @return mixed
     */
    public function getArgs()
    {
        return $this->args;
    }

    /**
     * @param mixed $args
     */
    public function setArgs($args)
    {
        $this->args = $args;
    }


    /**
     * @param null $alias
     * @param null $supplier
     */
    public function actionAliasCompare($alias = null, $supplier = null)
    {
        if (!empty($alias) && !empty($supplier)) {
            $this->setAliasMap([
                ['alias' => $alias, 'supplier' => $supplier]
            ]);
        }

        foreach ($this->getAliasMap() as $alias_map) {
            $criteria = new CDbCriteria();
            $criteria->condition = 'type = 1';

            $criteria->mergeWith(
                $this->criteriaBySupplier($alias_map['alias'])
            );

//            $count = Goods::model()
//                ->count($criteria);

//            $step = ceil($count/$this->getChunkSize());

//            $criteria->limit = $this->getChunkSize();

//            for($i=1; $i<=$step; ++$i)
//            {
            $model = Goods::model()->findAll($criteria);

            $this->parseTecdoc(
                $model,
                $alias_map['supplier']
            );
//            }
        }
    }

    public function actionAliasCompareLookup($alias = null, $supplier = null)
    {
        if (!empty($alias) && !empty($supplier)) {
            $this->setAliasMap([
                ['alias' => $alias, 'supplier' => $supplier]
            ]);
        }

        foreach ($this->getAliasMap() as $alias_map) {
            $criteria = new CDbCriteria();
            $criteria->condition = 'type = 1';

            $criteria->mergeWith(
                $this->criteriaBySupplier($alias_map['alias'])
            );

//            $count = Goods::model()
//                ->count($criteria);

//            $step = ceil($count/$this->getChunkSize());

//            $criteria->limit = $this->getChunkSize();

//            for($i=1; $i<=$step; ++$i)
//            {
            $model = Goods::model()->findAll($criteria);

            $this->parseTecdocLookup(
                $model,
                $alias_map['supplier']
            );
//            }
        }
    }

    /**
     * @param Goods[] $models
     * @param null $supplier
     */
    protected function parseTecdoc($models, $supplier = null)
    {
//        $behavior = ['class' => 'ext.behaviors.TecdocParseBehavior'];

        /**
         * @var $item Goods
         */
        foreach ($models as $key => $item) {

            $tecdocLookup = new TecdocLookup($item);

            while ($tecdocLookup->state != TecdocLookup::STATE_END) {
                $tecdocLookup->request();
            }
            /*$supplier = $this->lookupAlias($item->brand);
            
            Yii::log('Item: ' . $item->id . ' ' . $item->name . ' ' . $item->brand . ' SKU: ' . $item->sku, CLogger::LEVEL_PROFILE);
            Yii::log('Finded alias: ' . $supplier, CLogger::LEVEL_PROFILE);

            $item->attachBehavior('tecdocParse', $behavior);
            $item->strictMatch();
            $item->detachBehavior('tecdocParse');

            if (!$this->hasFinded($item)) {
                $item->attachBehavior('tecdocParse', $behavior);
                $item->lookupMatch();
                $item->detachBehavior('tecdocParse');
            }

            if (!$this->hasFinded($item) && $supplier !== null) {
                $behavior = array_merge($behavior, ['alias' => $supplier]);

                $item->attachBehavior('tecdocParse', $behavior);
                $item->strictMatch();
                $item->detachBehavior('tecdocParse');

                if (!$this->hasFinded($item)) {
                    $item->attachBehavior('tecdocParse', $behavior);
                    $item->lookupMatch();
                    $item->detachBehavior('tecdocParse');
                }
            }
            Yii::log('====================', CLogger::LEVEL_PROFILE);

            if ($item->type == 2) {
                Yii::log('++++++++++++++++++++', CLogger::LEVEL_PROFILE);
            }

            $item->save(false);
//          */  //if ($key==10) die;
        }
//        Yii::log(date('Y-m-d, H:i:s') . ' - Added: ' . $tecdoc_accepted . ' No add: ' . $tecdoc_not_accepted);
    }

    /**
     * @param $brand
     * @return string|null
     */
    private function lookupAlias($brand)
    {
        foreach ($this->_aliasMap as $item) {
            $alias = $item['alias'];
            $supplier = $item['supplier'];

            if ($brand == $alias) {
                return $supplier;
            }
        }
        return null;
    }

    /**
     * @param Goods $item
     * @return bool
     */
    private function hasFinded($item)
    {
        if ($item->type == 2) {
            Yii::log(date('Y-m-d, H:i:s') . ' - ' . $item->name . ' Supplier: ' . $item->ext_supplier_id . ' Article ID: ' . $item->ext_article_id, CLogger::LEVEL_PROFILE);
            return true;
        } elseif ($item->type === 0 || $item->type === '0') {
            $item->type = 1;
            return false;
        } else {
            Yii::log(date('Y-m-d, H:i:s') . ' - ' . $item->name, CLogger::LEVEL_PROFILE);
            return false;
        }
    }

    /**
     * @param $model
     * @param null $supplier
     */
    protected function parseTecdocLookup($model, $supplier = null)
    {
        $behavior = ['class' => 'ext.behaviors.TecdocParseBehavior'];

        if ($supplier !== null) {
            $behavior = array_merge($behavior, ['alias' => $supplier,]);
        }
        $tecdoc_accepted = 0;
        $tecdoc_not_accepted = 0;
        /**
         * @var $item Goods
         */
        foreach ($model as $item) {
            $item->attachBehavior('tecdocParse', $behavior);
            $item->tecdocLookup();
            if ($item->type == 2) {
                ++$tecdoc_accepted;
                $item->save(false);
                echo date('Y-m-d, H:i:s'), ' - ', $item->name, ' Supplier: ', $item->ext_supplier_id, ' Article ID: ', $item->ext_article_id, PHP_EOL;
            } elseif ($item->type === 0 || $item->type === '0') {
                ++$tecdoc_not_accepted;
                $item->type = 1;
                $item->save(false);
            } else {
                ++$tecdoc_not_accepted;
                echo date('Y-m-d, H:i:s'), ' - ', $item->name, PHP_EOL;
            }
            $item->detachBehavior('tecdocParse');
        }
        echo date('Y-m-d, H:i:s'), ' - Added: ', $tecdoc_accepted, ' No add: ', $tecdoc_not_accepted, PHP_EOL;
    }

    /**
     * @param $brand
     * @return CDbCriteria
     */
    protected function criteriaBySupplier($brand)
    {
        $criteria = new CDbCriteria();
        $criteria->with = ['items2brand'];
        $criteria->addCondition('items2brand.title = :brand');
        $criteria->params = [
            ':brand' => $brand,
        ];

        return $criteria;
    }

    /**
     * @return int
     */
    public function getChunkSize()
    {
        return Yii::app()->config->get('TECDOC.CHUNK_SIZE');
    }

    /**
     * @return array
     */
    public function getAliasMap()
    {
        return $this->_aliasMap;
    }

    /**
     * @param array $aliasMap
     */
    public function setAliasMap($aliasMap)
    {
        $this->_aliasMap = $aliasMap;
    }

    public function actionCross()
    {
        $csv = new Csv();
        $csv->fileName = 'FEBEST_cross.csv';
        $csv->path = Helpers::getPath('web/uploads/cross');
        $csv->delimiter = ',';
        $csv->setFields([
            'article_nr' => 0,
            'title' => 1,
            'article_cross' => 3,
        ]);
        $csv->import();

        echo date('H:i:s'), ' ', $csv->count(), PHP_EOL;

        foreach ($csv->getItems() as $item) {
            if (!empty($item['article_cross'])) {
                echo date('H:i:s'), ' ', $item['article_nr'], '-', $item['title'], '-', $item['article_cross'], PHP_EOL;

                $article_cross = explode('|', $item['article_cross']);

                foreach ($article_cross as $cross) {
                    $model = new ArticleLinkCross();
                    $model->attributes = $item;
                    $model->article_cross = $cross;
                    $model->status = 0;
                    $model->validate();

                    if ($model->hasErrors())
                        var_dump($model->getErrors());
                    else
                        $model->save();
                }
            }
        }
    }

    public function actionTest()
    {
        Yii::getLogger()->autoFlush = 1;
        Yii::getLogger()->autoDump = true;

        Yii::import('app.components.gearman.CDWorker');

        $gearman = new CDWorker();
        $gearman->setHost(Yii::app()->params['gearman']['host']);
        $gearman->setPort(Yii::app()->params['gearman']['port']);
        $gearman->setPerformer(Yii::app()->params['gearman']['worker']);
        $gearman->server();
    }

    protected function getParseItems()
    {
        $criteria = new CDbCriteria();
        $criteria->condition = 'type = 0 AND sid = :supplierId';
        $criteria->params = [
            ':supplierId' => 3
        ];
        $criteria->limit = $this->chunkSize;

        return Goods::model()->findAll($criteria);
    }

    private function initCLILogger()
    {
        if (PHP_SAPI == 'cli') {
            Yii::getLogger()->autoFlush = 1;
            Yii::getLogger()->autoDump = true;
        }
    }
}
