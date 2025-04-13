<?php

require_once('C:\xampp1\htdocs\web\projet\model\user.php');


class UserController {
    private $users = []; // Exemple simple, à remplacer par base de données

    public function addUser(User $user) {
        $this->users[] = $user;
    }

    public function getAllUsers() {
        return $this->users;
    }

    public function getUserByEmail($email) {
        foreach ($this->users as $user) {
            if ($user->getEmail() === $email) {
                return $user;
            }
        }
        return null;
    }

    public function verifyCredentials($email, $mdp) {
        $user = $this->getUserByEmail($email);
        if ($user && $user->getMdp() === $mdp) {
            return $user;
        }
        return null;
    }

    public function updatePassword($email, $newPassword) {
        $user = $this->getUserByEmail($email);
        if ($user) {
            $user->setMdp($newPassword);
            return true;
        }
        return false;
    }
}
?>
