<?php

include "./include/session.php";

$needle = "home";

if (isset($_GET['accept'])) {
    $acceptId = $_GET['accept'];
    $db->query("UPDATE news SET status='confirmation', request='no' WHERE id=$acceptId");
    header("location:./admin-panel-home.php");
    exit();
} elseif (isset($_GET['request'])) {
    $requestId = $_GET['request'];
    $db->query("UPDATE news SET status='pending', request='yes' WHERE id=$requestId");
    header("location:./admin-panel-home.php");
    exit();
} elseif (isset($_GET['reject'])) {
    $rejectId = $_GET['reject'];
    $db->query("UPDATE news SET status='reject', request='no' WHERE id=$rejectId");
    header("location:./admin-panel-home.php");
    exit();
}

if (isset($_GET['blockId'])) {
    $blockId = $_GET['blockId'];
    $db->query("UPDATE users SET status='inactive', writing_request='no' WHERE id=$blockId");
    header("location:./admin-panel-home.php");
    exit();
} elseif (isset($_GET['acceptId'])) {
    $acceptId = $_GET['acceptId'];
    $typeRequest = $_GET['typeRequest'];
    if ($typeRequest == "writing") {
        $db->query("UPDATE users SET type='writer', writing_request='no' WHERE id=$acceptId");
    } elseif ($typeRequest == "unblock") {
        $db->query("UPDATE users SET status='active', unblock_request='no' WHERE id=$acceptId");
    }
    header("location:./admin-panel-home.php");
    exit();
} elseif (isset($_GET['rejectId'])) {
    $rejectId = $_GET['rejectId'];
    $typeRequest = $_GET['typeRequest'];
    if ($typeRequest == "writing") {
        $db->query("UPDATE users SET writing_request='no' WHERE id=$rejectId");
    } elseif ($typeRequest == "unblock") {
        $db->query("UPDATE users SET unblock_request='no' WHERE id=$rejectId");
    }
    header("location:./admin-panel-home.php");
    exit();
}

if (isset($_GET['acceptComment'])) {
    $commentId = $_GET['acceptComment'];
    $db->query("UPDATE comments SET status='confirmation' WHERE id=$commentId");
    header("location:./admin-panel-home.php");
    exit();
} elseif (isset($_GET['rejectComment'])) {
    $commentId = $_GET['rejectComment'];
    $db->query("UPDATE comments SET status='reject' WHERE id=$commentId");
    header("location:./admin-panel-home.php");
    exit();
}

$pendingNews = $db->query("SELECT * FROM news WHERE status='pending' ORDER BY id DESC LIMIT 5");
$news = $db->query("SELECT * FROM news ORDER BY id DESC LIMIT 5");
$userRequest = $db->query("SELECT * FROM users WHERE unblock_request = 'yes' OR writing_request = 'yes' ORDER BY id DESC LIMIT 5");
$users = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT 5");
$comments = $db->query("SELECT * FROM comments ORDER BY id DESC LIMIT 5");

?>


<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>


<div class="container my-5">
    <div class="row align-items-center">
        <div class="col">
            <h5>در انتظار تایید</h5>
        </div>
        <div class="col text-start">
            <a href="./admin-pending.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
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
                            <a href="./admin-panel-home.php?accept=<?= $pending['id'] ?>"
                                class="btn btn-success">تایید</a>
                            <a href="./admin-panel-home.php?request=<?= $pending['id'] ?>"
                                class="btn btn-primary">درخواست ویرایش</a>
                            <a href="./admin-panel-home.php?reject=<?= $pending['id'] ?>"
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

<div class="container">
    <div class="row">
        <div class="col">
            <h5>آخرین اخبار</h5>
        </div>
        <div class="col text-start">
            <a href="./admin-news.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php
        if ($news->rowCount() > 0):
            foreach ($news as $new):
                $writerId = $new['writer_id'];
                $categoryId = $new['category_id'];
                $writer = $db->query("SELECT * FROM users WHERE id=$writerId")->fetch();
                $category = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                if ($new['status'] == "confirmation") {
                    $status = "تایید شده";
                    $style = 'text-bg-success';
                } elseif ($new['status'] == "reject") {
                    $status = "رد شده";
                    $style = 'text-bg-danger';
                } else {
                    $status = "در انتظار تایید";
                    $style = 'text-bg-secondary';
                }
                $request = $new['request'] == "yes" ? 'درخواست ویرایش داده شده' : '';
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto">
                            <?= substr($new['title'], 0, 80) ?>
                            <?php if (isset($status)): ?>
                                <span class="badge <?= $style ?> me-2">
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
                        <small><?= $new['date'] ?></small>
                    </div>
                    <div class="row">
                        <div class="col-8">
                            <p class="my-1"><?= substr($new['text'], 0, 150) ?></p>
                        </div>
                        <div class="col-4 text-start">
                            <a href="./admin-news-page.php?newsId=<?= $new['id'] ?>"
                                class="btn btn-secondary">مشاهده</a>
                            <a href="./admin-panel-home.php?accept=<?= $new['id'] ?>"
                                class="btn btn-success">تایید</a>
                            <a href="./admin-panel-home.php?request=<?= $new['id'] ?>"
                                class="btn btn-primary">درخواست ویرایش</a>
                            <a href="./admin-panel-home.php?reject=<?= $new['id'] ?>"
                                class="btn btn-danger">رد</a>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>خبری وجود ندارد</p>";
        endif;
        ?>
    </div>
</div>

<div class="container my-5">
    <div class="row">
        <div class="col">
            <h5>درخواست های کاربران</h5>
        </div>
        <div class="col text-start">
            <a href="./admin-request.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php
        if ($userRequest->rowCount() > 0):
            foreach ($userRequest as $request):
                $status = "";
                $style = "";
                if ($request['status'] == "active") {
                    $status = "فعال";
                    $style = "text-primary";
                } else {
                    $status = "مسدود";
                    $style = "text-danger";
                }

                $writingRequest = $request['writing_request'] == "yes" ? 'درخواست نویسندگی' : '';
                $unblockRequest = $request['unblock_request'] == "yes" ? 'درخواست رفع مسدودیت' : '';

                if ($request['type'] == "admin") {
                    $type = "ادمین";
                } elseif ($request['type'] == "writer") {
                    $type = "نویسنده";
                } else {
                    $type = "کاربر";
                }

                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto"><?= $request['name'] ?>
                            <?php if ($writingRequest != ''): ?>
                                <span class="badge text-bg-primary me-2"><?= $writingRequest ?></span>
                            <?php endif;
                            if ($unblockRequest != ''): ?>
                                <span class="badge text-bg-warning me-2"><?= $unblockRequest ?></span>
                            <?php endif ?>
                        </h5>
                        <small class="ms-2">وضعیت:</small>
                        <small class="ms-2 <?= $style ?>"><?= $status ?></small>
                        <small class="ms-2">|| تاریخ عضویت:</small>
                        <small><?= $request['date'] ?></small>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-primary"><?= $type ?></span>
                        </div>
                        <div class="col-6 text-start">
                            <a href="./admin-panel-home.php?blockId=<?= $request['id'] ?>"
                                class="btn btn-warning">مسدود کردن</a>
                            <a href="./admin-user-view.php?userId=<?= $request['id'] ?>"
                                class="btn btn-secondary">مشاهده کاربر</a>
                            <?php if ($writingRequest != '' || $unblockRequest != ''):
                                $typeRequest = ($writingRequest != '') ? "writing" : "unblock";
                                ?>
                                <a href="./admin-panel-home.php?acceptId=<?= $request['id'] ?>&typeRequest=<?= $typeRequest ?>"
                                    class="btn btn-success">تایید</a>
                                <a href="./admin-panel-home.php?rejectId=<?= $request['id'] ?>&typeRequest=<?= $typeRequest ?>"
                                    class="btn btn-danger">رد</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>درخواستی وجود ندارد</p>";
        endif;
        ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col">
            <h5>کاربران جدید</h5>
        </div>
        <div class="col text-start">
            <a href="./admin-user.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php
        if ($users->rowCount() > 0):
            foreach ($users as $user):
                $status = "";
                $style = "";
                if ($user['status'] == "active") {
                    $status = "فعال";
                    $style = "text-primary";
                } else {
                    $status = "مسدود";
                    $style = "text-danger";
                }

                $writingRequest = $user['writing_request'] == "yes" ? 'درخواست نویسندگی' : '';
                $unblockRequest = $user['unblock_request'] == "yes" ? 'درخواست رفع مسدودیت' : '';

                if ($user['type'] == "admin") {
                    $type = "ادمین";
                } elseif ($user['type'] == "writer") {
                    $type = "نویسنده";
                } else {
                    $type = "کاربر";
                }

                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="d-flex w-100">
                        <h5 class="mb-1 ms-auto"><?= $user['name'] ?>
                            <?php if ($writingRequest != ''): ?>
                                <span class="badge text-bg-primary me-2"><?= $writingRequest ?></span>
                            <?php endif;
                            if ($unblockRequest != ''): ?>
                                <span class="badge text-bg-warning me-2"><?= $unblockRequest ?></span>
                            <?php endif ?>
                        </h5>
                        <small class="ms-2">وضعیت:</small>
                        <small class="ms-2 <?= $style ?>"><?= $status ?></small>
                        <small class="ms-2">|| تاریخ عضویت:</small>
                        <small><?= $user['date'] ?></small>
                    </div>
                    <div class="row align-items-center">
                        <div class="col-6">
                            <span class="text-primary"><?= $type ?></span>
                        </div>
                        <div class="col-6 text-start">
                            <a href="./admin-panel-home.php?blockId=<?= $user['id'] ?>"
                                class="btn btn-warning">مسدود کردن</a>
                            <a href="./admin-user-view.php?userId=<?= $user['id'] ?>"
                                class="btn btn-secondary">مشاهده کاربر</a>
                            <?php if ($writingRequest != '' || $unblockRequest != ''):
                                $typeRequest = ($writingRequest != '') ? "writing" : "unblock";
                                ?>
                                <a href="./admin-panel-home.php?acceptId=<?= $user['id'] ?>&typeRequest=<?= $typeRequest ?>"
                                    class="btn btn-success">تایید</a>
                                <a href="./admin-panel-home.php?rejectId=<?= $user['id'] ?>&typeRequest=<?= $typeRequest ?>"
                                    class="btn btn-danger">رد</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>کاربری وجود ندارد</p>";
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
            <a href="./admin-comment.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
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
                            <a href="./admin-panel-home.php?acceptComment=<?= $comment['id'] ?>"
                                class="btn btn-success">تایید</a>
                            <a href="./admin-panel-home.php?rejectComment=<?= $comment['id'] ?>"
                                class="btn btn-danger">رد</a>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>نظری وجود ندارد</p>";
        endif;
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>