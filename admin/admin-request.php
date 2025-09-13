<?php

include "./include/session.php";

$needle = "request";

if (isset($_GET['blockId'])) {
    $blockId = $_GET['blockId'];
    $db->query("UPDATE users SET status='inactive', writing_request='no' WHERE id=$blockId");
    header("location:./admin-request.php");
    exit();
} elseif (isset($_GET['acceptId'])) {
    $acceptId = $_GET['acceptId'];
    $typeRequest = $_GET['typeRequest'];
    if ($typeRequest == "writing") {
        $db->query("UPDATE users SET type='writer', writing_request='no' WHERE id=$acceptId");
    } elseif ($typeRequest == "unblock") {
        $db->query("UPDATE users SET status='active', unblock_request='no' WHERE id=$acceptId");
    }
    header("location:./admin-request.php");
    exit();
} elseif (isset($_GET['rejectId'])) {
    $rejectId = $_GET['rejectId'];
    $typeRequest = $_GET['typeRequest'];
    if ($typeRequest == "writing") {
        $db->query("UPDATE users SET writing_request='no' WHERE id=$rejectId");
    } elseif ($typeRequest == "unblock") {
        $db->query("UPDATE users SET unblock_request='no' WHERE id=$rejectId");
    }
    header("location:./admin-request.php");
    exit();
}

$userRequest = $db->query("SELECT * FROM users WHERE unblock_request = 'yes' OR writing_request = 'yes' ORDER BY id DESC");

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-3">
    <div class="row g-3">
        <div class="col-4">
            <form action="./admin-request-search.php">
                <label for="inputName" class="form-label">جستجوی نام</label>
                <input type="text" class="form-control" id="inputName" name="name"
                    placeholder="قسمتی از نام را وارد کنید">
                <button class="btn btn-primary mt-2" name="setName">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./admin-request-search.php">
                <label for="inputType" class="form-label">جستجوی نوع کاربر</label>
                <select class="form-select" id="inputType" name="type">
                    <option value="all" selected>نوع کاربر را انتخاب کنید</option>
                    <option value="writer">نویسنده</option>
                    <option value="user">کاربر عادی</option>
                    <option value="admin">مدیر</option>
                </select>
                <button class="btn btn-primary mt-2" name="setType">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./admin-request-search.php">
                <label for="inputDate" class="form-label">جستجوی تاریخ عضویت</label>
                <input type="text" class="form-control" id="inputDate" name="date"
                    placeholder="روز/ماه/سال">
                <button class="btn btn-primary mt-2" name="setDate">جستجو</button>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h5>درخواست های کاربران</h5>
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
                            <a href="./admin-request.php?blockId=<?= $request['id'] ?>"
                                class="btn btn-warning">مسدود کردن</a>
                            <a href="./admin-user-view.php?userId=<?= $request['id'] ?>"
                                class="btn btn-secondary">مشاهده کاربر</a>
                            <?php if ($writingRequest != '' || $unblockRequest != ''):
                                $typeRequest = ($writingRequest != '') ? "writing" : "unblock";
                                ?>
                                <a href="./admin-request.php?acceptId=<?= $request['id'] ?>&typeRequest=<?= $typeRequest ?>"
                                    class="btn btn-success">تایید</a>
                                <a href="./admin-request.php?rejectId=<?= $request['id'] ?>&typeRequest=<?= $typeRequest ?>"
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

<?php include "./include/footer.php" ?>