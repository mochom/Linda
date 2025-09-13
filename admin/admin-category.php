<?php

include "./include/session.php";

$needle = "category";

if (isset($_GET['categoryId'])) {
    $categoryId = $_GET['categoryId'];
    $db->query("DELETE FROM categories WHERE id=$categoryId");
    header("location:./admin-category.php");
    exit();
}

$categories = $db->query("SELECT * FROM categories");

$counter1 = $db->query("SELECT count(id) FROM news WHERE category_id = 1")->fetch();
$counter2 = $db->query("SELECT count(id) FROM news WHERE category_id = 2")->fetch();
$counter3 = $db->query("SELECT count(id) FROM news WHERE category_id = 3")->fetch();
$counter4 = $db->query("SELECT count(id) FROM news WHERE category_id = 4")->fetch();

?>


<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h5>دسته بندی ها</h5>
        </div>
        <div class="col text-start">
            <a href="./admin-create-category.php" class="btn btn-outline-primary"><i
                    class="bi bi-plus-lg text-primary"></i>
                ایجاد دسته بندی جدید
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php if ($categories->rowCount() > 0):
            foreach ($categories as $category):
                switch ($category['id']) {
                    case 1:
                        $counter = $counter1[0];
                        break;
                    case 2:
                        $counter = $counter2[0];
                        break;
                    case 3:
                        $counter = $counter3[0];
                        break;
                    case 4:
                        $counter = $counter4[0];
                        break;
                    default:
                        $counter = "نامعتبر";
                        break;
                }
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="row align-items-center">
                        <div class="col-6">
                            <h5 class="mb-1 ms-auto"><?= $category['name'] ?></h5>
                            <p class="my-1">تعداد خبرها: <?= $counter ?>
                            </p>
                        </div>
                        <div class="col-6 text-start">
                            <a href="./admin-category.php?categoryId=<?= $category['id'] ?>"
                                class="btn btn-danger">حذف</a>
                        </div>
                    </div>
                </div>
            <?php endforeach;
        else:
            echo "<p class='alert alert-secondary'>دسته بندی وجود ندارد</p>";
        endif;
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>