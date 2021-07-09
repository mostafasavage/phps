<?php
session_start();
require "admin/confied.php";
?>
<?php include "admin/header.php"?>
<?php include "admin/navbar.php"?>
<?php include "admin/footer.php"?>
<?php
$userid = $_SESSION['ID'];
$stmt = $con->prepare("SELECT * FROM user WHERE id =?");
$stmt->execute(array($userid));
$profilouser = $stmt->fetch();
?>
<div class="container">
    <h1 class="text-center">Show Page</h1>
    <div class="user m-5">
        <form method="POST" action="?action=update" enctype="multipart/form-data">
            <input type="hidden" class="form-control" value="<?= $profilouser['id'] ?>" name="userid">
            <div class="mb-3">
                <label  class="form-label">User Name</label>
                <input type="text" class="form-control" value="<?=$profilouser['username'] ?>" name="username">
                <div  class="form-text">We'll never share your User Name with anyone else.</div>
            </div>
            <div class="mb-3">
                <label  class="form-label">Email Address</label>
                <input type="email" class="form-control" value="<?= $profilouser['email'] ?>" name="email">
                <div  class="form-text">We'll never share your Email Address with anyone else.</div>
            </div>
            <div class="mb-3">
                <label  class="form-label">Full Name</label>
                <input type="text" class="form-control" name="fullname" value="<?= $profilouser['fullname'] ?>">
                <div  class="form-text">We'll never share your Full Name with anyone else.</div>
            </div>
            <div class="mb-3">
                <label  class="form-label">Photo</label>
                <input type="text" class="form-control" name="avater" value="<?= $profilouser['image'] ?>">
                <div  class="form-text">We'll never share your Image with anyone else.</div>
            </div>
            <a href="members.php" class="btn btn-dark">Back</a>
        </form>
    </div>
</div>