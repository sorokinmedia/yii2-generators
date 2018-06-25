<?php
namespace tests\runtime\data\handlers\Post\interfaces;

/**
 * Interface Delete
 * @package tests\runtime\data\handlers\Post\interfaces
 */
interface Delete
{
    /**
     * @return bool
     */
    public function delete() : bool;
}