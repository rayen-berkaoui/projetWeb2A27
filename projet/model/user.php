<?php
class User {
    private $id_user;
    private $username;
    private $role;
    private $status;
    private $email;
    private $mdp;
    private $numero;

    // Constructeur
    public function __construct($username = "", $role = "", $status = "", $email = "", $mdp = "", $numero = "") {
        $this->username = $username;
        $this->role = $role;
        $this->status = $status;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->numero = $numero;
    }

    // Getters
    public function getIdUser() {
        return $this->id_user;
    }

    public function getUsername() {
        return $this->username;
    }

    public function getRole() {
        return $this->role;
    }

    public function getStatus() {
        return $this->status;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getMdp() {
        return $this->mdp;
    }

    public function getNumero() {
        return $this->numero;
    }

    // Setters
    public function setIdUser($id_user) {
        $this->id_user = $id_user;
    }

    public function setUsername($username) {
        $this->username = $username;
    }

    public function setRole($role) {
        $this->role = $role;
    }

    public function setStatus($status) {
        $this->status = $status;
    }

    public function setEmail($email) {
        $this->email = $email;
    }

    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }

    public function setNumero($numero) {
        $this->numero = $numero;
    }
}
?>
