<?php
    session_start();

    if(!isset($_SESSION['admin']) || !isset($_SESSION['level'])){
        header("location:login.php");
        exit;
    }

    include "koneksi.php";

    $pelanggan=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM pelanggan"));
    $produk=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM produk"));
    $penjualan=mysqli_num_rows(mysqli_query($koneksi,"SELECT * FROM penjualan"));

    // Filter tanggal
    $dari=$_GET['dari']??'';
    $sampai=$_GET['sampai']??'';

    $where="WHERE 1=1";

    if($dari!=""){
        $where.=" AND DATE(p.TanggalPenjualan)>='$dari'";
    }

    if($sampai!=""){
        $where.=" AND DATE(p.TanggalPenjualan)<='$sampai'";
    }

    $data=mysqli_query($koneksi,"SELECT 
        p.PenjualanID,
        p.TanggalPenjualan,
        pel.NamaPelanggan,
        p.TotalHarga,
        GROUP_CONCAT(pr.NamaProduk SEPARATOR ', ') barang,
        GROUP_CONCAT(d.JumlahProduk SEPARATOR ', ') jumlah
        FROM penjualan p 
        JOIN pelanggan pel ON p.PelangganID=pel.PelangganID
        JOIN detailpenjualan d ON p.PenjualanID=d.PenjualanID 
        JOIN produk pr ON d.ProdukID=pr.ProdukID 
        $where
        GROUP BY p.PenjualanID
        ORDER BY p.TanggalPenjualan DESC
    ");

    $totalUang=0;
?>

<!DOCTYPE html>
<html>

<head>

    <title>Dashboard</title>

    <link rel="stylesheet" href="asset/bootstrap.min.css">

</head>

<body class="bg-light">

    <nav class="navbar navbar-dark bg-primary">

        <div class="container">

            <span class="navbar-brand fw-bold">
                Sistem Penjualan
            </span>

            <a href="logout.php" class="btn btn-danger btn-sm">
                Logout
            </a>

        </div>

    </nav>


    <div class="container mt-4">

        <div class="card shadow-sm p-4 mb-4">

            <h2 class="text-primary">
                Dashboard
            </h2>

            <p>
                Selamat datang di Sistem Pengelolaan Penjualan Nadia.
            </p>

        </div>


        <!-- MENU KASIR -->

        <?php if($_SESSION['level']=='kasir'){ ?>

            <div class="row g-3">

                <div class="col-md-4">

                    <div class="card shadow-sm p-4 text-center">

                        <h5>
                            Pelanggan
                        </h5>

                        <h2 class="text-primary">
                            <?= $pelanggan ?>
                        </h2>

                        <a href="pelanggan.php"
                           class="btn btn-primary">

                            Kelola Pelanggan

                        </a>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card shadow-sm p-4 text-center">

                        <h5>
                            Produk
                        </h5>

                        <h2 class="text-primary">
                            <?= $produk ?>
                        </h2>

                        <a href="produk.php"
                           class="btn btn-primary">

                            Kelola Produk

                        </a>

                    </div>

                </div>


                <div class="col-md-4">

                    <div class="card shadow-sm p-4 text-center">

                        <h5>
                            Penjualan
                        </h5>

                        <h2 class="text-primary">
                            <?= $penjualan ?>
                        </h2>

                        <a href="penjualan.php"
                           class="btn btn-primary">

                            Kelola Penjualan

                        </a>

                    </div>

                </div>

            </div>

        <?php } ?>


        <!-- LAPORAN HANYA UNTUK ADMIN -->

        <?php if($_SESSION['level']=='admin'){ ?>

            <div class="card shadow-sm p-4 mt-4 mb-4">

                <h4 class="text-primary">
                    Laporan Penjualan
                </h4>


                <!-- Filter Tanggal -->

                <form method="get"
                      class="d-flex gap-2 mb-3 align-items-end">

                    <div>

                        <label class="form-label">
                            Dari Tanggal
                        </label>

                        <input type="date"
                               name="dari"
                               class="form-control"
                               value="<?= $dari ?>">

                    </div>


                    <div>

                        <label class="form-label">
                            Sampai Tanggal
                        </label>

                        <input type="date"
                               name="sampai"
                               class="form-control"
                               value="<?= $sampai ?>">

                    </div>


                    <button class="btn btn-primary btn-sm">

                        Tampilkan

                    </button>

                </form>


                <table class="table table-bordered">

                    <tr class="table-primary">

                        <th>
                            No
                        </th>

                        <th>
                            Tanggal
                        </th>

                        <th>
                            Pelanggan
                        </th>

                        <th>
                            Barang Dibeli
                        </th>

                        <th>
                            Jumlah
                        </th>

                        <th>
                            Total
                        </th>

                    </tr>


                    <?php

                    $no=1;

                    while($d=mysqli_fetch_array($data)){

                        $totalUang += $d['TotalHarga'];

                    ?>

                    <tr>

                        <td>
                            <?= $no++ ?>
                        </td>

                        <td>
                            <?= $d['TanggalPenjualan'] ?>
                        </td>

                        <td>
                            <?= $d['NamaPelanggan'] ?>
                        </td>

                        <td>
                            <?= $d['barang'] ?>
                        </td>

                        <td>
                            <?= $d['jumlah'] ?>
                        </td>

                        <td>
                            Rp <?= number_format($d['TotalHarga']) ?>
                        </td>

                    </tr>

                    <?php } ?>

                </table>


                <div class="d-flex justify-content-between align-items-center">

                    <h5 class="mb-0">

                        Total Penjualan:
                        Rp <?= number_format($totalUang) ?>

                    </h5>


                    <a href="cetak.php?dari=<?= $dari ?>&sampai=<?= $sampai ?>"
                       target="_blank"
                       class="btn btn-primary btn-sm">

                        Cetak Laporan

                    </a>

                </div>

            </div>

        <?php } ?>


    </div>

</body>

</html>