<?php
$user="";
$user_err="";
$role="";
$role_err="";
function data_correcting($w){
    $w=htmlspecialchars($w);
    $w=trim($w);
    $w=stripslashes($w);
    return $w ;
}
 if (isset($_POST['submit_button'])) {
    if (empty($_POST['username'])) {
        $user_err="*required";
    }else
    $user=data_correcting($_POST['username']);
    if (empty($_POST['permission'])) {
        $role_err="*required";
    }else
    $role=data_correcting($_POST['permission']);
    if (!empty($user) && !empty($role)) {
        require_once '../connect.php';
        $sql_check = "SELECT * FROM USER WHERE username = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $user);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // إذا كان اسم المستخدم موجودًا بالفعل
            $user_err = "* Username already taken";
        } else {
            // إذا لم يكن موجودًا، أضف البيانات إلى قاعدة البيانات
            $stmt = $conn->prepare("INSERT INTO USER (username, permission) VALUES (?, ?)");
            $stmt->bind_param("ss", $user, $role);

            if ($stmt->execute()) {
            echo "data created successfuly <br> ";
        }else
        echo "error <br> " . $conn->error;
          $conn->close();
        header(" location , url= index.php ");
    }
 }
}
 ?>
 <!DOCTYPE html>
 <html lang="en">
 <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>add user</title>
 </head>
 <body>
    <div class="title">
    <h1>Add User</h1>
</div>
    <form action="" method="post">
        <label for="username">Name:</label>
        <br>
        <input type="text" name="username" id="username" placeholder="Enter username" >
        <br>
        <br>
        <div class="error"><?php echo  $user_err ;?></div>
        <br>
        <label for="role">Role:</label>
        <br>
        <input type="text" name="permission" id="role"placeholder="Enter role">
        <br>
       <div class="error"> <?php echo $role_err ;?></div>
        <br>
        <input type="submit" name="submit_button" id="send" value="send">
    </form>
 </body>
 </html>
