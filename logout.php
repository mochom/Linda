<?php
include "./database/db.php";

session_start();
$userId = $_SESSION['id'];
$db->query("DELETE FROM sessions WHERE user_id=$userId");
session_unset();
session_destroy();

header("location:./login.php");
exit();