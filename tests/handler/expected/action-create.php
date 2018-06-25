<?php
namespace tests\runtime\data\handlers\Post\actions;

/**
 * Class Create
 * @package tests\runtime\data\handlers\Post\actions
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