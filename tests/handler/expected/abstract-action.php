<?php
namespace tests\runtime\data\handler\Post\actions;

use tests\runtime\data\handler\Post\interfaces\ActionExecutable;
use ma3obblu\gii\generators\tests\handler\Post;

/**
 * Class AbstractAction
 * @package tests\runtime\data\handler\Post\actions
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