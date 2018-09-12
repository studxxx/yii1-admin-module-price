<?php

class PriceProductController extends BasicPriceController
{
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete, toggle, deleteAll',
        ];
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'index', 'view', 'create', 'update', 'delete', 'toggle', 'deleteAll',
                ],
                'roles' => [Users::ROLE_MANAGER],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    /**
     * @throws CException
     */
    public function actionIndex()
    {
        $searchModel = new PriceProductSearch();
        $searchModel->unsetAttributes();

        if ($params = Yii::app()->request->getQuery('PriceProductSearch')) {
            $searchModel->attributes = $params;
        }

        if (Yii::app()->request->isAjaxRequest) {
            $this->renderPartial('_grid', [
                'searchModel' => $searchModel,
                'dataProvider' => $searchModel->search()
            ]);
        } else {
            $this->render('index', [
                'searchModel' => $searchModel,
                'dataProvider' => $searchModel->search()
            ]);
        }
    }

    /**
     * @param integer $id
     * @throws CException
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $model = $this->loadModel($id);

//        $possible_variants = $this->getPossibleVariants($model->sku);
//        $tecdoc = $this->getArticleFromGoods($model->tecdoc_article_id);

        if (Yii::app()->request->isAjaxRequest) {
//            if (Yii::app()->request->getQuery('ajax') == 'grid-possible-variants') {
//                $this->renderPartial('view/_possible_variants', [
//                    'possible_variants' => $possible_variants,
//                ]);
//            } elseif (Yii::app()->request->getQuery('ajax') == 'grid-tecdoc-data') {
//                $this->renderPartial('view/_tecdoc', [
//                    'tecdoc' => $tecdoc,
//                ]);
//            }
        } else {
            $this->render('view', [
                'model' => $model,
//                'possible_variants' => $possible_variants,
//                'tecdoc' => $tecdoc,
            ]);
        }
    }

    /**
     * @var $cs CClientScript
     * @throws CException
     */
    public function actionCreate()
    {
        // Подключаем объект для работы с JavaScript
        $cs = Yii::app()->clientScript;
        // Подключаем скрипты
        $cs->registerScriptFile(Helpers::getUri('js/chosen') . 'chosen.jquery.min.js', CClientScript::POS_END);
        $cs->registerScriptFile(Helpers::getUri('js') . 'chosen.js', CClientScript::POS_END);
        $cs->registerScriptFile(Helpers::getUri('js') . 'goods.js', CClientScript::POS_END);
        // Подключаем файл css
        $cs->registerCssFile(Helpers::getUri('js/chosen') . 'chosen.css');
        $cs->registerScript('init', "App.init();", CClientScript::POS_END);

        $model = new Goods;

        if (isset($_POST['Goods'])) {
            $model->attributes = $_POST['Goods'];
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = Goods::model()->loadGoods($id);

        $tecdoc = $this->getArticleFromGoods($model->tecdoc_article_id);

        if (isset($_POST['Goods'])) {
            $model->attributes = Yii::app()->request->getPost('Goods');
            if ($model->save()) {
                $this->redirect(['view', 'id' => $model->id]);
            }
        }

        $this->render('update', [
            'model' => $model,
            'tecdoc' => $tecdoc,
        ]);
    }

    /**
     * @param integer $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!Yii::app()->request->getQuery('ajax')) {
            $this->redirect(Yii::app()->request->getPost('returnUrl') ? $_POST['returnUrl'] : ['index']);
        }
    }

    public function actionDeleteAll()
    {
        PriceProduct::model()->deleteAll();
    }

    /**
     * @param $id
     * @return CActiveRecord|PriceProduct
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        if (!$model = PriceProduct::model()->findByPk($id)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }

        return $model;
    }
}
