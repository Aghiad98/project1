<?php
// دالة تصحيح البيانات
function data_correcting($w){
    $w = htmlspecialchars($w);
    $w = trim($w);
    $w = stripslashes($w);
    return $w;
}

if (isset($_GET['box'])) {
    if ($_GET['box'] == "update") {
        $user_id = intval($_GET['user_id']);
        require_once '../connect.php';

        // استعلام لجلب البيانات الخاصة بالمستخدم
        $sql = "SELECT * FROM USER WHERE user_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $user = $role = "";
            $user_err = $role_err = "";

            if (isset($_POST['submit_button'])) {
                if (empty($_POST['username'])) {
                    $user_err = "*required";
                } else {
                    $user = data_correcting($_POST['username']);
                }

                if (empty($_POST['permission'])) {
                    $role_err = "*required";
                } else {
                    $role = data_correcting($_POST['permission']);
                }

                // التأكد من أن البيانات المدخلة غير فارغة
                if (!empty($user) && !empty($role)) {
                    $update_sql = "UPDATE USER SET username = ?, permission = ? WHERE user_id = ?";
                    $update_stmt = $conn->prepare($update_sql);
                    $update_stmt->bind_param("ssi", $user, $role, $user_id);

                    if ($update_stmt->execute()) {
                        echo "Data updated successfully!<br>";
                        header("Location: index.php"); // إعادة التوجيه إلى صفحة المستخدمين
                        exit();
                    } else {
                        echo "Error updating data: " . $update_stmt->error;
                    }
                    $update_stmt->close();
                }
            }
        }
    } elseif ($_GET['box'] == "delete") {
        // تحقق من وجود معرف المستخدم
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    die("Invalid user ID.");
}
$user_id = intval($_GET['user_id']);
 require '../connect.php'; 
        
        // استعلام لحذف البيانات
         if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
        // أولاً، حذف السجلات المرتبطة في جدول tasks_user
$delete_tasks_sql = "DELETE FROM tasks_user WHERE user_id = ?";
$delete_tasks_stmt = $conn->prepare($delete_tasks_sql);
$delete_tasks_stmt->bind_param("i", $user_id);

if (!$delete_tasks_stmt->execute()) {
    echo "Error deleting related tasks: " . $delete_tasks_stmt->error;
    exit(); // إذا فشل حذف السجلات المرتبطة، لا تتابع مع حذف المستخدم
}
        $delete_sql = "DELETE FROM USER WHERE user_id = ?";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bind_param("i", $user_id);
        if ($delete_stmt->execute()) {
            echo "Data deleted successfully!<br>";
            header("Location: index.php"); // إعادة التوجيه إلى صفحة المستخدمين
            exit();
        } else {
            echo "Error deleting data: " . $delete_stmt->error;
        }
        $delete_stmt->close();
    } else {
        echo "Error: Invalid 'box' parameter";
    }

    // إغلاق الاتصال مع قاعدة البيانات
    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>update user</title>
</head>
<body>
    <form action="" method="post">
        <label for="username">Name:</label>
        <br>
        <input type="text" name="username" id="username" value=" <?php echo $row['username']; ?>">
        <br>
        <span class="error"><?php echo $user_err; ?></span>
        <br>
        <label for="role">Role:</label>
        <br>
        <input type="text" name="permission" id="role" value=" <?php echo $row['permission']; ?>">
        <br>
        <span class="error"><?php echo $role_err; ?></span>
        <br>
        <input type="submit" name="submit_button" id="send" value="Update">
    </form>
</body>
</html>
