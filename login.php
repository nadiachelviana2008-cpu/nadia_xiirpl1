<?php
    session_start();
    include "koneksi.php";

    if(isset($_POST['login'])){
        $username = $_POST['username'];
        $password = $_POST['password'];

        $data = mysqli_query($koneksi, "SELECT * FROM admin 
                WHERE Username='$username' AND Password='$password'");

        if(mysqli_num_rows($data) > 0){
            $d = mysqli_fetch_array($data);

            $_SESSION['admin'] = $d['Username'];
            $_SESSION['level'] = $d['Level'];

            header("location:index.php");
            exit;
        }else{
            $pesan = "Username atau password salah!";
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login - Nadia Alat Tulis</title>

    <link rel="stylesheet" href="asset/bootstrap.min.css">

    <style>
        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #eaf4ff;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            border: none;
            border-radius: 20px;
            overflow: hidden;
        }

        .logo {
            width: 120px;
            height: 120px;
            object-fit: contain;
            margin-bottom: 10px;
        }

        .judul {
            color: #1769d1;
            font-weight: bold;
            font-size: 30px;
        }

        .subjudul {
            color: #6c757d;
            font-size: 15px;
        }

        .btn-login {
            background: #1769d1;
            border: none;
            padding: 11px;
            font-weight: bold;
        }

        .btn-login:hover {
            background: #0d57b5;
        }
    </style>
</head>

<body>

<div class="card login-card shadow p-4">

    <div class="text-center">

        <!-- LOGO ALAT TULIS -->
        <img src="asset/logo.png"
             alt="Logo Nadia Alat Tulis"
             class="logo">

        <div class="judul">NADIA</div>

        <div class="subjudul mb-4">
            Sistem Penjualan Alat Tulis
        </div>

    </div>

    <?php if(isset($pesan)){ ?>
        <div class="alert alert-danger">
            <?= $pesan ?>
        </div>
    <?php } ?>

    <form method="post">

        <label class="mb-1">Username</label>
        <input type="text"
               name="username"
               class="form-control mb-3"
               placeholder="Masukkan username"
               required>

        <label class="mb-1">Password</label>
        <input type="password"
               name="password"
               class="form-control mb-4"
               placeholder="Masukkan password"
               required>

        <button type="submit"
                name="login"
                class="btn btn-primary btn-login w-100">
            Login
        </button>

    </form>

</div>

</body>
</html>