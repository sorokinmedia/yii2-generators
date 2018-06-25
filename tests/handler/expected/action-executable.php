<?php
namespace tests\runtime\data\handlers\Post\interfaces;

/**
 * Interface ActionExecutable
 * @package tests\runtime\data\handlers\Post\interfaces
 */
interface ActionExecutable
{
    /**
     * @return mixed
     */
    public function execute();
}