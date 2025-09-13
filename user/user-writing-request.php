<?php

include "./include/session.php";

$needle = "writing";

$repeatMessage = "";
$successMessage = "";
$blockMessage = "";

if (isset($_GET['accept'])) {
    $request = $db->query("SELECT * FROM users WHERE id=$sessionId")->fetch();
    if ($request['status'] == 'inactive') {
        $blockMessage = "شما مسدود شده اید";
    } elseif ($request['writing_request'] == 'yes') {
        $repeatMessage = "شما از قبل درخواست داده اید";
    } else {
        $db->query("UPDATE users SET writing_request='yes' WHERE id=$sessionId")->fetch();
        $successMessage = "درخواست شما ثبت شد";
    }
}

?>

<?php include "./include/header.php" ?>
<?php include "./include/navbar.php" ?>

<div class="container align-items-center mt-3">
    <form action="./user-writing-request.php" method="get">
        <h5>قوانین</h5>
        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
            طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان
            که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با
            هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته
            حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها
            شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ
            پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام </p>
        <p>لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ، و با استفاده از
            طراحان گرافیک است، چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان
            که لازم است، و برای شرایط فعلی تکنولوژی مورد نیاز، و کاربردهای متنوع با
            هدف بهبود ابزارهای کاربردی می باشد، کتابهای زیادی در شصت و سه درصد گذشته
            حال و آینده، شناخت فراوان جامعه و متخصصان را می طلبد، تا با نرم افزارها
            شناخت بیشتری را برای طراحان رایانه ای علی الخصوص طراحان خلاقی، و فرهنگ
            پیشرو در زبان فارسی ایجاد کرد، در این صورت می توان امید داشت که تمام </p>
        <small>درصورت تایید قوانین ارسال درخواست را کلیک کنید.</small>
        <div class="mt-2 text-center">
            <button name="accept" class="btn btn-success">ارسال درخواست</button>
            <p class="text-success"><?= $successMessage ?></p>
            <p class="text-warning"><?= $repeatMessage ?></p>
            <p class="text-danger"><?= $blockMessage ?></p>
        </div>
    </form>
</div>

<?php include "./include/footer.php" ?>