<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css//style.css">
    <title>task</title>
</head>
<body>
    <a href="./create.php" class="add-task-link">Add Tasks</a>
<?php
    require '../connect.php';
    $sql=" SELECT *from tasks";
    $result=$conn->query($sql);
    if ($result->num_rows>0) {
         // عرض كل مهمة و المستخدمين المرتبطين بها
    while ($row = $result->fetch_assoc()) {
        $task_id = $row['task_id'];
        $task = $row['task'];
        $descriptions = $row['descriptions'];

        // استعلام لعرض المستخدمين المرتبطين بهذه المهمة
        $sql_users = "SELECT USER.username FROM USER INNER JOIN  tasks_user ON tasks_user.user_id = USER.user_id WHERE tasks_user.task_id = ?";
        $stmt = $conn->prepare($sql_users);
        $stmt->bind_param("i", $task_id);
        $stmt->execute();
        $stmt->bind_result($username);

        $assigned_users = [];
        while ($stmt->fetch()) {
            $assigned_users[] = $username;
        }
       $stmt->close();

?>     
<div class="task-container">
    
         <h3><?php echo htmlspecialchars($task) ; ?></h3>
         <br> <br>
         
           <P> <?php echo htmlspecialchars($row['descriptions']); ?></P>
            <br>
<?PHP
            if (count($assigned_users) > 0) {
            echo "<ul>";
            foreach ($assigned_users as $user) {
                echo "<li>" . htmlspecialchars($user) . "</li>";  // طباعة كل اسم مستخدم في عنصر قائمة
            }
            echo "</ul>";
        } else {
            echo "<p>No users assigned to this task.</p>";
        }

      

    ?>
 <div class="action-links">
            <a href="./ud.php?box=delete&task_id=<?php echo urlencode($row ['task_id']);?> ">delete</a>
            <a href="./ud.php?box=update&task_id=<?php echo urlencode($row ['task_id'])?> ">update</a>
            <br><br>
            </div>
            </div>
         <?php
           echo "<hr>";  // إضافة فاصل بين المهام
        }}
    else {
        echo "<p>No tasks found.</p>";
    }
    $conn->close();
?>
</body>
</html>