<?php

include "./include/session.php";

$categories = $db->query("SELECT * FROM categories");

$messageTitle = "";
$messageImage = "";
$messageText = "";

if (isset($_POST["create"])) {
    if (empty($_POST["title"])) {
        $messageTitle = "وارد کردن عنوان خبر الزامی است";
    } elseif (empty($_FILES["image"]['name'])) {
        $messageImage = "بارگذاری تصویر الزامی است";
    } elseif (empty($_POST["text"])) {
        $messageText = "وارد کردن متن الزامی است";
    } else {
        $title = $_POST["title"];
        $category = $_POST["category"];
        $image = time() . "-" . $_FILES["image"]["name"];
        $text = $_POST["text"];
        $date = date("Y/m/d");
        $status = "pending";
        $request = "no";
        $writerId = $sessionId;
        $createNews = $db->prepare("INSERT INTO news (title, text, date, status,request, image, writer_id, category_id) VALUES (:title,:text,:date,:status,:request,:image,:writer_id,:category_id)");
        $createNews->execute(['title' => $title, 'text' => $text, 'date' => $date, 'status' => $status, 'request' => $request, 'image' => $image, 'writer_id' => $writerId, 'category_id' => $category]);

        move_uploaded_file($_FILES["image"]["tmp_name"], "../images/$image");
        header("location:./writer-news.php");
        exit();
    }
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container fw-bold align-items-center mt-3">
    <form class="row" method="post" enctype="multipart/form-data">
        <div class="col-8">
            <label for="inputTitle" class="form-label">موضوع</label>
            <input type="text" class="form-control" id="inputTitle" name="title">
            <p class="text-danger">
                <?= $messageTitle ?>
            </p>
        </div>
        <div class="col-4">
            <label for="inputCategory" class="form-label">دسته بندی</label>
            <select id="inputCategory" class="form-select" name="category">
                <?php if ($categories->rowCount() > 0):
                    foreach ($categories as $category):
                        ?>
                        <option value=<?= $category['id'] ?>><?= $category['name'] ?></option>
                    <?php endforeach;
                else: ?>
                    <option value="999">نامعتبر</option>
                <?php endif ?>
            </select>
        </div>
        <div class="col-12 my-2">
            <label for="inputText" class="form-label">متن</label>
            <textarea name="text" class="form-control" id="inputText"
                style="height: 200px;"></textarea>
            <p class="text-danger">
                <?= $messageText ?>
            </p>
        </div>
        <div class="col-12 mb-2">
            <label for="formFile" class="form-label">بارگذاری تصویر</label>
            <input class="form-control" type="file" id="formFile" name="image">
            <p class="text-danger">
                <?= $messageImage ?>
            </p>
        </div>
        <div class="col-12 mt-2">
            <button class="btn btn-primary ms-3" name="create">ایجاد خبر</button>
            <a href="./writer-news.php" class="btn btn-outline-secondary">بازگشت</a>
        </div>
    </form>
</div>

<?php include "./include/footer.php" ?>