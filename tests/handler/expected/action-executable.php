<?php
namespace tests\runtime\data\handler\Post\interfaces;

/**
 * Interface ActionExecutable
 * @package tests\runtime\data\handler\Post\interfaces
 */
interface ActionExecutable
{
    /**
     * @return mixed
     */
    public function execute();
}