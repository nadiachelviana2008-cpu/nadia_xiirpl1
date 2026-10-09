<?php
    include "koneksi.php";

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

    <title>Cetak Laporan</title>

    <link rel="stylesheet" href="asset/bootstrap.min.css">

    <style>

        @media print{

            @page{
                size: landscape;
                margin: 10mm;
            }

            .tombol{
                display:none;
            }

            table{
                width:100%;
            }

            thead{
                display:table-header-group;
            }

            tr{
                page-break-inside:avoid;
            }

            body{
                font-size:12px;
            }

            h3{
                margin-bottom:5px;
            }

        }

    </style>

</head>

<body>

    <div class="container mt-4">

        <h3 class="text-center fw-bold">
            LAPORAN PENJUALAN
        </h3>

        <p class="text-center mb-3">

            <?php if($dari!="" && $sampai!=""){ ?>

                Periode:
                <?= date('d-m-Y',strtotime($dari)) ?>
                s/d
                <?= date('d-m-Y',strtotime($sampai)) ?>

            <?php }elseif($dari!=""){ ?>

                Mulai:
                <?= date('d-m-Y',strtotime($dari)) ?>

            <?php }elseif($sampai!=""){ ?>

                Sampai:
                <?= date('d-m-Y',strtotime($sampai)) ?>

            <?php }else{ ?>

                Semua Data

            <?php } ?>

        </p>


        <table class="table table-bordered table-sm">

            <thead>

                <tr class="table-primary">

                    <th>No</th>
                    <th>Tanggal</th>
                    <th>Pelanggan</th>
                    <th>Barang Dibeli</th>
                    <th>Jumlah</th>
                    <th>Total</th>

                </tr>

            </thead>

            <tbody>

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

            </tbody>

        </table>


        <div class="text-end">

            <h5 class="fw-bold">

                Total Penjualan:
                Rp <?= number_format($totalUang) ?>

            </h5>

        </div>

    </div>


    <div class="tombol text-center mt-3">

        <button
            onclick="window.print()"
            class="btn btn-primary btn-sm">

            Cetak

        </button>

        <a
            href="index.php"
            class="btn btn-secondary btn-sm">

            Kembali

        </a>

    </div>

</body>

</html>