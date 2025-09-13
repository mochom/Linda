<?php

include "./include/session.php";

$needle = "information";

switch ($user['type']) {
    case 'admin':
        $type = "ادمین";
        break;
    case 'writer':
        $type = "نویسنده";
        break;
    case 'user':
        $type = "کاربر";
        break;
    default:
        $type = "نامعتبر";
        break;
}
switch ($user['status']) {
    case 'active':
        $status = "فعال";
        break;
    case 'inactive':
        $status = "مسدود";
        break;
    default:
        $status = "نامعتبر";
        break;
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container align-items-center mt-4">
    <form class="row gy-3">
        <div class="col-6">
            <p class="fw-bolder">ایمیل:</p>
            <p><?= $user['email'] ?></p>
        </div>
        <div class="col-6">
            <p class="fw-bolder">نام:</p>
            <p><?= $user['name'] ?></p>
        </div>
        <div class="col-6">
            <p class="fw-bolder">نام کاربری:</p>
            <p><?= $user['user_name'] ?></p>
        </div>
        <div class="col-6">
            <p class="fw-bolder">نوع کاربر:</p>
            <p><?= $type ?></p>
        </div>
        <div class="col-6">
            <p class="fw-bolder">تاریخ عضویت:</p>
            <p><?= $user['date'] ?></p>
        </div>
        <div class="col-6">
            <p class="fw-bolder">وضعیت کاربر:</p>
            <p><?= $status ?></p>
        </div>
        <div class="col-12 text-center">
            <a href="./writer-panel-home.php"
                class="text-decoration-none btn btn-primary">بازگشت</a>
        </div>
    </form>
</div>

<?php include "./include/footer.php" ?>