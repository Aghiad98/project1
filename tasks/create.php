<?php 
$task = $descriptions = $task_id = "";
$task_err = $descriptions_err = $task_id_err = "";

function data_correcting($w){
    $w = htmlspecialchars($w);
    $w = trim($w);
    $w = stripslashes($w);
    return $w;
}

if (isset($_POST['submit_button'])) {
    // التحقق من البيانات المدخلة
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
        // التعامل مع task_id كـ مصفوفة
        $task_id = $_POST['task_id']; // هنا نحتفظ بالمصفوفة كاملة
    }

    if (!empty($task) && !empty($descriptions) && !empty($task_id)) {
        require '../connect.php';

        // إضافة المهمة إلى جدول tasks
        $sql = "INSERT INTO tasks (task, descriptions) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $task, $descriptions); // ربط المعاملات كـ نصوص
        if ($stmt->execute()) {
            // الحصول على الـ task_id الذي تم إدخاله
            $task_id_inserted = $conn->insert_id;

            // إدخال المستخدمين في جدول task_user
            foreach ($task_id as $user_id) {
                $sql_task_user = "INSERT INTO tasks_user (user_id, task_id) VALUES (?, ?)";
                $stmt_task_user = $conn->prepare($sql_task_user);
                $stmt_task_user->bind_param("ii", $user_id, $task_id_inserted); // ربط user_id و task_id
                $stmt_task_user->execute();
                $stmt_task_user->close();
            }

            echo "Data created successfully and users assigned to the task.";
            header("Location: index.php"); // إعادة التوجيه بعد تنفيذ الإدخال بنجاح
            exit(); // تأكد من إنهاء السكربت بعد إعادة التوجيه
        } else {
            echo "Error: " . $conn->error;
        }

        // غلق الاتصال
        $stmt->close();
        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Add Task</title>
</head>
<body>
    <h1>Add Task</h1>
    <form action="" method="post">
        <label for="task">Task:</label>
        <br>
        <input type="text" name="task" id="task" value="<?php echo $task; ?>">
        <br>
        <div class="error"><?php echo $task_err; ?></div>
        <br>

        <label for="descriptions">Descriptions:</label>
        <br>
        <textarea name="descriptions" id="descriptions"><?php echo $descriptions; ?></textarea>
        <br>
        <div class="error"><?php echo $descriptions_err; ?></div>
        <br>

        <?php
        require '../connect.php';
        $sql = "SELECT user_id, username FROM USER";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            ?>
            <label for="task_id">Select Users:</label>
            <select name="task_id[]" id="task_id" multiple>
                <?php
                while ($row = $result->fetch_assoc()) {
                    ?>
                    <option value="<?php echo $row['user_id']; ?>" 
                        <?php 
                        if (isset($_POST['task_id']) && in_array($row['user_id'], $_POST['task_id'])) echo "selected"; 
                        ?>>
                        <?php echo $row['username']; ?>
                    </option>
                    <?php
                }
                ?>
            </select>
            <?php
            echo $task_id_err;
            ?>
            <br>
            <p>Hold Ctrl to select more than one user</p>
            <?php
        }
        ?>

        <br>
        <input type="submit" name="submit_button" value="Submit">
        <br>
    </form>
</body>
</html>
