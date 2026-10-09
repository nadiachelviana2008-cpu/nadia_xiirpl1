<?php
    include "koneksi.php";
    session_start();

    if(!isset($_SESSION['admin'])){
        header("location:login.php");
        exit;
    }

    if($_SESSION['level']!='kasir'){
        header("location:index.php");
        exit;
    }

    if(isset($_POST['simpan'])){
        mysqli_query($koneksi,"INSERT INTO produk
        VALUES(NULL,'$_POST[nama]','$_POST[harga]','$_POST[stok]')");
    }

    if(isset($_GET['hapus'])){
        mysqli_query($koneksi,"DELETE FROM produk
        WHERE ProdukID='$_GET[hapus]'");
    }

    if(isset($_POST['update'])){
        mysqli_query($koneksi,"UPDATE produk SET
        NamaProduk='$_POST[nama]',
        Harga='$_POST[harga]',
        Stok='$_POST[stok]'
        WHERE ProdukID='$_POST[id]'");
    }

    $edit = isset($_GET['edit']) ?
    mysqli_fetch_array(mysqli_query($koneksi,
    "SELECT * FROM produk WHERE ProdukID='$_GET[edit]'")) : null;

    $cari=$_GET['cari'] ?? '';

    if($cari==""){
        $data=mysqli_query($koneksi,"SELECT * FROM produk");
    }else{
        $data=mysqli_query($koneksi,"SELECT * FROM produk
        WHERE NamaProduk LIKE '%$cari%'");
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Produk</title>
        <link rel="stylesheet" href="asset/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container mt-4">
            <div class="card p-4 shadow">
            <span class="d-inline-block">
            <a href="index.php" class="btn btn-secondary btn-sm mb-3">
            ← Kembali ke Dashboard
            </a>
            </span>

            <h3 class="text-primary">Data Produk</h3>

            <!-- PENCARIAN -->
            <form method="get" class="d-flex gap-2 mb-3">
                <input type="text" name="cari"
                class="form-control"
                placeholder="Cari nama produk..."
                value="<?= $cari ?>">

                <button class="btn btn-primary btn-sm">Cari</button>

                <a href="produk.php"
                class="btn btn-secondary btn-sm">Reset</a>
            </form>

            <form method="post">

                <input type="hidden" name="id"
                value="<?= $edit['ProdukID'] ?? '' ?>">
                
                <label>Nama</label>
                <input name="nama"
                class="form-control mb-2"
                placeholder="Nama Produk"
                value="<?= $edit['NamaProduk'] ?? '' ?>"
                required>

                <label>Harga</label>
                <input type="number"
                name="harga"
                class="form-control mb-2"
                placeholder="Harga"
                value="<?= $edit['Harga'] ?? '' ?>"
                required>

                <label>Stok</label>
                <input type="number"
                name="stok"
                class="form-control mb-2"
                placeholder="Stok"
                value="<?= $edit['Stok'] ?? '' ?>"
                required>

                <button name="<?= $edit ? 'update' : 'simpan' ?>"
                class="btn btn-primary">
                <?= $edit ? 'Update' : 'Simpan' ?>
                </button>
            </form>
            <hr>
            <table class="table table-bordered">
                <tr class="table-primary">
                    <th>No</th>
                    <th>Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
                <?php
                $no=1;
                while($d=mysqli_fetch_array($data)){
                ?>
                <tr>
                    <td><?= $no++ ?></td>
                    <td><?= $d['NamaProduk'] ?></td>
                    <td>Rp <?= number_format($d['Harga']) ?></td>
                    <td><?= $d['Stok'] ?></td>
                    <td>
                        <a href="?edit=<?= $d['ProdukID'] ?>"
                        class="btn btn-warning btn-sm">Edit</a>

                        <a href="?hapus=<?= $d['ProdukID'] ?>"
                        class="btn btn-danger btn-sm"
                        onclick="return confirm('Hapus?')">
                        Hapus
                        </a>
                    </td>
                </tr>
                <?php } ?>
            </table>
            </div>
        </div>
    </body>
</html>