<?php
require_once '../../../src/models/Partner.php';
require_once '../../../config/Database.php';

if (isset($_GET['id'])) {
    $partner = new Partner();
    $result = $partner->deletePartner($_GET['id']);
    echo $result ? "success" : "error";
} else {
    echo "error";
}
