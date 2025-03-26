<?php
include_once "function.php";
startSecureSession();

if (!isset($_SESSION['is_logged']) || $_SESSION['is_logged'] !== true) {
    header("Location: ../admin/login.php");
    exit;
}
?>