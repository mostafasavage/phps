<?php
session_start();
require "admin/confied.php";
?>
<?php include "admin/header.php"?>
<?php include "admin/navbar.php"?>
<?php include "admin/footer.php"?>
<?php
$action = "";
if(isset($_GET['action'])){
    $action = $_GET['action'];
}else{
    $action ="index";
}
?>
<?php if($action =='index'): ?>
<?php
    $stmt = $con->prepare("SELECT * FROM user WHERE role =0");
     $stmt->execute();
     $users = $stmt->fetchAll();
    ?>
    <table class="table">
        <thead>
        <tr>
            <th scope="col">ID</th>
            <th scope="col">username</th>
            <th scope="col">Email</th>
            <th scope="col">Full Name</th>
            <th scope="col">Photo</th>
            <th scope="col">Control</th>
        </tr>
        </thead>
        <tbody>
          <?php foreach ($users as $user) : ?>
            <tr>
                <td><?= $user['id'] ?></td>
               <td><?= $user['username'] ?></td>
                <td><?= $user['email'] ?></td>
                <td><?= $user['fullname'] ?></td>
                <td><img src="assest/image/<?= $user['image']?>" style="height: 10vh"</td>
                <td>
                    <a href="?action=show&selection=<?= $user['id'] ?>" class="btn btn-primary">Show</a>
                     <?php if ($_SESSION['ROLE'] ==1) : ?>
                         <a href="?action=edit&selection=<?= $user['id'] ?>" class="btn btn-info">Edit</a>
                         <a href="?action=delete&selection=<?= $user['id'] ?>" class="btn btn-danger">Delete</a>
                    <?php endif; ?>
                </td>
            </tr>

        </tbody>
        <?php endforeach; ?>
    </table>
    <a href="?action=create" class="btn btn-info">Add New</a>
<?php elseif ($action =='create'): ?>
    <div class="container">
        <h1 class="text-center">Insert Page</h1>
        <div class="user m-5">
            <form method="POST" action="?action=store" enctype="multipart/form-data">
                <div class="mb-3">
                    <label  class="form-label">User Name</label>
                    <input type="text" class="form-control" name="username">
                    <div  class="form-text">We'll never share your User Name with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Email Address</label>
                    <input type="email" class="form-control" name="email">
                    <div  class="form-text">We'll never share your Email Address with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Password</label>
                    <input type="password" class="form-control" name="password">
                    <div  class="form-text">We'll never share your Password with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="fullname">
                    <div  class="form-text">We'll never share your Full Name with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Photo</label>
                    <input type="file" class="form-control" name="avater">
                    <div  class="form-text">We'll never share your Image with anyone else.</div>
                </div>
                <button type="submit" class="btn btn-primary">Insert Data</button>
            </form>
        </div>
    </div>
<?php elseif ($action =='store') : ?>
<?php
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $avater = $_FILES['avater'];
        $avaterName = $_FILES['avater']['name'];
        $avaterType = $_FILES['avater']['type'];
        $avaterTemp = $_FILES['avater']['tmp_name'];
        $avaterSize = $_FILES['avater']['size'];
        $avaterError = $_FILES['avater']['error'];
        $avterExption = array('image/jpeg','image/jpg','image/png');
        if (in_array($avaterType , $avterExption)){
            $randname = rand(0 , 10000) ."_".$avaterName;
            $dstmtionImages = "assest/image//".$randname;
            move_uploaded_file($avaterTemp , $dstmtionImages);
        }
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = sha1($_POST['password']);
        $fullname = $_POST['fullname'];
        $fromError = array();
        if(strlen($username) < 4 || empty($username)){
            $fromError[] ='insert User Name';
        }
        if(empty($email)){
            $fromError[] ="insert Email Address";
        }
        if (empty($password)){
            $fromError[]="insert Password";
        }
        if (empty($fullname)){
            $fromError[] ='Insert Full Name';
        }
        if (empty($avater)){
            $fromError[] ='insert Image';
        }
        if (empty($fromError)){
            $stmt = $con->prepare("INSERT INTO user (username , email , password , fullname , image,  role) VALUES (?,?,?,?,?,0)");
            $stmt->execute(array($username , $email , $password ,$fullname , $randname));
            header("location:members.php?action=create");
        }else{
            foreach ($fromError as $error){
                echo '<div class="alert alert-danger">'.$error.'</div>';
            }
        }
    }
    ?>
<?php elseif ($action =='edit') : ?>
<?php
    $userid = isset($_GET['selection'])&&is_numeric($_GET['selection'])?intval($_GET['selection']):0;
    $stmt = $con->prepare("SELECT * FROM user WHERE id =?");
    $stmt->execute(array($userid));
    $users = $stmt->fetch();
    $usercount = $stmt->rowCount();
    ?>
<?php if ($usercount > 0) : ?>
    <div class="container">
        <h1 class="text-center">Insert Page</h1>
        <div class="user m-5">
            <form method="POST" action="?action=update" enctype="multipart/form-data">
                <input type="hidden" class="form-control" value="<?= $users['id'] ?>" name="userid">
                <div class="mb-3">
                    <label  class="form-label">User Name</label>
                    <input type="text" class="form-control" value="<?= $users['username'] ?>" name="username">
                    <div  class="form-text">We'll never share your User Name with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="<?= $users['email'] ?>" name="email">
                    <div  class="form-text">We'll never share your Email Address with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Password</label>
                    <input type="password" class="form-control" name="oldpassword">
                    <input type="hidden" class="form-control" value="<?= $users['password'] ?>" name="newpassword">
                    <div  class="form-text">We'll never share your Password with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $users['fullname'] ?>">
                    <div  class="form-text">We'll never share your Full Name with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Photo</label>
                    <input type="file" class="form-control" name="avater" value="<?= $users['image'] ?>">
                    <div  class="form-text">We'll never share your Image with anyone else.</div>
                </div>
                <button type="submit" class="btn btn-primary">Update Data</button>
            </form>
        </div>
    </div>
    <?php else:?>
    <?php header("location:members.php"); ?>
<?php endif;?>
<?php elseif ($action =='update') : ?>
<?php
    if($_SERVER['REQUEST_METHOD']=='POST'){
        $avater = $_FILES['avater'];
        $avaterName = $_FILES['avater']['name'];
        $avaterType = $_FILES['avater']['type'];
        $avaterTemp = $_FILES['avater']['tmp_name'];
        $avaterSize = $_FILES['avater']['size'];
        $avaterError = $_FILES['avater']['error'];
        $avterExption = array('image/jpeg','image/jpg','image/png');
        if (in_array($avaterType , $avterExption)){
            $randname = rand(0 , 10000) ."_".$avaterName;
            $dstmtionImages = "assest/image//".$randname;
            move_uploaded_file($avaterTemp , $dstmtionImages);
        }
        $avater = $_POST['avater'];
        $userid = $_POST['userid'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = empty($_POST['newpassword'])?$_POST['oldpassword']:sha1($_POST['newpassword']);
        $fullname = $_POST['fullname'];
        $stmt = $con->prepare("UPDATE user SET username =? , email =? , password =? , fullname=? , image =? WHERE id=?");
        $stmt->execute(array($username , $email , $password , $fullname ,  $randname , $userid));
        header("location:members.php");
    }
    ?>
<?php elseif ($action=='delete'): ?>
<?php
    $userid = isset($_GET['selection'])&&is_numeric($_GET['selection'])?intval($_GET['selection']):0;
    $stmt = $con->prepare("DELETE FROM user WHERE id=?");
    $stmt->execute(array($userid));
    header("location:members.php");
    ?>
<?php elseif ($action =='show') : ?>
<?php
    $userid = isset($_GET['selection'])&&is_numeric($_GET['selection'])?intval($_GET['selection']):0;
    $stmt = $con->prepare("SELECT * FROM user WHERE id =?");
    $stmt->execute(array($userid));
    $users = $stmt->fetch();
    $usercount = $stmt->rowCount();
    ?>
<?php if($usercount > 0) : ?>
    <div class="container">
        <h1 class="text-center">Show Page</h1>
        <div class="user m-5">
            <form method="POST" action="?action=update" enctype="multipart/form-data">
                <div class="mb-3">
                    <label  class="form-label">User Name</label>
                    <input type="text" class="form-control" value="<?= $users['username'] ?>" name="username">
                    <div  class="form-text">We'll never share your User Name with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="<?= $users['email'] ?>" name="email">
                    <div  class="form-text">We'll never share your Email Address with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="fullname" value="<?= $users['fullname'] ?>">
                    <div  class="form-text">We'll never share your Full Name with anyone else.</div>
                </div>
                <div class="mb-3">
                    <label  class="form-label">Photo</label>
                    <input type="text" class="form-control" name="avater" value="<?= $users['image'] ?>">
                    <div  class="form-text">We'll never share your Image with anyone else.</div>
                </div>
                <a href="members.php" class="btn btn-dark">Back</a>
            </form>
        </div>
    </div>
    <?php else:?>
    <?php header("location:members.php"); ?>
<?php endif; ?>
<?php endif;?>
