<?php
session_start();
if ($_SESSION['Lang']=='en'){
    include "lang/en.php";
}elseif ($_SESSION['Lang']=='ar'){
    include "lang/ar.php";
}else{
    include "lang/en.php";
}
require "admin/confied.php";
?>
<h5 class="text-center p-3"><?= $lang['dashboard'] ?></h5>
<div class="container">
    <div class="row">
        <?php
        $stmt = $con->prepare("SELECT count(id) FROM user WHERE role=0");
        $stmt->execute();
        $user = $stmt->fetchColumn();
        ?>
        <div class="col-3">
            <a href="members.php"> <i class="fa fa-users fa-2x"></i></a>
        </div>
        <div class="col-3">
            <h5><?= $user ?></h5>
        </div>
        <div class="col-3">
            <h5>tast</h5>
        </div>
    </div>
</div>
<?php include "admin/header.php";?>
<?php include "admin/footer.php";?>
<?php include "admin/navbar.php"; ?>
