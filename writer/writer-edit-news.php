<?php

include "./include/session.php";

if (isset($_GET["newsId"])) {
    $newsId = $_GET["newsId"];
    $news = $db->query("SELECT * FROM news WHERE id = $newsId")->fetch();
    $categories = $db->query("SELECT * FROM categories");
} else {
    header("location:./writer-news.php");
    exit();
}

$messageTitle = "";
$messageText = "";
$messageSuccess = "";

if (isset($_POST['edit'])) {
    if (empty($_POST['title'])) {
        $messageTitle = "وارد کردن عنوان الزامی است";
    } elseif (empty($_POST['text'])) {
        $messageText = "وارد کردن متن الزامی است";
    } else {
        $title = $_POST["title"];
        $category = $_POST["category"];
        $text = $_POST["text"];
        $date = date("Y/m/d");
        $status = "pending";
        $request = "no";
        if ($_FILES['image']['size'] == 0) {
            $editNews = $db->prepare("UPDATE news SET title=:title,text=:text,date=:date,status=:status,request=:request,category_id=:category_id WHERE id=$newsId");
            $editNews->execute(['title' => $title, 'text' => $text, 'date' => $date, 'status' => $status, 'request' => $request, 'category_id' => $category]);
        } else {
            $image = time() . "-" . $_FILES["image"]["name"];
            $editNews = $db->prepare("UPDATE news SET title=:title,text=:text,date=:date,status=:status,request=:request,image=:image,category_id=:category_id WHERE id=$newsId");
            $editNews->execute(['title' => $title, 'text' => $text, 'date' => $date, 'status' => $status, 'request' => $request, 'image' => $image, 'category_id' => $category]);
            move_uploaded_file($_FILES["image"]["tmp_name"], "../images/$image");
        }

        $messageSuccess = "خبر با موفقیت ویرایش شد";
        header("location:./writer-news.php");
        exit();
    }
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container fw-bold align-items-center mt-3">
    <div class="text-center mb-3">
        <img src="../images/<?= $news['image'] ?>" alt="..." width="600px" height="300px">
    </div>
    <form class="row" method="post" enctype="multipart/form-data">
        <div class="col-8">
            <label for="inputTitle" class="form-label">موضوع</label>
            <input type="text" class="form-control" id="inputTitle" name="title"
                value="<?= $news['title'] ?>">
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
                        <option <?= ($category['id'] == $news['category_id']) ? 'selected' : '' ?>
                            value=<?= $category['id'] ?>><?= $category['name'] ?></option>
                    <?php endforeach;
                else: ?>
                    <option value="999">نامعتبر</option>
                <?php endif ?>
            </select>
        </div>
        <div class="col-12 my-2">
            <label for="inputText" class="form-label">متن</label>
            <textarea name="text" class="form-control" id="inputText"
                style="height: 200px;"><?= $news['text'] ?></textarea>
            <p class="text-danger">
                <?= $messageText ?>
            </p>
        </div>
        <div class="col-12 mb-2">
            <label for="formFile" class="form-label">بارگذاری تصویر</label>
            <input class="form-control" type="file" id="formFile" name="image">
            <p class="text-danger">
            </p>
        </div>
        <div class="col-12 mt-2">
            <button class="btn btn-primary ms-3" name="edit">ویرایش خبر</button>
            <a href="./writer-news.php" class="btn btn-outline-secondary">بازگشت</a>
        </div>
    </form>
</div>

<?php include "./include/footer.php" ?>