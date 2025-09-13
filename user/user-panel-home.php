<?php

include "./include/session.php";

$needle = "home";
$user = $db->query("SELECT * FROM users WHERE id=$sessionId")->fetch();
$email = $user['email'];
$comments = $db->query("SELECT * FROM comments WHERE email='$email' ORDER BY id DESC LIMIT 5");

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<?php
if ($user['status'] == 'inactive'):
    ?>
    <div class="container my-3">
        <p class="alert alert-danger" style="width: 350px;">شما مسدود شده اید.
            <br><small><small>دسترسی شما محدود شده است.</small></small>
        </p>
    </div>
    <?php
endif;
?>

<div class="container mt-5">
    <div class="row">
        <div class="col">
            <h5>نظرات شما</h5>
        </div>
        <div class="col text-start">
            <a href="./user-comment.php" class="btn btn-outline-primary">مشاهده همه
                <i class="bi bi-arrow-left"></i>
            </a>
        </div>
    </div>
    <div class="list-group mt-2">
        <?php
        if ($comments->rowCount() > 0):
            foreach ($comments as $comment):
                ?>
                <div class="list-group-item list-group-item-action">
                    <div class="row">
                        <div class="col-6">
                            <p class="my-1"><?= $comment['text'] ?></p>
                            <small><?= $comment['date'] ?></small>
                        </div>
                        <div class="col-6 text-start">
                            <a href="./user-comment-view.php?commentId=<?= $comment['id'] ?>"
                                class="btn btn-secondary">مشاهده نظر</a>
                        </div>
                    </div>
                </div>
                <?php
            endforeach;
        else:
            echo "<p class='alert alert-secondary'>نظری ندارید.</p>";
        endif
        ?>
    </div>
</div>

<?php include "./include/footer.php" ?>