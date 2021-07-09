<?php
session_start();
$_SESSION['Lang'] = isset($_GET['lang'])?$_GET['lang']:"en";
if ($_SESSION['Lang']=='en'){
    include "lang/en.php";
}elseif ($_SESSION['Lang']=='ar'){
    include "lang/ar.php";
}else{
    include "lang/en.php";
}
require "admin/confied.php";
?>
<?php
if ($_SERVER['REQUEST_METHOD']=='POST'){
    $adminNsername = $_POST['adminusername'];
    $adminPassword = sha1($_POST['adminpassword']);
    $stmt = $con->prepare("SELECT * FROM user WHERE username =? AND password=? AND role!=0");
    $stmt->execute(array($adminNsername , $adminPassword));
    $row = $stmt->fetch();
    $count = $stmt->rowCount();
    $countdb= 1;
    if($count == $countdb){
        $_SESSION['ID'] = $row['id'];
        $_SESSION['USERNAME'] = $adminNsername;
        $_SESSION['FULLNAME'] = $row['fullname'];
        $_SESSION['ROLE'] = $row['role'];
        $_SESSION['EMAIL'] = $row['email'];
        header("location:dashboard.php");
    }else{
        echo  "<div class='alert alert-danger'>Daata Is Error</div>";
    }
}
?>
<?php include "admin/header.php";?>
<?php include "admin/footer.php";?>
<div class="container">
    <h1 class="text-center m-5"><?= $lang['admin_user'] ?></h1>
<div class="user m-5">
    <a href="?lang=en">English</a>
    <a href="?lang=ar">اللغه العربية</a>
    <form method="POST" action="<?php $_SERVER['PHP_SELF'] ?>">
        <div class="mb-3">
            <label  class="form-label"><?= $lang['username'] ?></label>
            <input type="text" class="form-control" name="adminusername">
            <div  class="form-text">We'll never share your User Name with anyone else.</div>
        </div>
        <div class="mb-3">
            <label  class="form-label"><?= $lang['password'] ?></label>
            <input type="password" class="form-control" name="adminpassword">
            <div  class="form-text">We'll never share your Password with anyone else.</div>
        </div>
        <button type="submit" class="btn btn-primary"><?= $lang['login'] ?></button>
    </form>
</div>
</div>
