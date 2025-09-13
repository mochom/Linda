<?php

include "./include/session.php";

if (isset($_GET['setName'])) {
    $name = $_GET['name'];
    $email = $user['email'];
    $comments = $db->query("SELECT * FROM comments WHERE email='$email' AND name LIKE '%$name%' ORDER BY id DESC");
} elseif (isset($_GET['setText'])) {
    $text = $_GET['text'];
    $email = $user['email'];
    $comments = $db->query("SELECT * FROM comments WHERE email='$email' AND text LIKE '%$text%' ORDER BY id DESC");
} else {
    header("location:./writer-comment.php");
    exit();
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<?php if ($user['status'] == 'inactive'): ?>
    <div class="container my-3">
        <p class="alert alert-danger" style="width: 350px;">شما مسدود شده اید.
            <br><small><small>دسترسی شما محدود شده است.</small></small>
        </p>
    </div>
<?php endif ?>

<div class="container mt-3">
    <div class="row g-3 justify-content-between">
        <div class="col-4">
            <form action="./writer-comment-search.php" method="get">
                <label for="inputName" class="form-label">جستجوی نام</label>
                <input type="text" class="form-control" id="inputName" name="name"
                    placeholder="قسمتی از نام را وارد کنید">
                <button class="btn btn-primary mt-2" name="setName">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./writer-comment-search.php" method="get">
                <label for="inputText" class="form-label">جستجوی متن</label>
                <input type="text" class="form-control" id="inputText" name="text"
                    placeholder="قسمتی از متن را وارد کنید">
                <button class="btn btn-primary mt-2" name="setText">جستجو</button>
            </form>
        </div>
    </div>
</div>

<div class="container text-center">
    <a href="./writer-comment.php" class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="container mt-5">
    <h5>نظرات</h5>
    <div class="list-group mt-2">
        <?php if ($comments->rowCount() > 0):
            foreach ($comments as $comment):
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto"><?= $comment['name'] ?></h5>
                        <small><?= $comment['date'] ?></small>
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <p class="my-1"><?= substr($comment['text'], 0, 150) ?></p>
                        </div>
                        <div class="col-6 text-start">
                            <a href="./writer-comment-view.php?commentId=<?= $comment['id'] ?>"
                                class="btn btn-secondary">مشاهده نظر</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-secondary'>نظری یافت نشد</p>";
        endif
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>