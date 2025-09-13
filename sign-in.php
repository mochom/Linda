<?php

include "./include/session.php";

$emailMessage = "";
$nameMessage = "";
$userNameMessage = "";
$passwordMessage = "";
$successMessage = "";

if (isset($_POST['sign-in'])) {
    if (empty($_POST['email'])) {
        $emailMessage = "ایمیل را وارد کنید";
    } elseif (empty($_POST['name'])) {
        $nameMessage = "نام خود را وارد کنید";
    } elseif (empty($_POST['userName'])) {
        $userNameMessage = "نام کاربری را وارد کنید";
    } elseif (empty($_POST['password'])) {
        $passwordMessage = "رمز عبور را وارد کنید";
    } else {
        $email = $_POST['email'];
        $checkEmail = $db->query("SELECT * FROM users WHERE email='$email'")->fetch();

        $name = $_POST['name'];

        $userName = $_POST['userName'];
        $checkUserName = $db->query("SELECT * FROM users WHERE user_name='$userName'")->fetch();

        $password = $_POST['password'];

        $date = date("Y/m/d");

        if (!empty($checkEmail)) {
            $emailMessage = "ایمیل تکراری است";
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailMessage = "ایمیل نامعتبر است";
        } elseif (!empty($checkUserName)) {
            $userNameMessage = "نام کاربری تکراری است";
        } elseif (strlen($password) < 8) {
            $passwordMessage = "رمز عبور کمتر از 8 کاراکتر است";
        } else {
            $db->query("INSERT INTO users(name, email, user_name, password, type, status, unblock_request, writing_request, date) VALUES ('$name','$email','$userName','$password ','user','active','no','no','$date')");
            $successMessage = "ثبت نام با موفقیت انجام شد";
        }
    }
}

?>

<?php include "./include/header.php" ?>

<div class="text-center mt-3">
    <a href="./index.php" class="text-decoration-none h1 text-secondary">
        لیندا
    </a>
</div>

<div class="container mt-5 fw-bold align-items-center">
    <div class="row">
        <div class="col">
            <form class="row" method="post">
                <div class="col-12">
                    <label for="inputEmail" class="form-label">ایمیل</label>
                    <input type="text" class="form-control" id="inputEmail" name="email">
                    <p class="text-danger"><?= $emailMessage ?></p>
                </div>
                <div class="col-12 my-2">
                    <label for="inputName" class="form-label">نام</label>
                    <input type="text" class="form-control" id="inputName" name="name">
                    <p class="text-danger"><?= $nameMessage ?></p>
                </div>
                <div class="col-12 mb-2">
                    <label for="inputUserName" class="form-label">نام کاربری</label>
                    <input type="text" class="form-control" id="inputUserName"
                        name="userName">
                    <p class="text-danger"><?= $userNameMessage ?></p>
                </div>
                <div class="col-12 mb-2">
                    <label for="inputPassword" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control" id="inputPassword"
                        name="password">
                    <p class="text-danger"><?= $passwordMessage ?></p>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary" name="sign-in">ثبت
                        نام</button>
                    <p class="text-success"><?= $successMessage ?></p>
                </div>
            </form>
            <div class="my-3">
                <a href="./login.php" class="text-decoration-none">آیا از قبل ثبت نام کرده
                    اید؟</a>
            </div>
            <div class="my-3 text-center">
                <a href="./index.php" class="btn btn-primary">بازگشت</a>
            </div>
        </div>
        <div class="col">
            <img src="./images/linda.png" alt="linda-image" width="500px">
        </div>
    </div>
</div>

<?php include "./include/footer.php" ?>