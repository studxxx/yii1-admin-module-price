<?php

class TransactionManager
{
    /**
     * @param callable $function
     * @throws Exception
     */
    public function wrap(callable $function)
    {
        $transaction = Yii::app()->getDb()->beginTransaction();
        try {
            $function();
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
