<?php

include "./include/session.php";

$needle = "pending";

if (isset($_GET['accept'])) {
    $acceptId = $_GET['accept'];
    $db->query("UPDATE news SET status='confirmation', request='no' WHERE id=$acceptId");
    header("location:./admin-pending.php");
    exit();
} elseif (isset($_GET['request'])) {
    $requestId = $_GET['request'];
    $db->query("UPDATE news SET status='pending', request='yes' WHERE id=$requestId");
    header("location:./admin-pending.php");
    exit();
} elseif (isset($_GET['reject'])) {
    $rejectId = $_GET['reject'];
    $db->query("UPDATE news SET status='reject', request='no' WHERE id=$rejectId");
    header("location:./admin-pending.php");
    exit();
}

$pendingNews = $db->query("SELECT * FROM news WHERE status='pending' ORDER BY id DESC");

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-3">
    <div class="row g-3">
        <div class="col-4">
            <form action="./admin-pending-search.php">
                <label for="inputTitle" class="form-label">جستجوی عنوان</label>
                <input type="text" class="form-control" id="inputTitle" name="title"
                    placeholder="قسمتی از عنوان را وارد کنید">
                <button class="btn btn-primary mt-2" name="setTitle">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./admin-pending-search.php">
                <label for="inputWriter" class="form-label">جستجوی نویسنده</label>
                <input type="text" class="form-control" id="inputWriter" name="writer"
                    placeholder="قسمتی از نام نویسنده را وارد کنید">
                <button class="btn btn-primary mt-2" name="setWriter">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./admin-pending-search.php">
                <label for="inputDate" class="form-label">جستجوی تاریخ</label>
                <input type="text" class="form-control" id="inputDate" name="date"
                    placeholder="روز/ماه/سال">
                <button class="btn btn-primary mt-2" name="setDate">جستجو</button>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h5>در انتظار تایید</h5>
    <div class="list-group mt-2">
        <?php
        if ($pendingNews->rowCount() > 0):
            foreach ($pendingNews as $pending):
                $writerId = $pending['writer_id'];
                $categoryId = $pending['category_id'];
                $writer = $db->query("SELECT * FROM users WHERE id=$writerId")->fetch();
                $category = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                $status = "در انتظار تایید";
                $request = $pending['request'] == "yes" ? 'درخواست ویرایش داده شده' : '';
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto">
                            <?= substr($pending['title'], 0, 80) ?>
                            <?php if (isset($status)): ?>
                                <span class="badge text-bg-secondary me-2">
                                    <?= $status ?>
                                </span>
                            <?php endif;
                            if ($request != ''): ?>
                                <span class="badge text-bg-secondary me-2">
                                    <?= $request ?>
                                </span>
                            <?php endif ?>
                        </h5>
                        <small><?= $category['name'] ?></small>
                        <a href="./admin-user-view.php?userId=<?= $writer['id'] ?>"
                            class="text-decoration-none">
                            <small class="mx-2">| <?= $writer['name'] ?> |</small>
                        </a>
                        <small><?= $pending['date'] ?></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <p class="my-1"><?= substr($pending['text'], 0, 150) ?></p>
                        </div>
                        <div class="col-4 text-start">
                            <a href="./admin-news-page.php?newsId=<?= $pending['id'] ?>"
                                class="btn btn-secondary">مشاهده</a>
                            <a href="./admin-pending.php?accept=<?= $pending['id'] ?>"
                                class="btn btn-success">تایید</a>
                            <a href="./admin-pending.php?request=<?= $pending['id'] ?>"
                                class="btn btn-primary">درخواست ویرایش</a>
                            <a href="./admin-pending.php?reject=<?= $pending['id'] ?>"
                                class="btn btn-danger">رد</a>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>خبری برای تایید وجود ندارد</p>";
        endif;
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>