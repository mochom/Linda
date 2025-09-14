<?php
include "./database/db.php";

if (isset($_GET['setTitle'])) {
    $categoryId = $_GET['setTitle'];
    $title = $_GET['title'];
    $newsCard = $db->prepare("SELECT * FROM news WHERE title LIKE :title AND status = :status AND category_id = :category_id ORDER BY id DESC");
    $newsCard->execute(["title" => "%$title%", "status" => "confirmation", "category_id" => $categoryId]);
} elseif (isset($_GET['setName'])) {
    $categoryId = $_GET['setName'];
    $name = $_GET['name'];
    $userId = $db->query("SELECT * FROM users WHERE name LIKE '%$name%' ORDER BY id DESC");
    $newsCard = $db->query("SELECT * FROM news WHERE status = 'confirmation' AND category_id = $categoryId ORDER BY id DESC");
} elseif (isset($_GET['setDate'])) {
    $categoryId = $_GET['setDate'];
    $date = $_GET['date'];
    $newsCard = $db->prepare("SELECT * FROM news WHERE date = :date AND status = :status AND category_id = :category_id ORDER BY id DESC");
    $newsCard->execute(["date" => $date, "status" => "confirmation", "category_id" => $categoryId]);
}



?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-3">
    <div class="row gx-5">
        <div class="col-4">
            <form action="./category-search.php" method="get">
                <label class="form-label" for="title">جستجوی عنوان</label>
                <input class="form-control my-2" type="text" id="title" name="title">
                <button class="btn btn-primary" name="setTitle"
                    value="<?= $categoryId ?>">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./category-search.php" method="get">
                <label class="form-label" for="writer">جستجوی نویسنده</label>
                <input class="form-control my-2" type="text" id="writer" name="name">
                <button class="btn btn-primary" name="setName"
                    value="<?= $categoryId ?>">جستجو</button>
            </form>
        </div>
        <div class="col-4">
            <form action="./category-search.php" method="get">
                <label class="form-label" for="date">جستجوی تاریخ</label>
                <input class="form-control my-2" type="text" id="date" name="date">
                <button class="btn btn-primary" name="setDate"
                    value="<?= $categoryId ?>">جستجو</button>
            </form>
        </div>
    </div>
</div>

<?php if (empty($userId)): ?>

    <div class="container mt-3">
        <div class="row gy-3">
            <p class="fs-5 fw-bolder">اخبار دسته بندی</p>
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
                echo "<p class='alert alert-danger'>خبری یافت نشد.</p>";
            endif
            ?>
        </div>
    </div>

<?php else: ?>

    <div class="container mt-3">
        <div class="row gy-3">
            <p class="fs-5 fw-bolder">اخبار دسته بندی</p>
            <?php $foundIt = 0;
            if ($userId->rowCount() > 0):
                foreach ($userId as $user):
                    foreach ($newsCard as $new):
                        if ($user['id'] == $new['writer_id']):
                            $foundIt = 1;
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
                            <?php
                        endif;
                        $newsCard = $db->query("SELECT * FROM news WHERE status = 'confirmation' AND category_id = $categoryId ORDER BY id DESC");
                    endforeach;
                endforeach;
                if ($foundIt == 0):
                    echo "<p class='alert alert-danger'>خبری یافت نشد.</p>";
                endif;
            else:
                echo "<p class='alert alert-danger'>خبری یافت نشد.</p>";
            endif;
            ?>
        </div>
    </div>

<?php endif ?>

<?php include "./include/footer.php" ?>