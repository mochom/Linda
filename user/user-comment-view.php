<?php

include "./include/session.php";

if (isset($_GET['commentId'])) {
    $commentId = $_GET['commentId'];
    $comment = $db->query("SELECT * FROM comments WHERE id=$commentId")->fetch();
    $user = $db->query("SELECT * FROM users WHERE id=$sessionId")->fetch();
    if ($user['email'] != $comment['email']) {
        header("location:./user-comment.php");
        exit();
    }
} else {
    header("location:./user-comment.php");
    exit();
}


?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container my-5">
    <div class="row text-center justify-content-center">
        <div class="col-4">
            <p class="fw-bolder">تاریخ: </p>
            <p><?= $comment['date'] ?></p>
        </div>
        <div class="col-12 text-end my-3">
            <p class="fw-bolder">متن نظر: </p>
            <p><?= $comment['text'] ?></p>
        </div>
    </div>
</div>

<div class="container text-center">
    <a href="./user-comment.php" class="btn btn-outline-secondary">بازگشت</a>
</div>

<?php include "./include/footer.php" ?>