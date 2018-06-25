<?php
namespace tests\runtime\data\handlers\Post\actions;

/**
 * Class Update
 * @package tests\runtime\data\handlers\Post\actions
 */
class Update extends AbstractAction
{
    /**
     * @return bool
     */
    public function execute() : bool
    {
        $this->post->updateModel();
        return true;
    }
}