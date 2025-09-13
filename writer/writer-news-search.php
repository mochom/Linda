<?php

include "./include/session.php";

if (isset($_GET['setTitle'])) {
    $title = $_GET['title'];
    $news = $db->query("SELECT * FROM news WHERE writer_id=$sessionId AND title LIKE '%$title%' ORDER BY id DESC");
} else {
    header('location:./writer-news.php');
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

<div class="container">
    <div class="row gy-3 align-items-center">
        <div class="col-4">
            <form action="./writer-news-search.php" method="get">
                <label for="inputTitle" class="form-label">جستجوی عنوان</label>
                <input type="text" class="form-control" id="inputTitle" name="title"
                    placeholder="قسمتی از عنوان را وارد کنید">
                <button name="setTitle" class="btn btn-primary mt-2">جستجو</button>
            </form>
        </div>
    </div>
</div>

<div class="container text-center">
    <a href="./writer-news.php" class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="container mt-5">
    <h5>اخبار</h5>
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
                        <h5 class="mb-1 ms-auto">
                            <?= $new['title'] ?>
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
                                <a href="./writer-news.php?deleteId=<?= $new['id'] ?>"
                                    class="btn btn-danger">حذف</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-secondary'>خبری یافت نشد<p>";
        endif;
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>