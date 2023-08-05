<?php
$showAlert = false;
$showError = false;



if($_SERVER["REQUEST_METHOD"] == "POST"){
    include '_dbconnect.php';

    //Check maximum value
// $existSql2 = "SELECT MAX(id) from `users` ";
// $result2 = mysqli_query($conn, $existSql2);
// $value = (int) mysqli_fetch_assoc($result2);

    $username = $_POST["username"];
    $firstName = $_POST["firstName"];
    $lastName = $_POST["lastName"];
    $email = $_POST["email"];
    $phone = $_POST["phone"];
    $password = $_POST["password"];
    $cpassword = $_POST["cpassword"];
    
    // Check whether this username exists
    $existSql = "SELECT * FROM `users` WHERE username = '$username'";
    $result = mysqli_query($conn, $existSql);
    $numExistRows = mysqli_num_rows($result);

    if($numExistRows > 0){
        $showError = "Username Already Exists";
        header("Location: /DBMSmini/index.php?signupsuccess=false&error=$showError");
    }
    else{
      if(($password == $cpassword)){
          $hash = password_hash($password, PASSWORD_DEFAULT);

        //fetching max value from table 
        $result = mysqli_query($conn, "SELECT MAX(id) FROM users");
        $row = mysqli_fetch_assoc($result);


        //incrementing it by 1
        if(is_numeric($row['MAX(id)'])){
            $new_value = intval($row['MAX(id)']) + 1;
        }

          $sql = "INSERT INTO `users` (`id`, `username`, `firstName`, `lastName`, `email`, `phone`, `password`, `joinDate`) VALUES ('$new_value','$username', '$firstName', '$lastName', '$email', '$phone', '$hash', current_timestamp())"; 

          $result = mysqli_query($conn, $sql);
          
          if ($result){
              $showAlert = true;
              header("Location: /DBMSmini/index.php?signupsuccess=true");
          }
      }
      else{
          $showError = "Passwords do not match";
          header("Location: /DBMSmini/index.php?signupsuccess=false&error=$showError");
      }
    }
}
    
?>