<?php
session_start();
include 'koneksi.php';

$koneksi = new Koneksi();
$conn = $koneksi->getConnection();



$message = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = mysqli_real_escape_string($conn, $_POST['nama']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash(mysqli_real_escape_string($conn, $_POST['pw']), PASSWORD_BCRYPT);
    
    $query = "INSERT INTO pengguna (nama, email, pw) VALUES ('$nama', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
        header("Location: login.php");
        exit();
    } else {
        $message = 'Terjadi kesalahan saat registrasi. Silakan coba lagi.';
    }
}
?>



<!DOCTYPE html>  
<html lang="id">  
<head>  
    <meta name="viewport" content="width=device-width, initial-scale=1">  
    <meta charset="UTF-8">
    <title>Registrasi</title>
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
            margin-top: 5%;
            border-radius: 10px;
        }  
        input[type=text], input[type=email], input[type=password] {  
            width: 100%;  
            padding: 15px;  
            margin: 5px 0 22px 0;  
            display: inline-block;  
            border: none;  
            background: #f1f1f1;  
        }  
        input[type=text]:focus, input[type=email]:focus, input[type=password]:focus {  
            background-color: orange;  
            outline: none;  
        }  
        .registerbtn {  
            background-color: #4CAF50;  
            color: white;  
            padding: 16px 20px;  
            margin: 8px 0;  
            border: none;  
            cursor: pointer;  
            width: 100%;  
            opacity: 0.9;  
        }  
        .registerbtn:hover {  
            opacity: 1;  
        }  
        #message {
            display: none;
            background: #f1f1f1;
            color: #000;
            position: relative;
            padding: 20px;
            margin-top: 10px;
            border-radius: 10px;
        }
        #message p {
            padding: 10px 35px;
            font-size: 18px;
        }
        .valid {
            color: green;
        }
        .valid:before {
            position: relative;
            left: -35px;
            content: "✔";
        }
        .invalid:before {
            position: relative;
            left: -35px;
            content: "✖";
        }
    </style>  
</head>  
<body onload="showAlert('<?php echo $message; ?>')">  
    <div class="container">  
        <center><h1>Formulir Registrasi</h1></center>
        <hr>  
        <form id="mk" method="POST" action="register.php">
            <label for="nama">Nama*</label>   
            <input type="text" id="nama" name="nama" placeholder="Masukkan Nama" required />   

            <label for="email">Email*</label>  
            <input type="email" id="email" name="email" placeholder="Masukkan Email" required>

            <label for="psw">Password*</label>
            <input type="password" id="psw" name="pw" placeholder="Masukkan Password" pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}" title="Harus berisi minimal satu angka, satu huruf besar, dan minimal 8 karakter" required>
            
            <div id="message">
                <h3>Password harus memiliki:</h3>
                <p id="letter" class="invalid">Huruf kecil</p>
                <p id="capital" class="invalid">Huruf besar (Kapital)</p>
                <p id="number" class="invalid">Angka</p>
                <p id="length" class="invalid">Minimal <b>8 karakter</b></p>
            </div>

            <input type="checkbox" id="myCheck" name="myCheck" required>
            <label for="myCheck">Dengan ini, saya menyatakan bahwa data yang saya berikan adalah benar</label> 
            
            <input type="submit" value="Daftar" class="registerbtn">
        </form>
    </div>  

<script>
function showAlert(message) {
    if (message) {
        alert(message);
    }
}

var myInput = document.getElementById("psw");
var letter = document.getElementById("letter");
var capital = document.getElementById("capital");
var number = document.getElementById("number");
var length = document.getElementById("length");

myInput.onfocus = function() {
    document.getElementById("message").style.display = "block";
}
myInput.onblur = function() {
    document.getElementById("message").style.display = "none";
}
myInput.o
