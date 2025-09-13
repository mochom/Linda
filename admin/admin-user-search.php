<?php

include "./include/session.php";
if (isset($_GET['setName'])) {
    $name = $_GET['name'];
    $users = $db->query("SELECT * FROM users WHERE name LIKE '%$name%' ORDER BY id DESC");
} elseif (isset($_GET['setType'])) {
    $type = $_GET['type'];
    if ($type != 'all') {
        $users = $db->query("SELECT * FROM users WHERE type='$type' ORDER BY id DESC");
    } else {
        $users = $db->query("SELECT * FROM users ORDER BY id DESC");
    }
} elseif (isset($_GET['setDate'])) {
    $date = $_GET['date'];
    $users = $db->query("SELECT * FROM users WHERE date='$date' ORDER BY id DESC");
} else {
    header("location:./admin-user.php");
    exit();
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-3">
    <div class="row g-3">
        <div class="col-4">
            <form action="./admin-user-search.php">
                <label for="inputName" class="form-label">جستجوی نام</label>
                <input type="text" class="form-control" id="inputName" name="name"
                    placeholder="قسمتی از نام را وارد کنید">
                <button class="btn btn-primary mt-2" name="setName">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./admin-user-search.php">
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
            <form action="./admin-user-search.php">
                <label for="inputDate" class="form-label">جستجوی تاریخ عضویت</label>
                <input type="text" class="form-control" id="inputDate" name="date"
                    placeholder="روز/ماه/سال">
                <button class="btn btn-primary mt-2" name="setDate">جستجو</button>
            </form>
        </div>
    </div>
</div>

<div class="container text-center mt-5">
    <a href="./admin-user.php" class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="container mt-5">
    <h5>کاربران</h5>
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
                            <a href="./admin-user.php?blockId=<?= $user['id'] ?>"
                                class="btn btn-warning">مسدود کردن</a>
                            <a href="./admin-user-view.php?userId=<?= $user['id'] ?>"
                                class="btn btn-secondary">مشاهده کاربر</a>
                            <?php if ($writingRequest != '' || $unblockRequest != ''):
                                $typeRequest = ($writingRequest != '') ? "writing" : "unblock";
                                ?>
                                <a href="./admin-user.php?acceptId=<?= $user['id'] ?>&typeRequest=<?= $typeRequest ?>"
                                    class="btn btn-success">تایید</a>
                                <a href="./admin-user.php?rejectId=<?= $user['id'] ?>&typeRequest=<?= $typeRequest ?>"
                                    class="btn btn-danger">رد</a>
                            <?php endif ?>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>کاربری یافت نشد</p>";
        endif;
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>