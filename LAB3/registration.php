<?php
session_start();
if (isset($_SESSION["user"])) {
   header("Location: index.php");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Form</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" integrity="sha384-Zenh87qX5JnK2Jl0vWa8Ck2rdkQ2Bzep5IDxbcnCeuOxjzrPF/et3URy9Bv1WTRi" crossorigin="anonymous">
    <link rel="stylesheet" href="stylesheet.css">
</head>
<body>
    <div class="container">
        <?php
        if (isset($_POST["submit"])) {
           $username = $_POST["username"];
           $password = $_POST["password"];
           $lastname = $_POST["lastname"];
           $firstname = $_POST["firstname"];
           $middlename = $_POST["middlename"];
           $email = $_POST["email"];
           
           $passwordHash = password_hash($password, PASSWORD_DEFAULT);

           $errors = array();
           
           if (empty($username) || empty($email) || empty($password) || empty($lastname) || empty($firstname) || empty($middlename)) {
            array_push($errors,"All fields are required");
           }
           if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($errors, "Email is not valid");
           }
           if (strlen($password) < 8) {
            array_push($errors,"Password must be at least 8 characters long");
           }
           require_once "database.php";
           $sql = "SELECT * FROM user WHERE email = ?";
           $stmt = mysqli_stmt_init($conn);
           if (mysqli_stmt_prepare($stmt, $sql)) {
               mysqli_stmt_bind_param($stmt, "s", $email);
               mysqli_stmt_execute($stmt);
               mysqli_stmt_store_result($stmt);
               $rowCount = mysqli_stmt_num_rows($stmt);
               if ($rowCount > 0) {
                   array_push($errors,"Email already exists!");
               }
           } else {
               die("Something went wrong");
           }

           if (count($errors) > 0) {
            foreach ($errors as  $error) {
                echo "<div class='alert alert-danger'>$error</div>";
            }
           } else {
            
            $sql = "INSERT INTO user (username, password, Lastname, First_name, Middle_name, Email) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (mysqli_stmt_prepare($stmt, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssssss", $username, $passwordHash, $lastname, $firstname, $middlename, $email);
                mysqli_stmt_execute($stmt);
                echo "<div class='alert alert-success'>You are registered successfully.</div>";
            } else {
                die("Something went wrong");
            }
           }
          

        }
        ?>
        <form action="register_process.php" method="post">
            <div class="form-group">
                <input type="text" class="form-control" name="username" placeholder="Username">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="firstname" placeholder="Firstname">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="middlename" placeholder="Middlename">
            </div>
            <div class="form-group">
                <input type="text" class="form-control" name="lastname" placeholder="Lastname">
            </div>
            <div class="form-group">
                <input type="email" class="form-control" name="email" placeholder="Email">
            </div>
            <div class="form-group">
                <input type="password" class="form-control" name="password" placeholder="Password">
            </div>
            <div class="form-btn">
                <input type="submit" class="btn btn-primary" value="Register" name="submit">
            </div>
        </form>
        <div>
        <div><p>Already Registered <a href="login.php">Login Here</a></p></div>
      </div>
    </div>
</body>
</html>
