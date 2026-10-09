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
        mysqli_query($koneksi,"INSERT INTO pelanggan VALUES(NULL,'$_POST[nama]','$_POST[alamat]','$_POST[telp]')");

        header("location:pelanggan.php");
        exit;
    }

    if(isset($_GET['hapus'])){
        mysqli_query($koneksi,"DELETE FROM pelanggan WHERE PelangganID='$_GET[hapus]'");

        header("location:pelanggan.php");
        exit;
    }

    if(isset($_POST['update'])){
        mysqli_query($koneksi,"UPDATE pelanggan SET NamaPelanggan='$_POST[nama]',Alamat='$_POST[alamat]',
        NomorTelepon='$_POST[telp]' WHERE PelangganID='$_POST[id]'");

        header("location:pelanggan.php");
        exit;
    }

    $edit = isset($_GET['edit']) ?
    mysqli_fetch_array(mysqli_query($koneksi,"SELECT * FROM pelanggan WHERE PelangganID='$_GET[edit]'")) : null;
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Pelanggan</title>
        <link rel="stylesheet" href="asset/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container mt-4">
            <div class="card p-4 shadow">
                <span class="d-inline-block">
                    <a href="index.php" class="btn btn-secondary btn-sm mb-3">← Kembali ke Dashboard</a>
                </span>
                <h3 class="text-primary">Data Pelanggan</h3>
                <form method="get" class="d-flex gap-2 mb-3">
                    <input type="text" name="cari" class="form-control" placeholder="Cari pelanggan..." value="<?= $_GET['cari'] ?? '' ?>">
                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                    <a href="pelanggan.php" class="btn btn-secondary btn-sm">Reset</a>
                </form>
                <form method="post">
                    <input type="hidden" name="id" value="<?= $edit['PelangganID'] ?? '' ?>">
                    <label>Nama</label>
                    <input name="nama" class="form-control mb-2" placeholder="Nama" value="<?= $edit['NamaPelanggan'] ?? '' ?>" required>
                    <label>Alamat</label>
                    <input name="alamat" class="form-control mb-2" placeholder="Alamat" value="<?= $edit['Alamat'] ?? '' ?>">
                    <label>Telepon</label>
                    <input type="text" name="telp" class="form-control mb-2" placeholder="Telepon" value="<?= $edit['NomorTelepon'] ?? '' ?>"
                    maxlength="13" pattern="[0-9]{1,13}" oninput="this.value=this.value.replace(/[^0-9]/g,'')">
                    <button name="<?= $edit ? 'update' : 'simpan' ?>" class="btn btn-primary">
                        <?= $edit ? 'Update' : 'Simpan' ?>
                    </button>
                </form>
                <hr>
                <table class="table table-bordered">
                    <tr class="table-primary">
                        <th>No</th>
                        <th>Nama</th>
                        <th>Alamat</th>
                        <th>Telepon</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                        $no=1;
                        $cari=$_GET['cari'] ?? '';
                        if($cari==""){
                            $data=mysqli_query($koneksi,"SELECT * FROM pelanggan");
                        }else{
                            $data=mysqli_query($koneksi,"SELECT * FROM pelanggan WHERE NamaPelanggan LIKE '%$cari%'");
                        }
                        while($d=mysqli_fetch_array($data)){
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $d['NamaPelanggan'] ?></td>
                        <td><?= $d['Alamat'] ?></td>
                        <td><?= $d['NomorTelepon'] ?></td>
                        <td>
                            <a href="?edit=<?= $d['PelangganID'] ?>"
                            class="btn btn-warning btn-sm">
                                Edit
                            </a>
                            <a href="?hapus=<?= $d['PelangganID'] ?>"
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