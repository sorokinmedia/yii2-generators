<?php
namespace tests\runtime\data\handlers\Post\actions;

use tests\runtime\data\handlers\Post\interfaces\ActionExecutable;
use ma3obblu\gii\generators\tests\handler\Post;

/**
 * Class AbstractAction
 * @package tests\runtime\data\handlers\Post\actions
 *
 * @property Post $post
 */
abstract class AbstractAction implements ActionExecutable
{
    protected $post;

    /**
     * AbstractAction constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        return $this;
    }
}