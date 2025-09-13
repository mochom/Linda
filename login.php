<?php

include "./include/session.php";

$userNameMessage = "";
$passwordMessage = "";
$invalidMessage = "";

if (isset($_POST['login'])) {
    if (empty($_POST['userName'])) {
        $userNameMessage = "نام کاربری را وارد کنید";
    } elseif (empty($_POST['password'])) {
        $passwordMessage = "رمز عبور را وارد کنید";
    } else {
        $userName = $_POST['userName'];
        $password = $_POST['password'];
        $user = $db->query("SELECT * FROM users WHERE user_name='$userName' AND password='$password'")->fetch();
        if (empty($user)) {
            $invalidMessage = "نام کاربری یا رمز عبور اشتباه است.";
        } elseif ($user['type'] == "admin") {
            $_SESSION['id'] = $user['id'];
            $userId = $user['id'];
            $db->query("INSERT INTO sessions (user_id) VALUES ($userId)");
            header("location:./admin/admin-panel-home.php");
            exit();
        } elseif ($user['type'] == "writer") {
            $_SESSION['id'] = $user['id'];
            $userId = $user['id'];
            $db->query("INSERT INTO sessions (user_id) VALUES ($userId)");
            header("location:./writer/writer-panel-home.php");
            exit();
        } else {
            $_SESSION['id'] = $user['id'];
            $userId = $user['id'];
            $db->query("INSERT INTO sessions (user_id) VALUES ($userId)");
            header("location:./user/user-panel-home.php");
            exit();
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
            <form action="" method="post" class="row">
                <div class="col-12">
                    <label for="inputUserName" class="form-label">نام کاربری</label>
                    <input type="text" class="form-control" id="inputUserName"
                        name="userName">
                    <p class="text-danger"><?= $userNameMessage ?></p>
                </div>
                <div class="col-12 my-3">
                    <label for="inputPassword" class="form-label">رمز عبور</label>
                    <input type="password" class="form-control" id="inputPassword"
                        name="password">
                    <p class="text-danger"><?= $passwordMessage ?></p>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary"
                        name="login">ورود</button>
                    <p class="text-danger"><?= $invalidMessage ?></p>
                </div>
            </form>
            <div class="my-3">
                <a href="./sign-in.php" class="text-decoration-none">آیا ثبت نام نکرده
                    اید؟</a>
            </div>
            <div class="my-5 text-center">
                <a href="./index.php"
                    class="text-decoration-none btn btn-primary">بازگشت</a>
            </div>
        </div>
        <div class="col">
            <img src="./images/linda.png" alt="linda-image" width="500px">
        </div>
    </div>
</div>

<?php include "./include/footer.php" ?>