<?php

include "./include/session.php";

$needle = "home";

if (isset($_GET['deleteId'])) {
    $deleteId = $_GET['deleteId'];
    $db->query("DELETE FROM news WHERE id=$deleteId");
    header("location:./writer-panel-home.php");
    exit();
}

$pendingNews = $db->query("SELECT * FROM news WHERE request='yes' AND writer_id=$sessionId ORDER BY id DESC LIMIT 5");
$news = $db->query("SELECT * FROM news WHERE writer_id=$sessionId ORDER BY id DESC LIMIT 5");

$email = $user['email'];
$comments = $db->query("SELECT * FROM comments WHERE email='$email' ORDER BY id DESC LIMIT 5");

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

<div class="container my-3">
    <div class="row">
        <div class="col">
            <h5>درخواست ویرایش</h5>
            <small>ادمین درخواست ویرایش این خبر ها را داده است.</small>
        </div>
        <div class="col text-start">
            <a href="./writer-pending.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php if ($pendingNews->rowCount() > 0):
            foreach ($pendingNews as $pending):
                $categoryId = $pending['category_id'];
                $category = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto"><?= $pending['title'] ?><span
                                class="badge text-bg-secondary me-2">نیاز به
                                ویرایش</span><span class="badge text-bg-secondary me-2">در
                                انتظار تایید</span></h5>
                        <small class="ms-2"><?= $category['name'] ?></small>
                        <small><?= $pending['date'] ?></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <p class="my-1"><?= substr($pending['text'], 0, 150) ?></p>
                        </div>
                        <div class="col-4 text-start">
                            <a href="./writer-news-page.php?newsId=<?= $pending['id'] ?>"
                                class="btn btn-secondary">مشاهده</a>
                            <?php if ($user['status'] != 'inactive'): ?>
                                <a href="./writer-edit-news.php?newsId=<?= $pending['id'] ?>"
                                    class="btn btn-success">ویرایش</a>
                                <a href="./writer-panel-home.php?deleteId=<?= $pending['id'] ?>"
                                    class="btn btn-danger">حذف</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-secondary'>خبری برای ویرایش وجود ندارد<p>";
        endif;
        ?>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col">
            <h5>آخرین خبر های شما</h5>
        </div>
        <div class="col text-start">
            <a href="./writer-news.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php if ($news->rowCount() > 0):
            foreach ($news as $new):
                $categoryId = $new['category_id'];
                $category = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                $status = "";
                $style = "";
                switch ($new['status']) {
                    case 'confirmation':
                        $status = "تایید شده";
                        $style = "text-bg-success";
                        break;
                    case 'reject':
                        $status = "رد شده";
                        $style = "text-bg-danger";
                        break;
                    case 'pending':
                        $status = "در انتظار تایید";
                        $style = "text-bg-secondary";
                        break;
                    default:
                        $status = "نامعتبر";
                        $style = "text-bg-warning";
                        break;
                }
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto"><?= $new['title'] ?>
                            <?php if ($new['request'] == 'yes'): ?>
                                <span class="badge text-bg-secondary me-2">نیاز به
                                    ویرایش
                                </span>
                            <?php endif ?>
                            <span class="badge <?= $style ?> me-2">
                                <?= $status ?>
                            </span>
                        </h5>
                        <small class="ms-2"><?= $category['name'] ?></small>
                        <small><?= $new['date'] ?></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <p class="my-1"><?= substr($new['text'], 0, 150) ?></p>
                        </div>
                        <div class="col-4 text-start">
                            <a href="./writer-news-page.php?newsId=<?= $new['id'] ?>"
                                class="btn btn-secondary">مشاهده</a>
                            <?php if ($user['status'] != 'inactive'): ?>
                                <a href="./writer-edit-news.php?newsId=<?= $new['id'] ?>"
                                    class="btn btn-success">ویرایش</a>
                                <a href="./writer-panel-home.php?deleteId=<?= $new['id'] ?>"
                                    class="btn btn-danger">حذف</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-secondary'>خبری وجود ندارد<p>";
        endif;
        ?>
    </div>
</div>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h5>نظرات اخیر</h5>
        </div>
        <div class="col text-start">
            <a href="./writer-comment.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php
        if ($comments->rowCount() > 0):
            foreach ($comments as $comment):
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="row">
                        <div class="col-6">
                            <p class="my-1"><?= $comment['text'] ?></p>
                            <small><?= $comment['date'] ?></small>
                        </div>
                        <div class="col-6 text-start">
                            <a href="./writer-comment-view.php?commentId=<?= $comment['id'] ?>"
                                class="btn btn-secondary">مشاهده نظر</a>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>نظری ندارید.</p>";
        endif
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>