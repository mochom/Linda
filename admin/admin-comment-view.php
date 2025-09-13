<?php

include "./include/session.php";

if (isset($_GET['commentId'])) {
    $commentId = $_GET['commentId'];
    $comment = $db->query("SELECT * FROM comments WHERE id=$commentId")->fetch();
} else {
    header("location:./admin-comment.php");
    exit();
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-5">
    <div class="row text-center">
        <div class="col">
            <p class="fw-bolder">نام: </p>
            <p><?= $comment['name'] ?></p>
        </div>
        <div class="col">
            <p class="fw-bolder">ایمیل: </p>
            <p><?= $comment['email'] ?></p>
        </div>
        <div class="col">
            <p class="fw-bolder">تاریخ: </p>
            <p><?= $comment['date'] ?></p>
        </div>
        <div class="col-12 text-end my-3">
            <p class="fw-bolder">متن نظر: </p>
            <p><?= $comment['text'] ?></p>
        </div>
        <div>
            <a href="./admin-panel-home.php" class="btn btn-outline-secondary">بازگشت</a>
        </div>
    </div>
</div>

<?php include "./include/footer.php" ?>