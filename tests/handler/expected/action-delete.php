<?php
namespace tests\runtime\data\handler\Post\actions;

/**
 * Class Delete
 * @package tests\runtime\data\handler\Post\actions
 */
class Delete extends AbstractAction
{
    /**
     * @return bool
     */
    public function execute() : bool
    {
        $this->post->deleteModel();
        return true;
    }
}