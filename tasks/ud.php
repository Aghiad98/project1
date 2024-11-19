<?php
if (isset($_GET['box'])) {
    if ($_GET['box'] == "update") {
        if (isset($_GET['task_id'])) {
            $task_id = $_GET['task_id'];
            require '../connect.php'; 

            // جلب بيانات المهمة
            $sql = "SELECT task_id, task, descriptions FROM tasks WHERE task_id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $task_id);
            $stmt->execute();
            $stmt->bind_result($task_id, $task, $descriptions);
            if ($stmt->fetch()) {
                // تم العثور على المهمة وتعيين البيانات
            } else {
                echo "المهمة غير موجودة.";
                exit();
            }
            $stmt->close();

            // جلب المستخدمين المرتبطين بالمهمة
            $sql_users = "SELECT user_id FROM tasks_user WHERE task_id = ?";
            $stmt_users = $conn->prepare($sql_users);
            $stmt_users->bind_param("i", $task_id);
            $stmt_users->execute();
            $result_users = $stmt_users->get_result();
            $selected_users = [];
            while ($row = $result_users->fetch_assoc()) {
                $selected_users[] = $row['user_id'];
            }
            $stmt_users->close();
        

        // معالجة البيانات المرسلة
        function data_correcting($w){
            $w = htmlspecialchars($w);
            $w = trim($w);
            $w = stripslashes($w);
            return $w;
        }

        $task_err = $descriptions_err = $task_id_err = "";
        if (isset($_POST['submit_button'])) {
            if (empty($_POST['task'])) {
                $task_err = '*required';
            } else {
                $task = data_correcting($_POST['task']);
            }
            if (empty($_POST['descriptions'])) {
                $descriptions_err = '*required';
            } else {
                $descriptions = data_correcting($_POST['descriptions']);
            }
            if (empty($_POST['task_id'])) {
                $task_id_err = '*required';
            } else {
                $task_id = $_POST['task_id'];
            }

            if (!empty($task) && !empty($descriptions)) {
                require '../connect.php';
                $sql = "UPDATE tasks SET task = ?, descriptions = ? WHERE task_id = ?";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssi", $task, $descriptions, $task_id);
                if ($stmt->execute()) {
                    echo"the data updated sucessfully ";
                    header("Location: index.php"); // إعادة التوجيه إلى صفحة المستخدمين
            exit();
                } else {
                    echo "wrror " . $stmt->error;
                }
                
                $stmt->close();
            }}
        
?>

         <!DOCTYPE html>
         <html lang="en">
         <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" href="../css/style.css">
            <title>update</title>
         </head>
         <body>
            <h1>update task</h1>
            <form action=""method="post">
                <label for="task">task:</label>
                <br>
                <input type="text"name="task"id="task" value="<?php echo $task;?>">
                <br>
    <div class="error"><?php echo $task_err ;?></div>
    <br>
    <label for="descriptions">descriptions:</label>
    <br>
    <textarea name="descriptions" id="descriptions" ><?php echo $descriptions ;?></textarea>
    <br>
    <div class="error"><?php echo $descriptions_err ;?></div>
    <br>
    <?php
    require '../connect.php';
    $sql2=" SELECT * FROM USER ";
    $result2=$conn->query($sql2);
    if ($result2->num_rows>0) {
        ?>
        <select name="user_id" id="" multiple>
            <?php
    
            while ($row2=$result2->fetch_assoc()) {
                ?>
                <option value="<?php echo $row2['user_id']?>" <?php echo( $row2['user_id']==$task_id) ? 'selected':'';?>><?php echo $row2['username']?></option>
            <?php
            }  ?>
        </select>
        <br>
        <br>
    <p>hold Ctrl to select more than one user</p>
        <?php   
    }
    ?>
    <input type="submit" name="submit_button" id="" value="update">
</form>
</body>
</html>
<?php
        
}}elseif ($_GET['box'] == "delete") {
    $task_id = intval($_GET['task_id']);
    require '../connect.php';

    // أولاً، حذف السجلات المرتبطة في جدول tasks_user
    $delete_tasks_user_sql = "DELETE FROM tasks_user WHERE task_id = ?";
    $delete_tasks_user_stmt = $conn->prepare($delete_tasks_user_sql);
    $delete_tasks_user_stmt->bind_param("i", $task_id);
    if ($delete_tasks_user_stmt->execute()) {
        echo "Related user-task data deleted successfully!<br>";
    } else {
        echo "Error deleting related task-user data: " . $delete_tasks_user_stmt->error;
        exit();  // توقف في حالة فشل الحذف من tasks_user
    }
    $delete_tasks_user_stmt->close();

    // الآن، يمكن حذف السطر من جدول tasks
    $delete_tasks_sql = "DELETE FROM tasks WHERE task_id = ?";
    $delete_tasks_stmt = $conn->prepare($delete_tasks_sql);
    $delete_tasks_stmt->bind_param("i", $task_id);
    if ($delete_tasks_stmt->execute()) {
        echo "Task deleted successfully!<br>";
        header("Location: index.php");  // إعادة التوجيه بعد الحذف
        exit();
    } else {
        echo "Error deleting task: " . $delete_tasks_stmt->error;
    }
    $delete_tasks_stmt->close();
    $conn->close();
}}
?>



