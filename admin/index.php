<?php
session_start();

if (isset($_SESSION['logout_success'])) {
    echo "<p class='alert alert-success'>" . $_SESSION['logout_success'] . "</p>";
    unset($_SESSION['logout_success']);
}

include_once "./partials/top.php";

?>
      <h2>Dashboard - Page principale</h2>
<?php

include_once "./partials/bottom.php";

?>