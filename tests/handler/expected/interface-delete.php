<?php
namespace tests\runtime\data\handler\Post\interfaces;

/**
 * Interface Delete
 * @package tests\runtime\data\handler\Post\interfaces
 */
interface Delete
{
    /**
     * @return bool
     */
    public function delete() : bool;
}