<?php
namespace tests\runtime\data\handler\Post\actions;

/**
 * Class Update
 * @package tests\runtime\data\handler\Post\actions
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