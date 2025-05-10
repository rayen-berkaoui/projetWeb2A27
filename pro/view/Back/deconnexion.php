<?php
session_start();
session_destroy(); // On détruit la session
header("Location: view\assets\Js\l2.js");
exit();
?>