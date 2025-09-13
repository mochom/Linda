<?php
include "./database/db.php";

if (isset($_GET['newsId'])) {
    $newsId = $_GET['newsId'];
} else {
    header("location:./index.php");
    exit();
}

$news = $db->query("SELECT * FROM news WHERE id=$newsId")->fetch();

if (empty($news) || $news['status'] != 'confirmation') {
    header("location:./news.php");
    exit();
}

$writerId = $news['writer_id'];
$categoryId = $news['category_id'];
$categoryName = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
$writerName = $db->query("SELECT * FROM users WHERE id=$writerId")->fetch();

$comments = $db->query("SELECT * FROM comments WHERE news_id=$newsId AND status='confirmation' ORDER BY id DESC");


$invalidName = "";
$invalidEmail = "";
$invalidText = "";
$successMessage = "";
if (isset($_POST['set'])) {
    if (empty($_POST['name'])) {
        $invalidName = "نام خود را وارد کنید";
    } elseif (empty($_POST['email'])) {
        $invalidEmail = "ایمبل خود را وارد کنید";
    } elseif (empty($_POST['text'])) {
        $invalidText = "متن را وارد کنید";
    } else {
        $name = $_POST['name'];
        $email = $_POST['email'];
        $text = $_POST['text'];
        $date = date("Y/m/d");
        $status = "pending";
        $insertComment = $db->prepare("INSERT INTO `comments`(`name`, `email`, `text`, `date`, `status`, `news_id`) VALUES (:name,:email,:text,:date,:status,:newsId)");
        $insertComment->execute(["name" => $name, "email" => $email, "text" => $text, "date" => $date, "status" => $status, "newsId" => $newsId]);
        $successMessage = "نظر شما با موفقیت ثبت شد";
    }
}


?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container my-4">
    <h2><?= $news['title'] ?></h2>
    <div class="text-center my-3">
        <img src="./images/<?= $news['image'] ?>" alt="..." width="600px" height="300px">
    </div>
    <p class="text-success"><?= $categoryName['name'] ?></p>
    <p><?= $news['text'] ?></p>
    <p class="text-primary"><?= $writerName['name'] ?></p>
    <p><?= $news['date'] ?></p>
    <a href="./news.php" class="btn btn-outline-secondary">بازگشت</a>
</div>

<div class="container">
    <div class="row my-3 justify-content-between">
        <div class="col-4">
            <h5>نظرات</h5>
            <?php if ($comments->rowCount() > 0):
                foreach ($comments as $comment): ?>
                    <div class="border border-2 border-primary rounded-3 p-2 my-3">
                        <div class="d-flex justify-content-between">
                            <p><?= $comment['name'] ?></p>
                            <p><?= $comment['date'] ?></p>
                        </div>
                        <p><?= $comment['text'] ?></p>
                    </div>
                    <?php
                endforeach;
            else:
                echo "<p class='alert alert-secondary'>نظری وجود ندارد</p>";
            endif ?>
        </div>
        <div class="col-6">
            <form method="post">
                <h5>ارسال نظرات</h5>
                <?php
                $inputName = '';
                $inputEmail = '';
                $readOnly = '';
                if (!empty($userId)) {
                    $inputName = $user['name'];
                    $inputEmail = $user['email'];
                    $readOnly = 'readonly';
                }
                ?>
                <label for="inputName" class="form-label">نام</label>
                <input type="text" class="form-control mb-3" id="inputName" name="name"
                    value="<?= $inputName ?>" <?= $readOnly ?>>
                <p class="text-danger"><?= $invalidName ?></p>
                <label for="inputEmail" class="form-label">ایمیل</label>
                <input type="email" class="form-control mb-3" id="inputEmail" name="email"
                    value="<?= $inputEmail ?>" <?= $readOnly ?>>
                <p class="text-danger"><?= $invalidEmail ?></p>
                <label for="inputText" class="form-label">متن نظر</label>
                <textarea class="form-control mb-3" id="inputText" rows="5"
                    name="text"></textarea>
                <p class="text-danger"><?= $invalidText ?></p>
                <button class="btn btn-primary mb-3" name="set">ارسال</button>
                <p class="text-success"><?= $successMessage ?></p>
            </form>
        </div>
    </div>
</div>

<?php include "./include/footer.php" ?>