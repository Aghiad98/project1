<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>user</title>
</head>
<body>
     <div class="index">
    <a href="./create.php"class="button">add user</a>
    <a href="../tasks/index.php" class="button">tasks</a>
    <?php 
        require_once '../connect.php';
        $sql=" SELECT * FROM USER ";
        $result=$conn->query($sql);
        if($result->num_rows > 0){
        ?>
    <table>
        <thead>
        <tr> 
           <th> Name </th>
           <th> Role </th>
           <th> Update </th>
           <th> Delete </th>
        </tr>
        </thead>
        <tbody>
            <?php
             while ($row=$result->fetch_assoc()){ 
                ?>
        <tr>
            
            <td><?php echo htmlspecialchars( $row ['username']) ?></td>
            <td><?php echo htmlspecialchars($row['permission']) ?></td>
            <td> <a href="./ud.php?box=update&user_id=<?php echo urlencode($row['user_id']);?>"class="button1">update</a></td>
            <td> <a href="./ud.php?box=delete&user_id=<?php echo urlencode($row['user_id']);?>"class="button2">delete</a></td>
        </tr>
            <?php 
            }
            ?>
        </tbody>
        </table>
        <?php
             }else{
             echo "<p>No users found.</p>";
            }
            $conn->close();
             
       
       ?>
</div>
</body>
</html>