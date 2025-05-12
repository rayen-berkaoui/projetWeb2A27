<?php

class categorieProduitC {
    public static function getCategories() {
        $sql = "select * from categorieProduit";
        $db = config::getConnexion();
        try {
            return $db->query($sql);
        } catch (Exception $e) {
            die($e->getMessage());
        }
    }
}

