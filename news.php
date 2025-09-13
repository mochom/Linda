<?php
include "./database/db.php";

$newsCard = $db->query("SELECT * FROM news WHERE status = 'confirmation' ORDER BY id DESC");

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-3">
    <div class="row gx-5">
        <div class="col-4">
            <form action="./news-search.php">
                <label class="form-label" for="title">جستجوی عنوان</label>
                <input class="form-control my-2" type="text" id="title" name="title">
                <button class="btn btn-primary" name="setTitle">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./news-search.php">
                <label class="form-label" for="writer">جستجوی نویسنده</label>
                <input class="form-control my-2" type="text" id="writer" name="name">
                <button class="btn btn-primary" name="setName">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./news-search.php">
                <label class="form-label" for="date">جستجوی تاریخ</label>
                <input class="form-control my-2" type="text" id="date" name="date">
                <button class="btn btn-primary" name="setDate">جستجو</button>
            </form>
        </div>
    </div>
</div>

<div class="container mt-3">
    <div class="row gy-3">
        <p class="fs-5 fw-bolder">اخبار</p>
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

<?php include "./include/footer.php" ?>