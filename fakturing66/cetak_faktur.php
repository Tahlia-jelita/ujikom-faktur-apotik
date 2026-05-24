<?php
include 'koneksi.php';
$no = $_GET['no'];
$data = mysqli_fetch_array(mysqli_query($conn, "SELECT f.*, c.nama_customer, c.tgl_lahir, c.no_telp as telp_cust, c.alamat as al_cust, p.nama_perusahaan, p.no_siup, p.alamat as al_pt, p.no_telp as telp_pt, p.fax, k.nama_karyawan, k.no_lisensi FROM faktur f JOIN customer c ON f.id_customer=c.id_customer JOIN perusahaan p ON f.id_perusahaan=p.id_perusahaan JOIN karyawan k ON f.id_karyawan=k.id_karyawan WHERE f.no_fatur='$no'"));
?>
<!DOCTYPE html>
<html>
<head>
    <title>Faktur <?=$no?></title>
    <style>body { font-family: monospace; padding: 20px; } .box { border: 1px solid #000; padding: 20px; width: 100%; max-width: 750px; margin: auto; } table { width: 100%; border-collapse: collapse; margin-top: 15px; } th, td { border: 1px solid #000; padding: 8px; font-size: 12px; }</style>
</head>
<body onload="window.print()">
<div class="box">
    <div style="display:flex; justify-content:space-between; border-bottom:2px solid #000; padding-bottom:10px;">
        <div>
            <strong><?=$data['nama_perusahaan']?></strong><br>
            <small>SIUP: <?=$data['no_siup']?></small><br>
            <small><?=$data['al_pt']?><br>Telp: <?=$data['telp_pt']?> | Fax: <?=$data['fax']?></small>
        </div>
        <div align="right"><h2>FAKTUR OBAT</h2><small>No: <?=$no?></small></div>
    </div>
    <div style="display:flex; justify-content:space-between; margin-top:15px; font-size:12px;">
        <div>
            <strong>DATA PASIEN:</strong><br>
            Nama Pasien: <?=$data['nama_customer']?><br>
            Tgl Lahir : <?=$data['tgl_lahir']?><br>
            No Telpon : <?=$data['telp_cust']?><br>
            Alamat    : <?=$data['al_cust']?>
        </div>
        <div>
            <strong>METODE OPERASIONAL:</strong><br>
            Tanggal Keluar : <?=$data['tgl_faktur']?><br>
            Metode Bayar   : <?=$data['metode_bayar']?><br>
            Status Validasi: <strong><?=$data['resep_dokter']?></strong><br>
            Apoteker       : <?=$data['nama_karyawan']?> (<?=$data['no_lisensi']?>)
        </div>
    </div>
    <table>
        <tr><th>No</th><th>Nama Komoditas Obat</th><th>Qty</th><th>Harga Satuan</th><th>Subtotal</th></tr>
        <?php 
        $i=1; $det=mysqli_query($conn, "SELECT df.*, p.nama_produk FROM detail_faktur df JOIN produk p ON df.id_produk=p.id_produk WHERE df.no_faktur='$no'");
        while($r=mysqli_fetch_array($det)){ $sub = $r['qty']*$r['price'];
        echo "<tr><td>$i</td><td>{$r['nama_produk']}</td><td>{$r['qty']}</td><td>Rp ".number_format($r['price'])."</td><td>Rp ".number_format($sub)."</td></tr>"; $i++; }
        ?>
        <tr><td colspan="4" align="right">Pajak Farmasi (PPN 10%)</td><td>Rp <?=number_format($data['ppn'])?></td></tr>
        <tr><td colspan="4" align="right"><strong>Grand Total Akhir</strong></td><td><strong>Rp <?=number_format($data['grand_total'])?></strong></td></tr>
    </table>
    <div style="display:flex; justify-content:space-between; margin-top:40px; font-size:12px;">
        <div align="center">Pasien/Penerima<br><br><br><br>( ............................ )</div>
        <div align="center">Apoteker Pengelola<br><br><br><br><strong>( <?=$data['nama_karyawan']?> )</strong></div>
    </div>
</div>
</body>
</html>