<?php
require_once("../settings/core.php");
require_once("../controllers/brand_controller.php");

if (!isLoggedIn() || !isAdmin()) {
    header("Location: ../login/login.php");
    exit();
}





?>