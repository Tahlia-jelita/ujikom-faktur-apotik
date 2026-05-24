<?php
session_start();
if(!isset($_SESSION['user'])) { header("Location: login.php"); exit; }
include 'koneksi.php';
$page = isset($_GET['page']) ? $_GET['page'] : 'beranda';
$action = isset($_GET['action']) ? $_GET['action'] : '';

// =================== PROSES BACKEND (CRUD) ===================

// 1. PROSES PERUSAHAAN (APOTEK)
if (isset($_POST['save_pt'])) {
    $nama = $_POST['nama']; $email = $_POST['email']; $siup = $_POST['no_siup']; $alamat = $_POST['alamat']; $telp = $_POST['telp']; $fax = $_POST['fax'];
    if ($_POST['id_perusahaan'] != '') {
        mysqli_query($conn, "UPDATE perusahaan SET nama_perusahaan='$nama', email='$email', no_siup='$siup', alamat='$alamat', no_telp='$telp', fax='$fax' WHERE id_perusahaan={$_POST['id_perusahaan']}");
    } else {
        mysqli_query($conn, "INSERT INTO perusahaan (nama_perusahaan, email, no_siup, alamat, no_telp, fax) VALUES ('$nama', '$email', '$siup', '$alamat', '$telp', '$fax')");
    }
    header("Location: index.php?page=perusahaan"); exit;
}
if ($action == 'delete_pt') { 
    $id = $_GET['id'];
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0;");
    mysqli_query($conn, "DELETE FROM perusahaan WHERE id_perusahaan=$id");
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1;");
    header("Location: index.php?page=perusahaan"); exit; 
}

// 2. PROSES CUSTOMER (PASIEN)
if (isset($_POST['save_cust'])) {
    $nama = $_POST['nama']; $tgl_lahir = $_POST['tgl_lahir']; $no_telp = $_POST['no_telp']; $email_cust = $_POST['email_customer']; $alamat = $_POST['alamat'];
    if ($_POST['id_customer'] != '') {
        mysqli_query($conn, "UPDATE customer SET nama_customer='$nama', tgl_lahir='$tgl_lahir', no_telp='$no_telp', email_customer='$email_cust', alamat='$alamat' WHERE id_customer={$_POST['id_customer']}");
    } else {
        mysqli_query($conn, "INSERT INTO customer (nama_customer, tgl_lahir, no_telp, email_customer, alamat) VALUES ('$nama', '$tgl_lahir', '$no_telp', '$email_cust', '$alamat')");
    }
    header("Location: index.php?page=customer"); exit;
}
if ($action == 'delete_cust') { 
    $id = $_GET['id'];
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0;");
    mysqli_query($conn, "DELETE FROM customer WHERE id_customer=$id");
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1;");
    header("Location: index.php?page=customer"); exit; 
}

// 3. PROSES PRODUK (OBAT + INTERLEAVED GRAMASI & EXPIRED DATE)
if (isset($_POST['save_produk'])) {
    $nama_dasar = $_POST['nama_base'];
    $gramasi = $_POST['gramasi'];
    $expired = date('d/m/Y', strtotime($_POST['expired_date'])); // Format formal tgl kadaluwarsa
    
    // Hasil String Concatenate: "Paracetamol (500mg) - Exp: 21/05/2029"
    $nama_lengkap = $nama_dasar . " (" . $gramasi . ") - Exp: " . $expired;
    
    $harga = $_POST['harga']; $jenis = $_POST['jenis']; $stok = $_POST['stok'];
    if ($_POST['id_produk'] != '') {
        mysqli_query($conn, "UPDATE produk SET nama_produk='$nama_lengkap', price='$harga', jenis='$jenis', stock='$stok' WHERE id_produk={$_POST['id_produk']}");
    } else {
        mysqli_query($conn, "INSERT INTO produk (nama_produk, price, jenis, stock) VALUES ('$nama_lengkap', '$harga', '$jenis', '$stok')");
    }
    header("Location: index.php?page=produk"); exit;
}
if ($action == 'delete_prod') { 
    $id = $_GET['id'];
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0;");
    mysqli_query($conn, "DELETE FROM produk WHERE id_produk=$id");
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1;");
    header("Location: index.php?page=produk"); exit; 
}

// 4. PROSES KARYAWAN / APOTEKER
if (isset($_POST['save_karyawan'])) {
    $nama = $_POST['nama']; $jabatan = $_POST['jabatan']; $lisensi = $_POST['no_lisensi']; $telp = $_POST['no_telp'];
    if ($_POST['id_karyawan'] != '') {
        mysqli_query($conn, "UPDATE karyawan SET nama_karyawan='$nama', jabatan='$jabatan', no_lisensi='$lisensi', no_telp='$telp' WHERE id_karyawan={$_POST['id_karyawan']}");
    } else {
        mysqli_query($conn, "INSERT INTO karyawan (nama_karyawan, jabatan, no_lisensi, no_telp) VALUES ('$nama', '$jabatan', '$lisensi', '$telp')");
    }
    header("Location: index.php?page=karyawan"); exit;
}
if ($action == 'delete_karyawan') {
    $id = $_GET['id'];
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0;");
    mysqli_query($conn, "DELETE FROM karyawan WHERE id_karyawan=$id");
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1;");
    header("Location: index.php?page=karyawan"); exit;
}

// 5. PROSES TRANSAKSI FAKTUR
if (isset($_POST['save_faktur'])) {
    $nofaktur = $_POST['no_fatur']; $tgl = $_POST['tgl'];
    $metode = $_POST['metode']; $resep = $_POST['resep_dokter'];
    $id_pt = $_POST['id_pt']; $id_cust = $_POST['id_cust']; $id_prod = $_POST['id_prod']; $id_karyawan = $_POST['id_karyawan']; $qty = $_POST['qty'];

    $prod_data = mysqli_fetch_array(mysqli_query($conn, "SELECT price, stock FROM produk WHERE id_produk=$id_prod"));
    $harga_produk = $prod_data['price']; $stok_produk = $prod_data['stock'];

    if ($qty > $stok_produk) {
        echo "<script>alert('Stok tidak mencukupi! Sisa stok saat ini: $stok_produk'); window.location='index.php?page=penjualan';</script>"; exit;
    }

    $sub = $harga_produk * $qty; $ppn = $sub * 0.1; $grand = $sub + $ppn; 

    mysqli_query($conn, "INSERT INTO faktur (no_fatur, tgl_faktur, due_date, metode_bayar, resep_dokter, ppn, dp, grand_total, user, id_customer, id_perusahaan, id_karyawan) 
                         VALUES ('$nofaktur', '$tgl', '$tgl', '$metode', '$resep', '$ppn', 0, '$grand', 'Alif', $id_cust, $id_pt, $id_karyawan)");
    
    mysqli_query($conn, "INSERT INTO detail_faktur (id_produk, no_faktur, qty, price) VALUES ($id_prod, '$nofaktur', $qty, '$harga_produk')");
    mysqli_query($conn, "UPDATE produk SET stock = stock - $qty WHERE id_produk=$id_prod");
    
    header("Location: index.php?page=penjualan"); exit;
}
if ($action == 'delete_faktur') { 
    mysqli_query($conn, "DELETE FROM detail_faktur WHERE no_faktur='{$_GET['no']}'"); 
    mysqli_query($conn, "DELETE FROM faktur WHERE no_faktur='{$_GET['no']}'"); 
    header("Location: index.php?page=penjualan"); exit; 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Royal Luxury Pharmacy</title>
    <style>
        /* LIGHT ROYAL LUXURY & PEARL WHITE THEME STYLING */
        * { box-sizing: border-box; margin: 0; padding: 0; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; transition: all 0.2s ease; }
        body { background: #f4f7f5; color: #2c3e50; display: flex; flex-direction: column; min-height: 100vh; }
        
        header { background: linear-gradient(135deg, #0d2c20 0%, #061711 100%); color: #dfb76c; padding: 25px; text-align: center; border-bottom: 3px solid #dfb76c; box-shadow: 0 4px 15px rgba(0,0,0,0.15); }
        header h1 { font-size: 26px; letter-spacing: 3px; text-transform: uppercase; font-weight: 600; text-shadow: 1px 1px 2px rgba(0,0,0,0.3); }
        
        nav { background: #ffffff; padding: 0; display: flex; justify-content: center; gap: 0; border-bottom: 1px solid #dcdde1; flex-wrap: wrap; box-shadow: 0 2px 5px rgba(0,0,0,0.05); }
        nav a { color: #57606f; text-decoration: none; padding: 16px 24px; font-size: 13px; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px; border-bottom: 3px solid transparent; }
        nav a:hover, nav a.active { color: #0d2c20; background: #f9fbf9; border-bottom: 3px solid #dfb76c; }
        
        .main-wrapper { display: flex; flex: 1; flex-direction: row; width: 100%; max-width: 100%; margin: 0 auto; background: #ffffff; }
        
        aside { width: 260px; background: #ffffff; padding: 30px 15px; border-right: 1px solid #e1e8e5; display: flex; flex-direction: column; gap: 8px; }
        aside p { color: #0d2c20; font-weight: 700; font-size: 12px; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 15px; padding-left: 10px; border-left: 4px solid #dfb76c; }
        aside a { color: #4b655a; text-decoration: none; display: block; padding: 12px 18px; background: #f8faf9; border-radius: 6px; font-size: 13.5px; font-weight: 600; border: 1px solid #edf2f0; }
        aside a:hover { background: #0d2c20; color: #dfb76c; border-left: 4px solid #dfb76c; transform: translateX(4px); box-shadow: 0 4px 10px rgba(13,44,32,0.15); }
        
        main { flex: 1; padding: 35px; background: #f4f7f5; width: 100%; }
        main h2 { margin-bottom: 25px; text-transform: uppercase; font-size: 20px; color: #0d2c20; font-weight: 700; letter-spacing: 1px; border-bottom: 2px solid #dfb76c; padding-bottom: 8px; display: inline-block; }
        
        .card-luxury { background: #ffffff; border: 1px solid #e1e8e5; padding: 30px; border-radius: 12px; box-shadow: 0 6px 18px rgba(0,0,0,0.04); margin-bottom: 30px; }
        
        .table-responsive { width: 100%; overflow-x: auto; margin-top: 20px; border-radius: 8px; border: 1px solid #e1e8e5; box-shadow: 0 4px 12px rgba(0,0,0,0.02); }
        table { width: 100%; border-collapse: collapse; background: #ffffff; }
        th, td { padding: 14px 16px; text-align: left; font-size: 13.5px; border-bottom: 1px solid #edf2f0; }
        th { background: #0d2c20; color: #dfb76c; font-weight: 600; text-transform: uppercase; letter-spacing: 0.5px; font-size: 12px; }
        td { color: #2f3542; font-weight: 500; }
        tr:hover { background: #f9fbf9; }
        
        .btn { padding: 9px 18px; background: linear-gradient(135deg, #0d2c20 0%, #061711 100%); color: #dfb76c; text-decoration: none; font-size: 11.5px; font-weight: 700; border: none; cursor: pointer; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px; display: inline-block; box-shadow: 0 4px 10px rgba(13,44,32,0.15); }
        .btn:hover { transform: translateY(-1px); box-shadow: 0 6px 15px rgba(13,44,32,0.25); background: linear-gradient(135deg, #143f2e 0%, #0a241b 100%); }
        .btn-danger { background: linear-gradient(135deg, #ff4757 0%, #ee5253 100%); color: #fff; box-shadow: 0 4px 10px rgba(255,71,87,0.2); }
        .btn-danger:hover { background: linear-gradient(135deg, #ff6b81 0%, #ff4757 100%); box-shadow: 0 6px 15px rgba(255,71,87,0.3); }
        .btn-edit { background: linear-gradient(135deg, #2e86de 0%, #1e3799 100%); color: #fff; box-shadow: 0 4px 10px rgba(30,55,153,0.2); }
        .btn-edit:hover { background: linear-gradient(135deg, #54a0ff 0%, #2e86de 100%); }
        
        .form-box { background: #ffffff; padding: 25px; border: 1px solid #e1e8e5; margin-bottom: 25px; display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; border-radius: 8px; }
        .form-box-4col { background: #ffffff; padding: 25px; border: 1px solid #e1e8e5; margin-bottom: 25px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; border-radius: 8px; }
        .form-group { display: flex; flex-direction: column; }
        .form-group label { font-size: 12px; text-transform: uppercase; color: #0d2c20; margin-bottom: 8px; letter-spacing: 0.5px; font-weight: 700; }
        .form-box input, .form-box select, .form-box-4col input, .form-box-4col select { padding: 12px; background: #f8faf9; border: 1px solid #ced6d0; color: #2c3e50; border-radius: 6px; font-size: 13px; font-weight: 500; }
        .form-box input:focus, .form-box select:focus, .form-box-4col input:focus, .form-box-4col select:focus { outline: none; border-color: #dfb76c; background: #ffffff; box-shadow: 0 0 8px rgba(223,183,108,0.25); }
        
        .dashboard-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 20px; margin-bottom: 30px; }
        .widget-card { background: #ffffff; border: 1px solid #e1e8e5; border-top: 4px solid #dfb76c; padding: 25px; border-radius: 10px; box-shadow: 0 6px 15px rgba(0,0,0,0.03); }
        .widget-card h3 { font-size: 11.5px; color: #747d8c; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 12px; font-weight: 700; }
        .widget-card .value { font-size: 32px; font-weight: 700; color: #0d2c20; }
        
        footer { background: #ffffff; text-align: center; padding: 20px; font-size: 12px; color: #747d8c; border-top: 1px solid #e1e8e5; letter-spacing: 0.5px; font-weight: 500; }

        @media (max-width: 1200px) {
            .form-box, .form-box-4col { grid-template-columns: repeat(2, 1fr); }
            .dashboard-grid { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 992px) {
            .main-wrapper { flex-direction: column; }
            aside { width: 100%; border-right: none; border-bottom: 1px solid #e1e8e5; padding: 20px; }
            .form-box, .form-box-4col { grid-template-columns: 1fr; }
            .dashboard-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <header>
        <h1>THE ROYAL PHARMACY SYSTEM v2004</h1>
    </header>

    <nav>
        <a href="index.php?page=beranda" class="<?=$page=='beranda'?'active':''?>">Beranda</a>
        <a href="index.php?page=perusahaan" class="<?=$page=='perusahaan'?'active':''?>">Kelola Perusahaan</a>
        <a href="index.php?page=karyawan" class="<?=$page=='karyawan'?'active':''?>">Kelola Karyawan</a>
        <a href="index.php?page=customer" class="<?=$page=='customer'?'active':''?>">Kelola Customer</a>
        <a href="index.php?page=produk" class="<?=$page=='produk'?'active':''?>">Kelola Produk</a>
        <a href="index.php?page=penjualan" class="<?=$page=='penjualan'?'active':''?>">Kelola Penjualan</a>
        <a href="login.php" style="background: #ff4757; color:#fff; border-bottom:none; margin-left: auto; font-weight: bold;">Logout</a>
    </nav>

    <div class="main-wrapper">
        <aside>
            <p>CONSOLES NODE</p>
            <a href="index.php?page=beranda">» Main Dashboard</a>
            <a href="index.php?page=perusahaan">» Data Apotek</a>
            <a href="index.php?page=karyawan">» Data Apoteker / Staff</a>
            <a href="index.php?page=customer">» Data Pasien (Customer)</a>
            <a href="index.php?page=produk">» Master Data Obat</a>
            <a href="index.php?page=penjualan">» Transaksi Penjualan</a>
        </aside>

        <main>
            <div class="card-luxury">
                <?php 
                // =================== INTERFACE BERANDA / DASHBOARD ===================
                if ($page == 'beranda') { 
                    $count_pt = mysqli_num_rows(mysqli_query($conn, "SELECT id_perusahaan FROM perusahaan"));
                    $count_cust = mysqli_num_rows(mysqli_query($conn, "SELECT id_customer FROM customer"));
                    $count_prod = mysqli_num_rows(mysqli_query($conn, "SELECT id_produk FROM produk"));
                    $count_faktur = mysqli_num_rows(mysqli_query($conn, "SELECT no_fatur FROM faktur"));
                    $sum_income = mysqli_fetch_array(mysqli_query($conn, "SELECT SUM(grand_total) as total FROM faktur"));
                    $total_income = $sum_income['total'] ? $sum_income['total'] : 0;
                ?>
                    <h2>Main Dashboard</h2>
                    <p style="color:#747d8c; font-size:13px; margin-bottom: 25px;">Engine Port Active: :2004. Executive Class Premium Pharmacy Factoring System.</p>
                    <div class="dashboard-grid">
                        <div class="widget-card">
                            <h3>Apotek / PT</h3><div class="value"><?=$count_pt?></div>
                        </div>
                        <div class="widget-card">
                            <h3>Pasien Terdaftar</h3><div class="value"><?=$count_cust?></div>
                        </div>
                        <div class="widget-card">
                            <h3>Stok Komoditas Obat</h3><div class="value"><?=$count_prod?></div>
                        </div>
                        <div class="widget-card" style="border-top-color: #00aa55;">
                            <h3>Faktur Keluar</h3><div class="value"><?=$count_faktur?></div>
                        </div>
                    </div>
                    <div style="background:#f8faf9; border: 1px solid #e1e8e5; border-left: 5px solid #00ff66; padding:25px; border-radius:8px; box-shadow: 0 4px 10px rgba(0,0,0,0.01);">
                        <h3 style="font-size:11.5px; color:#2f3542; text-transform:uppercase; letter-spacing:1px; margin-bottom:8px; font-weight: 700;">Total Pendapatan Apotek</h3>
                        <div style="font-size:28px; font-weight:bold; color:#0d2c20; font-family:monospace;">Rp <?=number_format($total_income)?></div>
                    </div>

                <?php 
                // =================== INTERFACE PERUSAHAAN (APOTEK) ===================
                } elseif ($page == 'perusahaan') { 
                    $id = ''; $nama = ''; $email = ''; $siup = ''; $alamat = ''; $telp = ''; $fax = ''; $btn_text = 'SIMPAN DATA MASTER';
                    if ($action == 'edit') {
                        $res = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM perusahaan WHERE id_perusahaan={$_GET['id']}"));
                        $id = $res['id_perusahaan']; $nama = $res['nama_perusahaan']; $email = $res['email']; $siup = $res['no_siup']; $alamat = $res['alamat']; $telp = $res['no_telp']; $fax = $res['fax'];
                        $btn_text = 'UPDATE DATA MASTER';
                    }
                ?>
                    <h2>Kelola Perusahaan Apotek</h2>
                    <form action="" method="POST" class="form-box">
                        <input type="hidden" name="id_perusahaan" value="<?=$id?>">
                        <div class="form-group"><label>Nama Apotek</label><input type="text" name="nama" value="<?=$nama?>" required></div>
                        <div class="form-group"><label>Email Apotek</label><input type="email" name="email" value="<?=$email?>" required></div>
                        <div class="form-group"><label>Nomor SIUP</label><input type="text" name="no_siup" value="<?=$siup?>" required></div>
                        <div class="form-group"><label>Alamat Operasional</label><input type="text" name="alamat" value="<?=$alamat?>" required></div>
                        <div class="form-group"><label>No Telepon</label><input type="text" name="telp" value="<?=$telp?>" required></div>
                        <div class="form-group"><label>No Fax</label><input type="text" name="fax" value="<?=$fax?>"></div>
                        <button type="submit" name="save_pt" class="btn" style="grid-column: span 3; margin-top:5px; padding:12px;"><?=$btn_text?></button>
                    </form>
                    <div class="table-responsive">
                        <table>
                            <tr><th>Nama Apotek</th><th>Email</th><th>No SIUP</th><th>Alamat</th><th>No Telp</th><th>Fax</th><th>Aksi</th></tr>
                            <?php $q = mysqli_query($conn, "SELECT * FROM perusahaan"); while($d = mysqli_fetch_array($q)){ ?>
                            <tr><td><?=$d['nama_perusahaan']?></td><td><?=$d['email']?></td><td><strong><?=$d['no_siup']?></strong></td><td><?=$d['alamat']?></td><td><?=$d['no_telp']?></td><td><?=$d['fax']?></td>
                            <td>
                                <a href="index.php?page=perusahaan&action=edit&id=<?=$d['id_perusahaan']?>" class="btn btn-edit">Ubah</a>
                                <a href="index.php?page=perusahaan&action=delete_pt&id=<?=$d['id_perusahaan']?>" class="btn btn-danger" onclick="return confirm('Hapus data?')">Hapus</a>
                            </td></tr>
                            <?php } ?>
                        </table>
                    </div>

                <?php 
                // =================== INTERFACE KARYAWAN / APOTEKER ===================
                } elseif ($page == 'karyawan') { 
                    $id = ''; $nama = ''; $jabatan = ''; $lisensi = ''; $telp = ''; $btn_text = 'SIMPAN KARYAWAN';
                    if ($action == 'edit') {
                        $res = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM karyawan WHERE id_karyawan={$_GET['id']}"));
                        $id = $res['id_karyawan']; $nama = $res['nama_karyawan']; $jabatan = $res['jabatan']; $lisensi = $res['no_lisensi']; $telp = $res['no_telp'];
                        $btn_text = 'UPDATE KARYAWAN';
                    }
                ?>
                    <h2>Kelola Karyawan / Apoteker</h2>
                    <form action="" method="POST" class="form-box">
                        <input type="hidden" name="id_karyawan" value="<?=$id?>">
                        <div class="form-group"><label>Nama Lengkap & Gelar</label><input type="text" name="nama" value="<?=$nama?>" required></div>
                        <div class="form-group">
                            <label>Jabatan</label>
                            <select name="jabatan" required>
                                <option value="Apoteker Pengelola" <?=$jabatan=='Apoteker Pengelola'?'selected':''?>>Apoteker Pengelola (SIPA)</option>
                                <option value="Asisten Apoteker" <?=$jabatan=='Asisten Apoteker'?'selected':''?>>Asisten Apoteker</option>
                                <option value="Staff Kasir" <?=$jabatan=='Staff Kasir'?'selected':''?>>Staff Kasir</option>
                            </select>
                        </div>
                        <div class="form-group"><label>No SIPA (Lisensi Apoteker)</label><input type="text" name="no_lisensi" value="<?=$lisensi?>"></div>
                        <div class="form-group"><label>No Handphone</label><input type="text" name="no_telp" value="<?=$telp?>" required></div>
                        <button type="submit" name="save_karyawan" class="btn" style="grid-column: span 3; margin-top:5px; padding:12px;"><?=$btn_text?></button>
                    </form>
                    <div class="table-responsive">
                        <table>
                            <tr><th>Nama Karyawan</th><th>Jabatan</th><th>No Lisensi (SIPA)</th><th>No Telp</th><th>Aksi</th></tr>
                            <?php $q = mysqli_query($conn, "SELECT * FROM karyawan"); while($d = mysqli_fetch_array($q)){ ?>
                            <tr><td><?=$d['nama_karyawan']?></td><td><?=$d['jabatan']?></td><td><?=$d['no_lisensi']?$d['no_lisensi']:'-'?></td><td><?=$d['no_telp']?></td>
                            <td>
                                <a href="index.php?page=karyawan&action=edit&id=<?=$d['id_karyawan']?>" class="btn btn-edit">Ubah</a>
                                <a href="index.php?page=karyawan&action=delete_karyawan&id=<?=$d['id_karyawan']?>" class="btn btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                            </td></tr>
                            <?php } ?>
                        </table>
                    </div>

                <?php 
                // =================== INTERFACE CUSTOMER (PASIEN) ===================
                } elseif ($page == 'customer') { 
                    $id = ''; $nama = ''; $tgl_lahir = ''; $no_telp = ''; $email_cust = ''; $alamat = ''; $btn_text = 'SIMPAN CUSTOMER';
                    if ($action == 'edit') {
                        $res = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM customer WHERE id_customer={$_GET['id']}"));
                        $id = $res['id_customer']; $nama = $res['nama_customer']; $tgl_lahir = $res['tgl_lahir']; $no_telp = $res['no_telp']; $email_cust = $res['email_customer']; $alamat = $res['alamat'];
                        $btn_text = 'UPDATE CUSTOMER';
                    }
                ?>
                    <h2>Kelola Pasien (Customer Premium)</h2>
                    <form action="" method="POST" class="form-box">
                        <input type="hidden" name="id_customer" value="<?=$id?>">
                        <div class="form-group"><label>Nama Lengkap Pasien</label><input type="text" name="nama" value="<?=$nama?>" required></div>
                        <div class="form-group"><label>Tanggal Lahir</label><input type="date" name="tgl_lahir" value="<?=$tgl_lahir?>" required></div>
                        <div class="form-group"><label>No Telepon Pasien</label><input type="text" name="no_telp" value="<?=$no_telp?>" required></div>
                        <div class="form-group"><label>Email Pasien</label><input type="email" name="email_customer" value="<?=$email_cust?>" required></div>
                        <div class="form-group" style="grid-column: span 2;"><label>Alamat Rumah Lengkap</label><input type="text" name="alamat" value="<?=$alamat?>" required></div>
                        <button type="submit" name="save_cust" class="btn" style="grid-column: span 3; margin-top:5px; padding:12px;"><?=$btn_text?></button>
                    </form>
                    <div class="table-responsive">
                        <table>
                            <tr><th>Nama Pasien</th><th>Tgl Lahir</th><th>No Telpon</th><th>Email Customer</th><th>Alamat</th><th>Aksi</th></tr>
                            <?php $q = mysqli_query($conn, "SELECT * FROM customer"); while($d = mysqli_fetch_array($q)){ ?>
                            <tr><td><?=$d['nama_customer']?></td><td><?=$d['tgl_lahir']?></td><td><?=$d['no_telp']?></td><td><?=$d['email_customer']?></td><td><?=$d['alamat']?></td>
                            <td>
                                <a href="index.php?page=customer&action=edit&id=<?=$d['id_customer']?>" class="btn btn-edit">Ubah</a>
                                <a href="index.php?page=customer&action=delete_cust&id=<?=$d['id_customer']?>" class="btn btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                            </td></tr>
                            <?php } ?>
                        </table>
                    </div>

                <?php 
                // =================== INTERFACE PRODUK (OBAT + REVISI GRAMASI & EXPIRED) ===================
                } elseif ($page == 'produk') { 
                    $id = ''; $nama_input = ''; $harga = ''; $jenis = ''; $stok = ''; $btn_text = 'SIMPAN PRODUK';
                    if ($action == 'edit') {
                        $res = mysqli_fetch_array(mysqli_query($conn, "SELECT * FROM produk WHERE id_produk={$_GET['id']}"));
                        $id = $res['id_produk']; 
                        
                        // Trik Regex: Ambil bagian nama obat murni sebelum tanda kurung gramasi
                        $nama_full = $res['nama_produk'];
                        if (preg_match('/(.*)\s\((.*)\)/', $nama_full, $matches)) {
                            $nama_input = trim($matches[1]);
                        } else {
                            $nama_input = $nama_full;
                        }
                        
                        $harga = $res['price']; $jenis = $res['jenis']; $stok = $res['stock'];
                        $btn_text = 'UPDATE PRODUK';
                    }
                ?>
                    <h2>Kelola Data Obat (Produk Vault)</h2>
                    <form action="" method="POST" class="form-box-4col">
                        <input type="hidden" name="id_produk" value="<?=$id?>">
                        <div class="form-group">
                            <label>Nama Obat (Tanpa Atribut)</label>
                            <input type="text" name="nama_base" value="<?=$nama_input?>" placeholder="Contoh: Paracetamol, Bodrex" required>
                        </div>
                        
                        <div class="form-group">
                            <label>Gramasi / Dosis Obat</label>
                            <select name="gramasi" required>
                                <option value="100mg">100 mg</option>
                                <option value="250mg">250 mg</option>
                                <option value="500mg" selected>500 mg</option>
                                <option value="650mg">650 mg</option>
                                <option value="1000mg">1000 mg (1gr)</option>
                                <option value="5gr">5 gr</option>
                                <option value="10gr">10 gr</option>
                                <option value="Liquid 60ml">Liquid 60 ml</option>
                                <option value="Liquid 120ml">Liquid 120 ml</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Tanggal Kadaluwarsa (Expired)</label>
                            <input type="date" name="expired_date" value="<?=date('Y-m-d', strtotime('+3 years'))?>" required>
                        </div>

                        <div class="form-group"><label>Harga Per Satuan</label><input type="number" name="harga" value="<?=$harga?>" required></div>
                        <div class="form-group"><label>Golongan / Jenis Obat</label><input type="text" name="jenis" value="<?=$jenis?>" required></div>
                        <div class="form-group"><label>Stok Sisa</label><input type="number" name="stok" value="<?=$stok?>" required></div>
                        
                        <button type="submit" name="save_produk" class="btn" style="grid-column: span 4; margin-top:5px; padding:12px;"><?=$btn_text?></button>
                    </form>
                    
                    <div class="table-responsive">
                        <table>
                            <tr><th>Nama Obat, Gramasi & Expired Date</th><th>Harga</th><th>Golongan</th><th>Stok</th><th>Aksi</th></tr>
                            <?php $q = mysqli_query($conn, "SELECT * FROM produk"); while($d = mysqli_fetch_array($q)){ ?>
                            <tr><td><strong><?=$d['nama_produk']?></strong></td><td>Rp <?=number_format($d['price'])?></td><td><?=$d['jenis']?></td><td><?=$d['stock']?></td>
                            <td>
                                <a href="index.php?page=produk&action=edit&id=<?=$d['id_produk']?>" class="btn btn-edit">Ubah</a>
                                <a href="index.php?page=produk&action=delete_prod&id=<?=$d['id_produk']?>" class="btn btn-danger" onclick="return confirm('Hapus?')">Hapus</a>
                            </td></tr>
                            <?php } ?>
                        </table>
                    </div>

                <?php 
                // =================== INTERFACE TRANSAKSI PENJUALAN APOTEK ===================
                } elseif ($page == 'penjualan') { ?>
                    <h2>Transaksi Penjualan Apotek (Luxury Checkout)</h2>
                    <form action="" method="POST" class="form-box">
                        <div class="form-group"><label>No Faktur</label><input type="text" name="no_fatur" value="FKT-2004-<?=rand(100,999)?>" readonly></div>
                        <div class="form-group"><label>Tanggal</label><input type="date" name="tgl" value="<?=date('Y-m-d')?>"></div>
                        
                        <div class="form-group">
                            <label>Metode Pembayaran</label>
                            <select name="metode">
                                <option value="TUNAI">TUNAI</option>
                                <option value="TRANSFER">TRANSFER</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Otoritas Resep</label>
                            <select name="resep_dokter" required>
                                <option value="Tanpa Resep">Tanpa Resep (Obat Bebas)</option>
                                <option value="Ada Resep Dokter">Ada Resep Dokter (Obat Keras)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Perusahaan Apotek</label>
                            <select name="id_pt">
                                <?php $pts=mysqli_query($conn,"SELECT * FROM perusahaan"); while($p=mysqli_fetch_array($pts)){ echo "<option value='{$p['id_perusahaan']}'>{$p['nama_perusahaan']}</option>"; } ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Apoteker Melayani</label>
                            <select name="id_karyawan" required>
                                <?php $kars=mysqli_query($conn,"SELECT * FROM karyawan"); while($k=mysqli_fetch_array($kars)){ echo "<option value='{$k['id_karyawan']}'>{$k['nama_karyawan']} ({$k['jabatan']})</option>"; } ?>
                            </select>
                        </div>
                        
                        <div class="form-group" style="grid-column: span 2;">
                            <label>Pasien Premium (Biodata Lengkap)</label>
                            <select name="id_cust" required>
                                <option value="">-- Pilih Pasien --</option>
                                <?php 
                                $cts = mysqli_query($conn, "SELECT * FROM customer"); 
                                while($c = mysqli_fetch_array($cts)){ 
                                    $tgl_formatted = date('d-m-Y', strtotime($c['tgl_lahir']));
                                    echo "<option value='{$c['id_customer']}'>{$c['nama_customer']} [Lahir: $tgl_formatted | Telp: {$c['no_telp']} | Alamat: {$c['alamat']}]</option>"; 
                                } 
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Pilih Komoditas Obat</label>
                            <select name="id_prod">
                                <?php $pds=mysqli_query($conn,"SELECT * FROM produk"); while($p=mysqli_fetch_array($pds)){ echo "<option value='{$p['id_produk']}'>{$p['nama_produk']} - Rp ".number_format($p['price'])." (Stok: {$p['stock']})</option>"; } ?>
                            </select>
                        </div>
                        
                        <div class="form-group"><label>Quantity</label><input type="number" name="qty" min="1" value="1"></div>
                        <button type="submit" name="save_faktur" class="btn" style="grid-column: span 3; background: linear-gradient(135deg, #0d2c20 0%, #061711 100%); color:#dfb76c; padding:12px; margin-top:5px;">EXECUTE & GENERATE FACTURE</button>
                    </form>
                    <div class="table-responsive">
                        <table>
                            <tr><th>No Faktur</th><th>Tanggal</th><th>Pasien</th><th>Resep Dokter</th><th>Grand Total</th><th>Aksi</th></tr>
                            <?php $q = mysqli_query($conn, "SELECT f.*, c.nama_customer FROM faktur f JOIN customer c ON f.id_customer=c.id_customer"); while($d = mysqli_fetch_array($q)){ ?>
                            <tr><td><?=$d['no_fatur']?></td><td><?=$d['tgl_faktur']?></td><td><?=$d['nama_customer']?></td><td><span style="background:#f4f7f5; padding:4px 8px; border-radius:4px; font-size:11px; color:#0d2c20; font-weight:bold; border:1px solid #dfb76c;"><?=$d['resep_dokter']?></span></td><td>Rp <?=number_format($d['grand_total'])?></td>
                            <td>
                                <a href="cetak_faktur.php?no=<?=$d['no_fatur']?>" target="_blank" class="btn" style="background: linear-gradient(135deg, #0d2c20 0%, #061711 100%); color:#dfb76c;">Cetak</a> 
                                <a href="index.php?page=penjualan&action=delete_faktur&no=<?=$d['no_fatur']?>" class="btn btn-danger" onclick="return confirm('Hapus transaksi?')">Hapus</a>
                            </td></tr>
                            <?php } ?>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </main>
    </div>

    <footer>&copy; 2026 The Royal Palace Luxury Pharmacy v2004. Licensed Server Node Active.</footer>
</body>
</html>