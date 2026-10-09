<?php
    session_start();
    include "koneksi.php";

    if(!isset($_SESSION['admin'])){
        header("location:login.php");
        exit;
    }

    if($_SESSION['level']!='kasir'){
        header("location:index.php");
        exit;
    }

    if(!isset($_SESSION['keranjang'])){
        $_SESSION['keranjang']=[];
    }

    if(isset($_POST['tambah'])){
        $produk=$_POST['produk'];
        $jumlah=$_POST['jumlah'];
        $pelanggan=$_POST['pelanggan'];

        $p=mysqli_fetch_array(mysqli_query($koneksi,"
        SELECT * FROM produk WHERE ProdukID='$produk'
        "));

        if($produk!="" && $jumlah>0 && $jumlah<=$p['Stok']){

            $_SESSION['pelanggan']=$pelanggan;
            $ada=false;

            foreach($_SESSION['keranjang'] as $i=>$item){
                if($item['ProdukID']==$produk){
                    $_SESSION['keranjang'][$i]['Jumlah'] += $jumlah;
                    $ada=true;
                }
            }

            if(!$ada){
                $_SESSION['keranjang'][]=[
                    'ProdukID'=>$produk,
                    'NamaProduk'=>$p['NamaProduk'],
                    'Harga'=>$p['Harga'],
                    'Jumlah'=>$jumlah
                ];
            }
        }

        header("location:penjualan.php");
        exit;
    }

    if(isset($_GET['hapus_cart'])){
        unset($_SESSION['keranjang'][$_GET['hapus_cart']]);
        $_SESSION['keranjang']=array_values($_SESSION['keranjang']);
        header("location:penjualan.php");
        exit;
    }

    if(isset($_GET['kosongkan'])){
        $_SESSION['keranjang']=[];
        unset($_SESSION['pelanggan']);
        header("location:penjualan.php");
        exit;
    }

    if(isset($_POST['checkout'])){

        $pelanggan=$_SESSION['pelanggan'];
        $tunai=$_POST['tunai'];
        $total=0;

        foreach($_SESSION['keranjang'] as $item){
            $total += $item['Harga']*$item['Jumlah'];
        }

        if($tunai < $total){
            die("Uang tunai kurang.");
        }

        $kembalian=$tunai-$total;

        mysqli_query($koneksi,"
        INSERT INTO penjualan
        VALUES(NULL,CURDATE(),'$total','$pelanggan','$tunai','$kembalian')
        ");

        $id=mysqli_insert_id($koneksi);

        foreach($_SESSION['keranjang'] as $item){

            $produk=$item['ProdukID'];
            $jumlah=$item['Jumlah'];
            $sub=$item['Harga']*$jumlah;

            mysqli_query($koneksi,"
            INSERT INTO detailpenjualan
            VALUES(NULL,'$id','$produk','$jumlah','$sub')
            ");

            mysqli_query($koneksi,"
            UPDATE produk
            SET Stok=Stok-'$jumlah'
            WHERE ProdukID='$produk'
            ");
        }

        $_SESSION['keranjang']=[];
        unset($_SESSION['pelanggan']);

        header("location:penjualan.php");
        exit;
    }

    if(isset($_GET['hapus'])){

        $id=$_GET['hapus'];

        $detail=mysqli_query($koneksi,"
        SELECT * FROM detailpenjualan
        WHERE PenjualanID='$id'
        ");

        while($d=mysqli_fetch_array($detail)){

            mysqli_query($koneksi,"
            UPDATE produk
            SET Stok=Stok+{$d['JumlahProduk']}
            WHERE ProdukID='{$d['ProdukID']}'
            ");
        }

        mysqli_query($koneksi,"
        DELETE FROM detailpenjualan
        WHERE PenjualanID='$id'
        ");

        mysqli_query($koneksi,"
        DELETE FROM penjualan
        WHERE PenjualanID='$id'
        ");

        header("location:penjualan.php");
        exit;
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Penjualan</title>
        <link rel="stylesheet" href="asset/bootstrap.min.css">
    </head>
    <body class="bg-light">
        <div class="container mt-4">
            <span class="d-inline-block">
                <a href="index.php" class="btn btn-secondary btn-sm mb-3">← Kembali ke Dashboard</a>
            </span>
            <div class="card p-4 shadow mb-4">
                <h3 class="text-primary">Tambah Penjualan</h3>
                <form method="post">
                    <label>Pelanggan</label>
                    <select name="pelanggan" class="form-select mb-3" required>
                        <option value="">
                            -- Pilih Pelanggan --
                        </option>
                        <?php
                        $data=mysqli_query($koneksi,
                        "SELECT * FROM pelanggan");
                        while($d=mysqli_fetch_array($data)){
                        ?>
                        <option value="<?= $d['PelangganID'] ?>"
                        <?= isset($_SESSION['pelanggan']) &&
                        $_SESSION['pelanggan']==$d['PelangganID']
                        ? 'selected' : '' ?>>
                            <?= $d['NamaPelanggan'] ?>
                        </option>
                        <?php } ?>
                    </select>
                    <label>Produk</label>
                    <select name="produk" class="form-select mb-2" required>
                        <option value="">-- Pilih Produk --</option>
                        <?php
                        $data=mysqli_query($koneksi,"SELECT * FROM produk WHERE Stok>0");
                        while($p=mysqli_fetch_array($data)){
                        ?>
                        <option value="<?= $p['ProdukID'] ?>">
                            <?= $p['NamaProduk'] ?>
                            - Rp <?= number_format($p['Harga']) ?>
                            - Stok <?= $p['Stok'] ?>
                        </option>
                        <?php } ?>
                    </select>
                    <label>Jumlah</label>
                    <input type="number" name="jumlah" class="form-control mb-3" placeholder="Jumlah" min="1" required>
                    <button name="tambah" class="btn btn-primary">+ Tambah ke Keranjang</button>
                </form>
            </div>
            <?php if(count($_SESSION['keranjang'])>0){ ?>
            <div class="card p-4 shadow mb-4">
                <h3 class="text-primary">
                    🛒 Keranjang
                </h3>
                <table class="table table-bordered">
                    <tr class="table-primary">
                        <th>No</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    $no=1;
                    $total=0;
                    foreach($_SESSION['keranjang'] as $i=>$item){
                        $sub=$item['Harga']*$item['Jumlah'];
                        $total += $sub;
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $item['NamaProduk'] ?></td>
                        <td>
                            Rp <?= number_format($item['Harga']) ?>
                        </td>
                        <td><?= $item['Jumlah'] ?></td>
                        <td>
                            Rp <?= number_format($sub) ?>
                        </td>
                        <td>
                            <a href="?hapus_cart=<?= $i ?>" class="btn btn-danger btn-sm">Hapus</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <tr>
                        <th colspan="4" class="text-end">
                            Total
                        </th>
                        <th>
                            Rp <?= number_format($total) ?>
                        </th>
                        <th></th>
                    </tr>
                </table>
                <a href="?kosongkan=1" class="btn btn-danger btn-sm mb-2">Kosongkan Keranjang</a>
                <?php if(isset($_SESSION['pelanggan'])){ ?>
                <form method="post">
                    <input type="number" name="tunai" class="form-control mb-2" placeholder="Uang Tunai" min="<?= $total ?>" required>
                    <button name="checkout" class="btn btn-success">Checkout</button>
                </form>
                <?php }else{ ?>
                <div class="alert alert-warning">
                    Pilih pelanggan terlebih dahulu.
                </div>
                <?php } ?>
            </div>
            <?php } ?>
            <div class="card p-4 shadow">
                <h3 class="text-primary">
                    Data Penjualan
                </h3>
                <form method="get" class="d-flex gap-2 mb-3">
                    <input type="text" name="cari" class="form-control" placeholder="Cari nama pelanggan..." value="<?= $_GET['cari'] ?? '' ?>">
                    <button class="btn btn-primary btn-sm">Cari</button>
                    <a href="penjualan.php" class="btn btn-secondary btn-sm">Reset</a>
                </form>
                <table class="table table-bordered">
                    <tr class="table-primary">
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Total</th>
                        <th>Aksi</th>
                    </tr>
                    <?php
                    $no=1;
                    $cari=$_GET['cari'] ?? '';
                    $where=$cari ?
                    "WHERE pelanggan.NamaPelanggan LIKE '%$cari%'"
                    : "";
                    $data=mysqli_query($koneksi,"SELECT penjualan.*, pelanggan.NamaPelanggan
                    FROM penjualan JOIN pelanggan ON penjualan.PelangganID=pelanggan.PelangganID
                    $where ORDER BY PenjualanID DESC");
                    while($d=mysqli_fetch_array($data)){
                    ?>
                    <tr>
                        <td><?= $no++ ?></td>
                        <td><?= $d['TanggalPenjualan'] ?></td>
                        <td><?= $d['NamaPelanggan'] ?></td>
                        <td>
                            Rp <?= number_format($d['TotalHarga']) ?>
                        </td>
                        <td>
                            <a href="struk.php?id=<?= $d['PenjualanID'] ?>" class="btn btn-success btn-sm">
                                Struk
                            </a>
                            <a href="?hapus=<?= $d['PenjualanID'] ?>" class="btn btn-danger btn-sm"
                            onclick="return confirm('Hapus transaksi? Stok akan dikembalikan.')">
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