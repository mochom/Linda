<?php

include "./include/session.php";

$messageName = "";
$messageRepeat = "";

if (isset($_POST["create"])) {
    if (empty($_POST["name"])) {
        $messageName = "وارد کردن نام دسته بندی الزامی است";
    } else {
        $name = $_POST["name"];
        $categories = $db->query("SELECT * FROM categories WHERE name='$name'");
        if ($categories->rowCount() > 0) {
            $messageRepeat = "این دسته بندی تکراری است";
        } else {
            $createCategory = $db->prepare("INSERT INTO categories (name) VALUES (:name)");
            $createCategory->execute(['name' => $name]);
            header("location:./admin-category.php");
            exit();
        }

    }
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container fw-bold align-items-center mt-3">
    <form class="row" method="post" enctype="multipart/form-data">
        <div class="col-8">
            <label for="inputName" class="form-label">نام دسته بندی</label>
            <input type="text" class="form-control" id="inputName" name="name">
            <p class="text-danger">
                <?= $messageName ?>
            </p>
        </div>
        <div class="col-12 mt-2">
            <button class="btn btn-primary ms-3" name="create">ایجاد خبر</button>
            <a href="./admin-category.php" class="btn btn-outline-secondary">بازگشت</a>
        </div>
        <p class="text-warning">
            <?= $messageRepeat ?>
        </p>
    </form>
</div>

<?php include "./include/footer.php" ?>