<?php
namespace tests\runtime\data\handlers\Post;

use tests\runtime\data\handlers\Post\interfaces\{
    Update
};
use ma3obblu\gii\generators\tests\handler\Post;

/**
 * Class PostHandler
 * @package tests\runtime\data\handlers\Post
 *
 * @property Post $post
 */
class PostHandler implements Update
{
    private $post;

    /**
     * PostHandler constructor.
     * @param Post $post
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
        return $this;
    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        return (new actions\Update($this->post))->execute();
    }
}