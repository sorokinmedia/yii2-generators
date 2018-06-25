<?php
namespace tests\runtime\data\handler\Post;

use tests\runtime\data\handler\Post\interfaces\{
    Create, Update, Delete
};
use ma3obblu\gii\generators\tests\handler\Post;

/**
 * Class PostHandler
 * @package tests\runtime\data\handler\Post
 */
class PostHandler implements Create, Update, Delete
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
    public function create() : bool
    {
        return (new actions\Create($this->post))->execute();
    }

    /**
     * @return bool
     */
    public function update() : bool
    {
        return (new actions\Update($this->post))->execute();
    }

    /**
     * @return bool
     */
    public function delete() : bool
    {
        return (new actions\Delete($this->post))->execute();
    }
}