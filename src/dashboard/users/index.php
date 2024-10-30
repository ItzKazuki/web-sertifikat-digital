<?php
session_start();

include '../service/utility.php';

if(!isset($_SESSION['email']) && !isset($_SESSION['is_auth']) && $_SESSION['role'] != "admin") {
    return redirect("index.php");
}

?>