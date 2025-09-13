<?php

include "./include/session.php";

if (isset($_GET['newsId'])) {
    $newsId = $_GET['newsId'];
    $news = $db->query("SELECT * FROM news WHERE id=$newsId")->fetch();
    if ($news['id'] != $sessionId) {
        header("location:./writer-news.php");
        exit();
    }
    $categoryId = $news['category_id'];
    $category = $db->query("SELECT * FROM categories WHERE id=$categoryId")->fetch();
} else {
    header("location:./writer-news.php");
    exit();
}

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
    <div class="text-center">
        <img src="../images/<?= $news['image'] ?>" alt="..." width="600px" height="300px">
    </div>
    <h2><?= $news['title'] ?></h2>
    <?php
    $status = "";
    $style = "";
    switch ($news['status']) {
        case 'confirmation':
            $status = "تایید شده";
            $style = 'text-bg-success';
            break;
        case 'reject':
            $status = "رد شده";
            $style = 'text-bg-danger';
            break;
        case 'pending':
            $status = "در انتظار تایید";
            $style = 'text-bg-secondary';
            break;
        default:
            $status = "نامعتبر";
            $style = 'text-bg-warning';
            break;
    }
    if ($news['request'] == 'yes'): ?>
        <span class="badge text-bg-secondary ms-2">نیاز به
            ویرایش
        </span>
    <?php endif ?>
    <span class="badge <?= $style ?>"><?= $status ?></span>
    <p class="text-success mt-2"><?= $category['name'] ?></p>
    <p><?= $news['text'] ?></p>
    <p><?= $news['date'] ?></p>
    <?php if ($user['status'] != 'inactive'): ?>
        <a href="./writer-edit-news.php?newsId=<?= $news['id'] ?>"
            class="btn btn-success">ویرایش</a>
        <a href="./writer-news.php?deleteId=<?= $news['id'] ?>"
            class="btn btn-danger mx-3">حذف</a>
    <?php endif ?>
    <a href="./writer-news.php" class="btn btn-outline-secondary">بازگشت</a>
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
                if (!empty($sessionId)) {
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