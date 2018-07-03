<?php
namespace tests\runtime\data\handlers\Post\actions;

/**
 * Class Delete
 * @package tests\runtime\data\handlers\Post\actions
 */
class Delete extends AbstractAction
{
    /**
     * @return bool
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function execute() : bool
    {
        $this->post->deleteModel();
        return true;
    }
}