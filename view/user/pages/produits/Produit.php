<?php

class Produit
{
    private int $id;
    private string $categorie;
    private string $nom;
    private string $marque;
    private string $materiel;
    private float $prix;
    private int $stock;
    private string $pays;
    private string $photo;

    public function __construct($id, $categorie, $nom, $marque, $materiel, $prix, $stock, $pays, $photo)
    {
        $this->id = $id;
        $this->categorie = $categorie;
        $this->nom = $nom;
        $this->marque = $marque;
        $this->materiel = $materiel;
        $this->prix = $prix;
        $this->stock = $stock;
        $this->pays = $pays;
        $this->photo = $photo;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCategorie(): string
    {
        return $this->categorie;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    public function getMateriel(): string
    {
        return $this->materiel;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function getStock(): int
    {
        return $this->stock;
    }

    public function getPays(): string
    {
        return $this->pays;
    }

    public function getPhoto(): string
    {
        return $this->photo;
    }
}