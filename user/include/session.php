<?php

session_start();

$sessionId = $_SESSION['id'];
if (!isset($_SESSION['id'])) {
    header("location:../login.php");
    exit();
}

include "../database/db.php";

$user = $db->query("SELECT * FROM users WHERE id=$sessionId")->fetch();
if ($user['type'] == "writer") {
    header("location:../writer/writer-panel-home.php");
    exit();
} elseif ($user['type'] == "admin") {
    header("location:../admin/admin-panel-home.php");
    exit();
}