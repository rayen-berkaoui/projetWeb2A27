<?php
class Post {
    private $id;
    private $titre;
    private $description;
    private $dateCreation;
    private $statut;

    public function __construct($titre, $description, $dateCreation, $statut) {
        $this->titre = $titre;
        $this->description = $description;
        $this->dateCreation = $dateCreation;
        $this->statut = $statut;
    }

    // Getter methods for each property
    public function getTitre() {
        return $this->titre;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDateCreation() {
        return $this->dateCreation;
    }

    public function getStatut() {
        return $this->statut;
    }

    // Optional: Setter methods if needed
    public function setTitre($titre) {
        $this->titre = $titre;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function setDateCreation($dateCreation) {
        $this->dateCreation = $dateCreation;
    }

    public function setStatut($statut) {
        $this->statut = $statut;
    }
}
?>
