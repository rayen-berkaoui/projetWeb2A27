<?php

class Commentaire
{
    private $id;
    private $postId;
    private $content;
    private $author;
    private $timestamp;

    // Constructeur pour initialiser les propriétés
    public function __construct($postId, $content, $author, $timestamp)
    {
        $this->postId = $postId;
        $this->content = $content;
        $this->author = $author;
        $this->timestamp = $timestamp;
    }

    // Getters et setters
    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function setPostId($postId)
    {
        $this->postId = $postId;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function setAuthor($author)
    {
        $this->author = $author;
    }

    public function getTimestamp()
    {
        return $this->timestamp;
    }

    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
?>
