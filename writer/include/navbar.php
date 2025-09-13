<?php

$server = $_SERVER['REQUEST_URI'];
$style = "";

if (isset($needle) && str_contains($server, $needle)) {
    $style = 'fw-bolder disabled';
}

$user = $db->query("SELECT * FROM users WHERE id=$sessionId")->fetch();

?>

<nav class="navbar navbar-expand-lg bg-primary">
    <div class="container">
        <a class="navbar-brand text-white" href="../index.php">
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
                <a class="nav-link <?= ($needle == 'home') ? $style : 'text-white'; ?>"
                    aria-current="page" href="./writer-panel-home.php">خانه</a>
            </div>
            <div class="navbar-nav me-3">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        خبرها
                    </a>
                    <ul class="dropdown-menu text-end">
                        <li>
                            <a class="dropdown-item <?= ($needle == 'pending') ? $style : ''; ?>"
                                href="./writer-pending.php">در انتظار
                                ویرایش</a>
                        </li>
                        <li>
                            <a class="dropdown-item <?= ($needle == 'news') ? $style : ''; ?>"
                                href="./writer-news.php">لیست
                                خبرها</a>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="navbar-nav me-3">
                <a class="nav-link <?= ($needle == 'unblock') ? $style : 'text-white'; ?>"
                    aria-current="page" href="./writer-unblock-request.php">درخواست
                    رفع مسدودیت</a>
            </div>
            <div class="navbar-nav me-3">
                <a class="nav-link  <?= ($needle == 'comment') ? $style : 'text-white'; ?>"
                    aria-current="page" href="./writer-comment.php">نظرات</a>
            </div>
            <div class="navbar-nav me-auto">
                <div class="dropdown">
                    <a class="nav-link dropdown-toggle text-white" href="#" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        <?= $user['name'] ?>
                    </a>
                    <ul class="dropdown-menu text-end">
                        <li><a class="dropdown-item <?= ($needle == 'information') ? $style : ''; ?>"
                                href="./writer-information.php">مشخصات فردی</a></li>
                        <li><a class="dropdown-item text-danger"
                                href="../logout.php">خروج</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</nav>