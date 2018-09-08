<?php
class PriceRangeController extends BasicPriceController
{

    public function filters()
    {
        return [
            'accessControl',
            'postOnly + delete',
        ];
    }

    public function accessRules()
    {
        return [
            [
                'allow',
                'actions' => [
                    'delete',
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
     * @param integer $id
     * @throws CDbException
     * @throws CHttpException
     */
    public function actionDelete($id)
    {
        $this->loadModel($id)->delete();

        if (!Yii::app()->request->getQuery('ajax')) {
            $this->redirect(
                Yii::app()->request->getPost('returnUrl')
                    ? Yii::app()->request->getPost('returnUrl')
                    : ['index']
            );
        }
    }

    /**
     * @param integer $id
     * @return CActiveRecord|PriceRange
     * @throws CHttpException
     */
    protected function loadModel($id)
    {
        if (!$model = PriceRange::model()->findByPk($id)) {
            throw new CHttpException(404, 'The requested page does not exist.');
        }
        return $model;
    }
}
