<?php

namespace MVCPHP\models;
/**
 * Class Post
 * @package MVCPHP\models
 */
class Post
{
    private $db;

    /**
     * Post constructor.
     */
    public function __construct()
    {
        global $registry;
        $this->db = $registry->get('db');
    }


    /**
     * @param $data
     * @return boolean
     */
    public function add($data)
    {
        return $this->db->insert('posts',[$data['username'] ,$data['title'],$data['content'],$data['photo']],'username,postTitle,postContent,photoName');
    }

    /**
     * @return object
     */
    public function allPosts()
    {
        return $this->db->select('posts');
    }

    /**
     * @param $id
     * @return object
     */
    public function getPostById($id)
    {
        return $this->db->select('posts','*',"postId ='$id'");
    }

    /**
     * @param $id
     * @return object
     */
    public function getPostComments($id)
    {

        return $this->db->select('comments INNER JOIN users ON comments.username = users.username','comments.*, users.avatarName ',"postId= '$id'",'comments.commentDate DESC');
    }
}