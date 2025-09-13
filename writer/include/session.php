<?php

session_start();

$sessionId = $_SESSION['id'];
if (!isset($_SESSION['id'])) {
    header("location:../login.php");
    exit();
}

include "../database/db.php";

$user = $db->query("SELECT * FROM users WHERE id=$sessionId")->fetch();
if ($user['type'] == "admin") {
    header("location:../admin/admin-panel-home.php");
    exit();
} elseif ($user['type'] == "user") {
    header("location:../user/user-panel-home.php");
    exit();
}