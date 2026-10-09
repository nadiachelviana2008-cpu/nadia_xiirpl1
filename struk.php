<?php
    include "koneksi.php";

    $id=$_GET['id'];

    $data=mysqli_query($koneksi,"
    SELECT p.TanggalPenjualan,p.TotalHarga,p.UangTunai,p.Kembalian,
    pel.NamaPelanggan,pr.NamaProduk,pr.Harga,
    d.JumlahProduk,d.Subtotal
    FROM penjualan p
    JOIN pelanggan pel ON p.PelangganID=pel.PelangganID
    JOIN detailpenjualan d ON p.PenjualanID=d.PenjualanID
    JOIN produk pr ON d.ProdukID=pr.ProdukID
    WHERE p.PenjualanID='$id'
    ");

    $d=mysqli_fetch_array($data);

    $total=$d['TotalHarga'];
    $tunai=$d['UangTunai'];
    $kembali=$d['Kembalian'];
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Struk</title>
        <link rel="stylesheet" href="asset/bootstrap.min.css">
        <style>
        @media print{
            .tombol{
                display:none;
            }
        }
        </style>
    </head>
    <body class="bg-light">
        <div class="card shadow mx-auto mt-5 p-4" style="max-width:500px">
            <h3 class="text-center text-primary">TOKO NADIA</h3>
            <p class="text-center">Struk Penjualan</p>
            <hr>
            <p>
                <b>No Transaksi :</b> <?= $id ?><br>
                <b>Tanggal :</b> <?= $d['TanggalPenjualan'] ?><br>
                <b>Pelanggan :</b> <?= $d['NamaPelanggan'] ?>
            </p>
            <hr>
            <?php
            mysqli_data_seek($data,0);
            while($d=mysqli_fetch_array($data)){
            ?>
            <div class="mb-2">
                <b><?= $d['NamaProduk'] ?></b><br>
                <?= $d['JumlahProduk'] ?> x
                Rp <?= number_format($d['Harga']) ?>
                <span class="float-end">
                    Rp <?= number_format($d['Subtotal']) ?>
                </span>
            </div>
            <?php } ?>
            <hr>
            <div class="mb-3">
                <div class="d-flex justify-content-between">
                    <b>Total</b>
                    <b>Rp <?= number_format($total) ?></b>
                </div>
                <div class="d-flex justify-content-between">
                    <b>Uang Tunai</b>
                    <b>Rp <?= number_format($tunai) ?></b>
                </div>
                <div class="d-flex justify-content-between">
                    <b>Kembalian</b>
                    <b>Rp <?= number_format($kembali) ?></b>
                </div>
            </div>
            <hr>
            <p class="text-center">
            Terima kasih sudah berbelanja 😊
            </p>
            <div class="tombol text-center">
                <button onclick="window.print()"
                class="btn btn-primary btn-sm">
                Cetak
                </button>
                <a href="penjualan.php"
                class="btn btn-secondary btn-sm">
                Kembali
                </a>
            </div>
        </div>
    </body>
</html>