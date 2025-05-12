<?php

class Commentaire
{
    private $id;
    private $post_id;
    private $content;
    private $author;
    private $timestamp;  // Nouvel attribut pour la date et l'heure du commentaire

    // Constructeur modifié pour accepter un timestamp optionnel
    public function __construct($post_id, $content, $author, $id = null, $timestamp = null)
    {
        $this->id = $id;
        $this->post_id = $post_id;
        $this->content = $content;
        $this->author = $author;
        $this->timestamp = $timestamp ?: date("Y-m-d H:i:s");  // Par défaut, utiliser l'heure actuelle
    }

    // Getter pour l'id
    public function getId()
    {
        return $this->id;
    }

    // Getter pour post_id
    public function getPostId()
    {
        return $this->post_id;
    }

    // Getter pour le contenu du commentaire
    public function getContent()
    {
        return $this->content;
    }

    // Getter pour l'auteur du commentaire
    public function getAuthor()
    {
        return $this->author;
    }

    // Getter pour le timestamp
    public function getTimestamp()
    {
        return $this->timestamp;
    }

    // Setter pour le timestamp
    public function setTimestamp($timestamp)
    {
        $this->timestamp = $timestamp;
    }
}
?>
