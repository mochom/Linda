<?php

include "./database/db.php";

$newsSlider = $db->query("SELECT * FROM news WHERE status='confirmation' ORDER BY id DESC LIMIT 3");
$newsCard = $db->query("SELECT * FROM news WHERE status='confirmation' ORDER BY id DESC LIMIT 4");
$newsCard1 = $db->query("SELECT * FROM news WHERE category_id=1 AND status='confirmation' ORDER BY id DESC LIMIT 4");
$newsCard2 = $db->query("SELECT * FROM news WHERE category_id=2 AND status='confirmation' ORDER BY id DESC LIMIT 4");
$newsCard3 = $db->query("SELECT * FROM news WHERE category_id=3 AND status='confirmation' ORDER BY id DESC LIMIT 4");
$newsCard4 = $db->query("SELECT * FROM news WHERE category_id=4 AND status='confirmation' ORDER BY id DESC LIMIT 4");



?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container my-4">
    <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide-to="2" aria-current="true" aria-label="Slide 1"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide-to="1" aria-label="Slide 2"></button>
            <button type="button" data-bs-target="#carouselExampleCaptions"
                data-bs-slide-to="0" class="active" aria-label="Slide 3"></button>
        </div>
        <div class="carousel-inner">
            <?php if ($newsSlider->rowCount() > 0):
                foreach ($newsSlider as $new):
                    ?>
                    <a href="./news-page.php?newsId=<?= $new['id'] ?>">
                        <div class="carousel-item active">
                            <img src="./images/<?= $new['image'] ?>" class="d-block w-100"
                                alt="..." height="500px">
                            <div class="carousel-caption d-none d-md-block">
                                <h5><?= $new['title'] ?></h5>
                                <p><?= substr($new['text'], 0, 150), "..." ?></p>
                            </div>
                        </div>
                    </a>
                <?php endforeach;
            else:
                echo "<p class='alert alert-danger'>خبری وجود ندارد</p>";
            endif
            ?>
        </div>
        <button class="carousel-control-prev" type="button"
            data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button"
            data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="row align-items-center mb-2">
            <div class="col fw-bolder fs-5">
                جدید ترین اخبار
            </div>
            <div class="col text-start">
                <a href="./news.php" class="btn btn-outline-primary">مشاهده همه
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
        <?php if ($newsCard->rowCount() > 0):
            foreach ($newsCard as $new):
                $categoryId = $new['category_id'];
                $categoryName = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                ?>
                <div class="col-3">
                    <a href="./news-page.php?newsId=<?= $new['id'] ?>"
                        class="text-decoration-none">
                        <div class="card" style="height: 400px;">
                            <img src="./images/<?= $new['image'] ?>" class="card-img-top"
                                width="259px" height="114px" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?= $new['title'] ?></h5>
                                <p class="card-text"><?= substr($new['text'], 0, 200), "..." ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <span class="me-4 ms-5"><?= $new['date'] ?></span>
                                <span class="text-primary"><?= $categoryName['name'] ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-danger'>خبری وجود ندارد</p>";
        endif
        ?>
    </div>
</div>

<div class="container my-4">
    <div class="row">
        <div class="row align-items-center mb-2">
            <div class="col fw-bolder fs-5">
                اخبار اقتصادی جدید
            </div>
            <div class="col text-start">
                <a href="./category.php?categoryId=1"
                    class="btn btn-outline-primary">مشاهده همه
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
        <?php if ($newsCard1->rowCount() > 0):
            foreach ($newsCard1 as $new):
                $categoryId = $new['category_id'];
                $categoryName = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                ?>
                <div class="col-3">
                    <a href="./news-page.php?newsId=<?= $new['id'] ?>"
                        class="text-decoration-none">
                        <div class="card" style="height: 400px;">
                            <img src="./images/<?= $new['image'] ?>" class="card-img-top"
                                width="259px" height="114px" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?= $new['title'] ?></h5>
                                <p class="card-text"><?= substr($new['text'], 0, 200), "..." ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <span class="ms-4 ms-5"><?= $new['date'] ?></span>
                                <span class="text-primary"><?= $categoryName['name'] ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-danger'>خبری وجود ندارد</p>";
        endif
        ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="row align-items-center mb-2">
            <div class="col fw-bolder fs-5">
                اخبار سیاسی جدید
            </div>
            <div class="col text-start">
                <a href="./category.php?categoryId=2"
                    class="btn btn-outline-primary">مشاهده همه
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
        <?php if ($newsCard2->rowCount() > 0):
            foreach ($newsCard2 as $new):
                $categoryId = $new['category_id'];
                $categoryName = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                ?>
                <div class="col-3">
                    <a href="./news-page.php?newsId=<?= $new['id'] ?>"
                        class="text-decoration-none">
                        <div class="card" style="height: 400px;">
                            <img src="./images/<?= $new['image'] ?>" class="card-img-top"
                                width="259px" height="114px" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?= $new['title'] ?></h5>
                                <p class="card-text"><?= substr($new['text'], 0, 200), "..." ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <span class="ms-4 ms-5"><?= $new['date'] ?></span>
                                <span class="text-primary"><?= $categoryName['name'] ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-danger'>خبری وجود ندارد</p>";
        endif
        ?>
    </div>
</div>

<div class="container my-4">
    <div class="row">
        <div class="row align-items-center mb-2">
            <div class="col fw-bolder fs-5">
                اخبار اجتماعی جدید
            </div>
            <div class="col text-start">
                <a href="./category.php?categoryId=3"
                    class="btn btn-outline-primary">مشاهده همه
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
        <?php if ($newsCard3->rowCount() > 0):
            foreach ($newsCard3 as $new):
                $categoryId = $new['category_id'];
                $categoryName = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                ?>
                <div class="col-3">
                    <a href="./news-page.php?newsId=<?= $new['id'] ?>"
                        class="text-decoration-none">
                        <div class="card" style="height: 400px;">
                            <img src="./images/<?= $new['image'] ?>" class="card-img-top"
                                width="259px" height="114px" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?= $new['title'] ?></h5>
                                <p class="card-text"><?= substr($new['text'], 0, 200), "..." ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <span class="ms-4 ms-5"><?= $new['date'] ?></span>
                                <span class="text-primary"><?= $categoryName['name'] ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-danger'>خبری وجود ندارد</p>";
        endif
        ?>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="row align-items-center mb-2">
            <div class="col fw-bolder fs-5">
                اخبار ورزشی جدید
            </div>
            <div class="col text-start">
                <a href="./category.php?categoryId=4"
                    class="btn btn-outline-primary">مشاهده همه
                    <i class="bi bi-arrow-left"></i>
                </a>
            </div>
        </div>
        <?php if ($newsCard4->rowCount() > 0):
            foreach ($newsCard4 as $new):
                $categoryId = $new['category_id'];
                $categoryName = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
                ?>
                <div class="col-3">
                    <a href="./news-page.php?newsId=<?= $new['id'] ?>"
                        class="text-decoration-none">
                        <div class="card" style="height: 400px;">
                            <img src="./images/<?= $new['image'] ?>" class="card-img-top"
                                width="259px" height="114px" alt="...">
                            <div class="card-body">
                                <h5 class="card-title"><?= $new['title'] ?></h5>
                                <p class="card-text"><?= substr($new['text'], 0, 200), "..." ?>
                                </p>
                            </div>
                            <div class="card-footer">
                                <span class="ms-4 ms-5"><?= $new['date'] ?></span>
                                <span class="text-primary"><?= $categoryName['name'] ?></span>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-danger'>خبری وجود ندارد</p>";
        endif
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>