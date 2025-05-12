<?php
// config.php

class db {
    private static $conn;

    // Create and return a database connection
    public static function getConnexion() {
        if (self::$conn == null) {
            try {
                // Change the database credentials to your actual ones
                self::$conn = new PDO('mysql:host=localhost;dbname=db_html', 'root', '');
                self::$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                // Log the exception message and stop the script if unable to connect
                error_log($e->getMessage());
                die("Database connection failed");
            }
        }
        return self::$conn;
    }
}
?>
