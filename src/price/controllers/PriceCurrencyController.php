<?php

class PriceCurrencyController extends BasicPriceController
{
    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    /**
     * @return array access control rules
     */
    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => ['index', 'view', 'create', 'update', 'admin', 'delete'],
                'roles' => [Users::ROLE_MANAGER],
            ],
            [
                'deny',
                'users' => ['*'],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PriceCurrencySearch();

        if (Yii::app()->request->getQuery('PriceCurrencySearch')) {
            $searchModel->attributes = Yii::app()->request->getQuery('PriceCurrencySearch');
        }

        $this->render('index', [
            'model' => $searchModel,
            'dataProvider' => $searchModel->search(Yii::app()->request->getQuery('PriceCurrencySearch'))
        ]);
    }

    /**
     * @param integer $id
     * @throws CHttpException
     */
    public function actionView($id)
    {
        $this->render('view', [
            'model' => $this->loadModel($id),
        ]);
    }

    public function actionCreate()
    {
        $model = new PriceCurrency;

        $this->performAjaxValidation($model);

        if (Yii::app()->request->getPost('PriceCurrency')) {
            $model->attributes = Yii::app()->request->getPost('PriceCurrency');
            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * @param integer $id
     * @throws CException
     * @throws CHttpException
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        $this->performAjaxValidation($model);

        if (Yii::app()->request->getPost('PriceCurrency')) {
            $model->attributes = Yii::app()->request->getPost('PriceCurrency');
            if ($model->save()) {
                $this->redirect(['index']);
            }
        }

        $this->render('update', [
            'model' => $model,
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

        if (!isset($_GET['ajax'])) {
            $this->redirect(
                Yii::app()->request->getPost('returnUrl')
                    ? Yii::app()->request->getPost('returnUrl')
                    : ['index']
            );
        }
    }

    /**
     * @param integer $id
     * @return PriceCurrency the loaded model
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        if (!$model = PriceCurrency::model()->findByPk($id)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param PriceCurrency $model the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (Yii::app()->request->getPost('ajax') === 'currency-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }
}
