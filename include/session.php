<?php
session_start();
include "./database/db.php";

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $user = $db->query("SELECT * FROM users WHERE id=$userId")->fetch();
    if ($user['type'] == "admin") {
        header("location:./admin/admin-panel-news.php");
        exit();
    } elseif ($user['type'] == "writer") {
        header("location:./writer/writer-panel-news.php");
        exit();
    } else {
        header("location:./user/user-panel-news.php");
        exit();
    }
}