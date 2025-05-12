<?php

class Posts {
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
}
