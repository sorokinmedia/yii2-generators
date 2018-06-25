<?php
namespace tests\runtime\data\handler\Post\actions;

/**
 * Class Create
 * @package tests\runtime\data\handler\Post\actions
 */
class Create extends AbstractAction
{
    /**
     * @return bool
     */
    public function execute() : bool
    {
        $this->post->insertModel();
        return true;
    }
}