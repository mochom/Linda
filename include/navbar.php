<?php

session_start();
include "./database/db.php";

if (isset($_SESSION['id'])) {
    $userId = $_SESSION['id'];
    $user = $db->query("SELECT * FROM users WHERE id=$userId")->fetch();
    if($user['type'] == 'admin'){
        $path = "./admin/admin-panel-home.php";
    } elseif ($user['type'] == 'writer') {
        $path = "./writer/writer-panel-home.php";
    } else {
        $path = "./user/user-panel-home.php";
    }
}

$categories = $db->query("SELECT * FROM categories");

?>
<nav class="navbar navbar-expand-lg bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand text-white" href="./index.php">
            <i class="bi bi-flower1 d-inline-block align-text-top"></i>
            لیندا
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
            data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup"
            aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
            <div class="navbar-nav me-3">
                <a class="nav-link text-white" aria-current="page" href="./news.php">خبر
                    ها</a>
            </div>
            <div class="navbar-nav me-3">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        دسته بندی ها
                    </a>
                    <ul class="dropdown-menu text-end">
                        <?php if ($categories->rowCount() > 0):
                            foreach ($categories as $category): ?>
                                <li><a class="dropdown-item"
                                        href="./category.php?categoryId=<?= $category['id'] ?>"><?= $category['name'] ?></a>
                                </li>
                            <?php endforeach;
                        else:
                            echo "<p class='alert alert-danger'>دسته بندی وجود ندارد</p>";
                        endif ?>
                    </ul>
                </div>
            </div>
            <?php
            if(empty($userId)):
            ?>
            <div class="me-auto">
                <a href="./sign-in.php" class="btn btn-warning">عضویت</a>
                <a href="./login.php" class="btn btn-success">ورود</a>
            </div>
            <?php
            else:
            ?>
            <div class="navbar-nav me-auto">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $user['name'] ?>
                    </a>
                    <ul class="dropdown-menu text-end">
                        <li><a class="dropdown-item"
                                href=<?= $path ?>>پنل کاربری</a>
                            </li>
                        <li><a class="dropdown-item text-danger"
                                href="./logout.php">خروج</a>
                        </li>
                    </ul>
                </div>
            </div>
            <?php
            endif
            ?>
        </div>
    </div>
</nav>