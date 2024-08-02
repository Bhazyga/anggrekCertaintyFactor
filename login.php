<?php
session_start();
include 'koneksi.php'; 

$koneksi = new Koneksi();
$conn = $koneksi->getConnection(); 

$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['pw']);
    
    $query = "SELECT id, pw FROM pengguna WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (!$result) {
        die("Query failed: " . mysqli_error($conn));
    }
    
    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if (password_verify($password, $row['pw'])) {
            // session
            $_SESSION['user_id'] = $row['id'];
            header("Location: index");
            exit();
        } else {
            $message = 'Password salah.';
        }
    } else {
        $message = 'Email tidak ditemukan.';
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <style> 
        body {  
            font-family: Calibri, Helvetica, sans-serif;  
            background-color: rgb(255, 11, 51);  
        }  
        .container {  
            padding: 50px;  
            background-color: rgb(187, 205, 211);  
            width: 30%;
            margin: 0 auto;
            margin-top: 10%;
            border-radius: 10px;
        }  
        input[type=email], input[type=password] {  
            width: 100%;  
            padding: 15px;  
            margin: 5px 0 22px 0;  
            display: inline-block;  
            border: none;  
            background: #f1f1f1;  
        }  
        input[type=email]:focus, input[type=password]:focus {  
            background-color: orange;  
            outline: none;  
        }  
        .tombol {  
            background-color: #4CAF50;  
            color: white;  
            padding: 16px 20px;  
            margin: 8px 0;  
            border: none;  
            cursor: pointer;  
            width: 100%;  
            opacity: 0.9;  
        }  
        .tombol:hover {  
            opacity: 1;  
        }
        .register-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #4CAF50;
            text-decoration: none;
        }
        .register-link:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>
        <form action="login.php" method="POST">
            <label for="email">Email:</label><br>
            <input type="email" id="email" name="email" required><br><br>
            <label for="pw">Password:</label><br>
            <input type="password" id="pw" name="pw" required><br><br>
            <input class="tombol" type="submit" value="Login">
            <a href="register.php" class="register-link">Daftar</a>
            <?php if (!empty($message)) { ?>
                <p class="error-message"><?php echo $message; ?></p>
            <?php } ?>
        </form>
    </div>
</body>
</html>
