<?php
$servername="localhost";
$username="root";
$password="";
$conn=new mysqli($servername,$username,$password);
if ($conn->connect_error ) {
    die ("Connection failed: " . $conn->connect_error);}
//create new database
/* $sql=" CREATE DATABASE project ";
if ($conn->query($sql)){
    echo "database created successfuly <br>";
}else{
    echo "error creating database:<br>".$conn->error;
} */
  $conn=new mysqli($servername,$username,$password,'project');
//create new table
/*   $sql=" CREATE TABLE USER( 
      user_id int auto_increment primary key ,
      username varchar(25) not null ,
      permission varchar (25) not null );";
      if ($conn->query($sql)){
        echo "table created successfuly <br>";
    }else{
        echo "error creating table:<br>".$conn->error;
    } */
   //create new table 
/*     $sql=" CREATE TABLE tasks( 
        task_id int auto_increment primary key ,
        task varchar(25) not null ,
        descriptions varchar (25) not null );";
        if ($conn->query($sql)){
          echo "table created successfuly <br>";
      }else{
          echo "error creating table:<br>".$conn->error;
      } */
//create new table
 /*       $sql=" CREATE TABLE tasks_user (
      tu_id int auto_increment primary key ,
      user_id int ,
      task_id int ,
      FOREIGN KEY (user_id) REFERENCES USER(user_id) , 
      FOREIGN KEY (task_id) REFERENCES tasks(task_id) );";
      if($conn->query($sql)){
            echo "table created successfuly <br>";
      }else{
            echo "error creating table:<br>" .$conn->error;
      }  */
        /* $conn->close(); */
     ?>