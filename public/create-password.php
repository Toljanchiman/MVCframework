<?php

session_start();

require $_SERVER['DOCUMENT_ROOT'] . '/connect.php';

if (isset($_POST['create'])) {
    $resetPass = $_SESSION['user_id_reset_pass'];
    
    if (!$resetPass) {
        header('/public/login.php');
        exit;
    } else {
        $pass = !empty($_POST['password']) ? trim($_POST['password']) : null;
    
        // Hash the new password
        $passwordHash = password_hash($pass, PASSWORD_BCRYPT, array("cost" => 12));
        
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(':password', $passwordHash);
        $statement->bindValue(':id', $resetPass);
    
        $result = $statement->execute();
        
        if ($result) {
            // Success
            echo 'Your password has been updated. <a href="/public/login.php">Login</a>';
        }
    }
}
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Create New Password</title>
    </head>
    <body>
        <h1>Create New Password</h1>
        <form action="create-password.php" method="post">
            <label for="password">Password</label>
            <input type="text" id="password" name="password"><br>
            <input type="submit" name="create" value="Create Password">
        </form>
    </body>
    <a href="/public">Home</a> <a href="/public/register.php">Register</a> <a href="/public/forgot-password.php">Forgot Password</a>
</html>
