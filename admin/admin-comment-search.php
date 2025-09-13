<?php

include "./include/session.php";

if (isset($_GET['setName'])) {
    $name = $_GET['name'];
    $comments = $db->query("SELECT * FROM comments WHERE name LIKE '%$name%' ORDER BY id DESC");
} elseif (isset($_GET['setEmail'])) {
    $email = $_GET['email'];
    $comments = $db->query("SELECT * FROM comments WHERE email LIKE '%$email%' ORDER BY id DESC");
} elseif (isset($_GET['setText'])) {
    $text = $_GET['text'];
    $comments = $db->query("SELECT * FROM comments WHERE text LIKE '%$text%' ORDER BY id DESC");
} else {
    header("location:./admin-comment.php");
    exit();
}


?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-3">
    <div class="row g-3">
        <div class="col-4">
            <form action="./admin-comment-search.php">
                <label for="inputName" class="form-label">جستجوی نام</label>
                <input type="text" class="form-control" id="inputName" name="name"
                    placeholder="قسمتی از نام کاربر را وارد کنید">
                <button class="btn btn-primary mt-2" name="setName">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./admin-comment-search.php">
                <label for="inputEmail" class="form-label">جستجوی ایمیل</label>
                <input type="text" class="form-control" id="inputEmail" name="email"
                    placeholder="قسمتی از ایمیل را وارد کنید">
                <button class="btn btn-primary mt-2" name="setEmail">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./admin-comment-search.php">
                <label for="inputText" class="form-label">جستجوی متن</label>
                <input type="text" class="form-control" id="inputText" name="text"
                    placeholder="قسمتی از متن را وارد کنید">
                <button class="btn btn-primary mt-2" name="setText">جستجو</button>
            </form>
        </div>
    </div>
</div>

<div class="container text-center mt-5">
    <a href="./admin-comment.php" class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="container mt-5">
    <h5>نظرات</h5>
    <div class="list-group mt-2">
        <?php
        if ($comments->rowCount() > 0):
            foreach ($comments as $comment):
                if ($comment["status"] == "confirmation") {
                    $status = "تایید شده";
                    $style = "text-bg-success";
                } elseif ($comment["status"] == "reject") {
                    $status = "رد شده";
                    $style = "text-bg-danger";
                } else {
                    $status = "در انتظار تایید";
                    $style = "text-bg-secondary";
                }
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto"><?= $comment['name'] ?><span
                                class="badge <?= $style ?> me-2"><?= $status ?></span>
                        </h5>
                        <small><?= $comment['date'] ?></small>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <span class="text-primary"><?= $comment['email'] ?></span>
                            <p class="my-1"><?= substr($comment['text'], 0, 150) ?></p>
                        </div>
                        <div class="col-6 text-start">
                            <a href="./admin-comment-view.php?commentId=<?= $comment['id'] ?>"
                                class="btn btn-secondary">مشاهده نظر</a>
                            <a href="./admin-comment.php?acceptComment=<?= $comment['id'] ?>"
                                class="btn btn-success">تایید</a>
                            <a href="./admin-comment.php?rejectComment=<?= $comment['id'] ?>"
                                class="btn btn-danger">رد</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-secondary'>نظری یافت نشد</p>";
        endif;
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>